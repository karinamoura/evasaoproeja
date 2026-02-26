<?php

namespace App\Http\Controllers;

use App\Models\Estudante;
use App\Models\Disciplina;
use App\Models\Frequencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrequenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Se for professor, mostra apenas suas disciplinas
        if ($user->hasRole('professor')) {
            $disciplinas = Disciplina::where('professor_id', $user->id)
                ->with(['oferta', 'estudantes'])
                ->orderBy('id', 'DESC')
                ->get();
        } else {
            // Admin/Coordenador vê todas
            $disciplinas = Disciplina::with(['professor', 'oferta', 'estudantes'])
                ->orderBy('id', 'DESC')
                ->get();
        }

        return view('admin.frequencias.index', compact('disciplinas'));
    }

    /**
     * Show the form for creating/registering frequency.
     */
    public function create($disciplinaId)
    {
        $disciplina = Disciplina::with(['estudantes', 'oferta'])->findOrFail(decrypt($disciplinaId));

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para registrar frequência nesta disciplina.');
        }

        // Buscar frequências existentes para cada estudante
        $percentualTempoDecorrido = $disciplina->getPercentualTempoDecorrido();
        $estudantesComFrequencia = [];

        foreach ($disciplina->estudantes as $estudante) {
            $frequenciaTotal = $estudante->getFrequenciaTotal($disciplina->id);
            $percentualFrequencia = $disciplina->carga_horaria_total > 0
                ? ($frequenciaTotal / $disciplina->carga_horaria_total) * 100
                : 0;

            // Só mostrar alertas se já passou mais de 50% do tempo da disciplina
            $status = 'ok';

            if ($percentualTempoDecorrido > 50) {
                // Se já passou mais de 50% do tempo, verificar frequência
                if ($percentualFrequencia >= 75) {
                    $status = 'ok';
                } elseif ($percentualFrequencia >= 50) {
                    $status = 'atencao';
                } else {
                    $status = 'risco';
                }
            } else {
                // Se ainda está nos primeiros 50% do tempo, sempre OK
                $status = 'ok';
            }

            $estudantesComFrequencia[] = [
                'estudante' => $estudante,
                'frequencia_total' => $frequenciaTotal,
                'carga_horaria_total' => $disciplina->carga_horaria_total,
                'percentual' => round($percentualFrequencia, 2),
                'status' => $status,
            ];
        }

        // Ordenar estudantes alfabeticamente por nome
        usort($estudantesComFrequencia, function($a, $b) {
            return strcmp($a['estudante']->nome, $b['estudante']->nome);
        });

        return view('admin.frequencias.create', compact('disciplina', 'estudantesComFrequencia'));
    }

    /**
     * Store frequency records (single or bulk).
     */
    public function store(Request $request)
    {
        $request->validate([
            'disciplina_id' => 'required|exists:disciplinas,id',
            'data_aula_global' => 'required|date',
            'carga_horaria_geral' => 'required|numeric|min:0',
            'frequencias' => 'required|array',
            'frequencias.*.estudante_id' => 'required|exists:estudantes,id',
            'frequencias.*.hora_aula' => 'required|numeric|min:0',
            'frequencias.*.observacoes' => 'nullable|string|max:1000',
        ]);

        $disciplina = Disciplina::findOrFail($request->disciplina_id);

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para registrar frequência nesta disciplina.');
        }

        $dataAula = $request->data_aula_global;
        $registros = [];

        foreach ($request->frequencias as $freq) {
            // Processar todos os estudantes, mesmo os que faltaram (hora_aula = 0)
            if (isset($freq['estudante_id']) && isset($freq['hora_aula'])) {
                $registros[] = [
                    'estudante_id' => $freq['estudante_id'],
                    'disciplina_id' => $request->disciplina_id,
                    'data_aula' => $dataAula,
                    'hora_aula' => $freq['hora_aula'] ?? 0,
                    'observacoes' => $freq['observacoes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($registros)) {
            Frequencia::insert($registros);
        }

        return redirect()->route('admin.frequencia.show', encrypt($request->disciplina_id))
            ->with('success', 'Frequência registrada com sucesso.');
    }

    /**
     * Display frequency records for a discipline.
     */
    public function show(string $id)
    {
        $disciplina = Disciplina::with(['professor', 'oferta', 'estudantes'])->findOrFail(decrypt($id));

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para visualizar esta disciplina.');
        }

        // Buscar todas as frequências da disciplina
        $frequencias = Frequencia::where('disciplina_id', $disciplina->id)
            ->with('estudante')
            ->orderBy('data_aula', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Ordenar estudantes alfabeticamente
        $estudantesOrdenados = $disciplina->estudantes->sortBy('nome')->values();

        // Calcular estatísticas por estudante
        $percentualTempoDecorrido = $disciplina->getPercentualTempoDecorrido();
        $estatisticas = [];

        foreach ($estudantesOrdenados as $estudante) {
            $frequenciaTotal = $estudante->getFrequenciaTotal($disciplina->id);
            $percentualFrequencia = $disciplina->carga_horaria_total > 0
                ? ($frequenciaTotal / $disciplina->carga_horaria_total) * 100
                : 0;

            // Só mostrar alertas se já passou mais de 50% do tempo da disciplina
            $status = 'ok';
            $alerta_evasao = false;

            if ($percentualTempoDecorrido > 50) {
                // Se já passou mais de 50% do tempo, verificar frequência
                if ($percentualFrequencia >= 75) {
                    $status = 'ok';
                    $alerta_evasao = false;
                } elseif ($percentualFrequencia >= 50) {
                    $status = 'atencao';
                    $alerta_evasao = false;
                } else {
                    $status = 'risco';
                    $alerta_evasao = true;
                }
            } else {
                // Se ainda está nos primeiros 50% do tempo, sempre OK
                $status = 'ok';
                $alerta_evasao = false;
            }

            $estatisticas[$estudante->id] = [
                'frequencia_total' => $frequenciaTotal,
                'carga_horaria_total' => $disciplina->carga_horaria_total,
                'percentual' => round($percentualFrequencia, 2),
                'percentual_tempo_decorrido' => round($percentualTempoDecorrido, 2),
                'status' => $status,
                'alerta_evasao' => $alerta_evasao,
            ];
        }

        // Passar estudantes ordenados para a view
        $disciplina->setRelation('estudantes', $estudantesOrdenados);

        return view('admin.frequencias.show', compact('disciplina', 'frequencias', 'estatisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $frequencia = Frequencia::with(['estudante', 'disciplina'])->findOrFail(decrypt($id));

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $frequencia->disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para editar esta frequência.');
        }

        return view('admin.frequencias.edit', compact('frequencia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $frequencia = Frequencia::with('disciplina')->findOrFail(decrypt($id));

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $frequencia->disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para editar esta frequência.');
        }

        $request->validate([
            'data_aula' => 'required|date',
            'hora_aula' => 'required|integer|min:0',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        $frequencia->update($request->only(['data_aula', 'hora_aula', 'observacoes']));

        return redirect()->route('admin.frequencia.show', encrypt($frequencia->disciplina_id))
            ->with('success', 'Frequência atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $frequencia = Frequencia::with('disciplina')->findOrFail(decrypt($id));

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $frequencia->disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para excluir esta frequência.');
        }

        $disciplinaId = $frequencia->disciplina_id;
        $frequencia->delete();

        return redirect()->route('admin.frequencia.show', encrypt($disciplinaId))
            ->with('success', 'Frequência removida com sucesso.');
    }

    /**
     * Show frequency history for a specific student.
     */
    public function estudanteHistorico($disciplinaId, $estudanteId)
    {
        $disciplina = Disciplina::with(['professor', 'oferta'])->findOrFail(decrypt($disciplinaId));
        $estudante = Estudante::findOrFail(decrypt($estudanteId));

        // Verificar se o usuário é o professor da disciplina
        if (Auth::user()->hasRole('professor') && $disciplina->professor_id != Auth::id()) {
            abort(403, 'Você não tem permissão para visualizar esta disciplina.');
        }

        // Verificar se o estudante está matriculado na disciplina
        if (!$disciplina->estudantes->contains($estudante->id)) {
            abort(404, 'Estudante não encontrado nesta disciplina.');
        }

        // Buscar todas as frequências do estudante nesta disciplina
        $frequencias = Frequencia::where('disciplina_id', $disciplina->id)
            ->where('estudante_id', $estudante->id)
            ->orderBy('data_aula', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->get();

        // Calcular estatísticas
        $frequenciaTotal = $estudante->getFrequenciaTotal($disciplina->id);
        $percentualFrequencia = $disciplina->carga_horaria_total > 0
            ? ($frequenciaTotal / $disciplina->carga_horaria_total) * 100
            : 0;

        $percentualTempoDecorrido = $disciplina->getPercentualTempoDecorrido();

        // Só mostrar alertas se já passou mais de 50% do tempo da disciplina
        $status = 'ok';
        $alerta_evasao = false;

        if ($percentualTempoDecorrido > 50) {
            // Se já passou mais de 50% do tempo, verificar frequência
            if ($percentualFrequencia >= 75) {
                $status = 'ok';
                $alerta_evasao = false;
            } elseif ($percentualFrequencia >= 50) {
                $status = 'atencao';
                $alerta_evasao = false;
            } else {
                $status = 'risco';
                $alerta_evasao = true;
            }
        } else {
            // Se ainda está nos primeiros 50% do tempo, sempre OK
            $status = 'ok';
            $alerta_evasao = false;
        }

        $estatisticas = [
            'frequencia_total' => $frequenciaTotal,
            'carga_horaria_total' => $disciplina->carga_horaria_total,
            'percentual' => round($percentualFrequencia, 2),
            'percentual_tempo_decorrido' => round($percentualTempoDecorrido, 2),
            'status' => $status,
            'alerta_evasao' => $alerta_evasao,
            'total_registros' => $frequencias->count(),
        ];

        return view('admin.frequencias.estudante-historico', compact('disciplina', 'estudante', 'frequencias', 'estatisticas'));
    }
}
