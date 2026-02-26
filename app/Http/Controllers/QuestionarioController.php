<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use App\Models\Pergunta;
use App\Models\OpcaoResposta;
use App\Models\Secao;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class QuestionarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionarios = Questionario::with('perguntas')->orderBy('id', 'DESC')->get();
        return view('admin.questionario.index', compact('questionarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.questionario.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'descricao' => 'nullable|max:1000',
            'secoes' => 'nullable|array',
            'secoes.*.titulo' => 'required_with:secoes|max:255',
            'perguntas' => 'required|array|min:1',
            'perguntas.*.pergunta' => 'required|max:500',
            'perguntas.*.tipo' => 'required|in:texto_simples,texto_longo,radio,checkbox,select',
            'perguntas.*.obrigatoria' => 'boolean',
            'perguntas.*.secao_id' => 'nullable|integer',
            'perguntas.*.ordem' => 'nullable|integer',
            'perguntas.*.opcoes' => 'required_if:perguntas.*.tipo,radio,checkbox,select|array|min:1',
            'perguntas.*.opcoes.*' => 'required|max:255'
        ]);

        // Criar questionário
        $questionario = Questionario::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'slug' => Questionario::gerarSlug($request->titulo)
        ]);

        // Criar seções
        $mapSecaoIndiceParaId = [];
        if ($request->filled('secoes')) {
            $ordemSecao = 1;
            foreach ($request->secoes as $indice => $secaoData) {
                $secao = Secao::create([
                    'questionario_id' => $questionario->id,
                    'titulo' => $secaoData['titulo'] ?? 'Seção ' . $ordemSecao,
                    'descricao' => $secaoData['descricao'] ?? null,
                    'ordem' => $ordemSecao,
                ]);
                $mapSecaoIndiceParaId[(string)$indice] = $secao->id;
                $ordemSecao++;
            }
        }

        // Criar perguntas e opções respeitando seção e ordem
        foreach ($request->perguntas as $index => $perguntaData) {
            $ordemPergunta = isset($perguntaData['ordem']) ? (int)$perguntaData['ordem'] : ($index + 1);
            $secaoIndice = isset($perguntaData['secao_id']) ? (string)$perguntaData['secao_id'] : null;
            $secaoId = $secaoIndice !== null && isset($mapSecaoIndiceParaId[$secaoIndice]) ? $mapSecaoIndiceParaId[$secaoIndice] : null;

            $pergunta = $questionario->perguntas()->create([
                'pergunta' => $perguntaData['pergunta'],
                'tipo' => $perguntaData['tipo'],
                'obrigatoria' => $perguntaData['obrigatoria'] ?? false,
                'ordem' => $ordemPergunta,
                'formato_validacao' => $perguntaData['formato_validacao'] ?? 'texto_comum',
                'secao_id' => $secaoId,
            ]);

            // Criar opções se necessário
            if (in_array($perguntaData['tipo'], ['radio', 'checkbox', 'select']) && isset($perguntaData['opcoes'])) {
                foreach ($perguntaData['opcoes'] as $opcaoIndex => $opcao) {
                    $pergunta->opcoesResposta()->create([
                        'opcao' => $opcao,
                        'ordem' => $opcaoIndex + 1
                    ]);
                }
            }
        }

        return redirect()->route('admin.questionario.index')
                        ->with('success', 'Questionário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $questionario = Questionario::with(['perguntas.opcoesResposta'])->findOrFail($id);
        return view('admin.questionario.show', compact('questionario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $questionario = Questionario::with(['perguntas.opcoesResposta'])->findOrFail($id);
        return view('admin.questionario.edit', compact('questionario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|max:255',
            'descricao' => 'nullable|max:1000',
            'secoes' => 'nullable|array',
            'secoes.*.titulo' => 'required_with:secoes|max:255',
            'perguntas' => 'required|array|min:1',
            'perguntas.*.pergunta' => 'required|max:500',
            'perguntas.*.tipo' => 'required|in:texto_simples,texto_longo,radio,checkbox,select',
            'perguntas.*.obrigatoria' => 'boolean',
            'perguntas.*.secao_id' => 'nullable|integer',
            'perguntas.*.ordem' => 'nullable|integer',
            'perguntas.*.opcoes' => 'required_if:perguntas.*.tipo,radio,checkbox,select|array|min:1',
            'perguntas.*.opcoes.*' => 'required|max:255'
        ]);

        $questionario = Questionario::findOrFail($id);

        // Atualizar questionário
        $questionario->update([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao
        ]);

        // Remover perguntas e seções existentes
        $questionario->perguntas()->delete();
        $questionario->secoes()->delete();

        // Criar seções
        $mapSecaoIndiceParaId = [];
        if ($request->filled('secoes')) {
            $ordemSecao = 1;
            foreach ($request->secoes as $indice => $secaoData) {
                $secao = Secao::create([
                    'questionario_id' => $questionario->id,
                    'titulo' => $secaoData['titulo'] ?? 'Seção ' . $ordemSecao,
                    'descricao' => $secaoData['descricao'] ?? null,
                    'ordem' => $ordemSecao,
                ]);
                $mapSecaoIndiceParaId[(string)$indice] = $secao->id;
                $ordemSecao++;
            }
        }

        // Criar novas perguntas respeitando seção e ordem
        foreach ($request->perguntas as $index => $perguntaData) {
            $ordemPergunta = isset($perguntaData['ordem']) ? (int)$perguntaData['ordem'] : ($index + 1);
            $secaoIndice = isset($perguntaData['secao_id']) ? (string)$perguntaData['secao_id'] : null;
            $secaoId = $secaoIndice !== null && isset($mapSecaoIndiceParaId[$secaoIndice]) ? $mapSecaoIndiceParaId[$secaoIndice] : null;

            $pergunta = $questionario->perguntas()->create([
                'pergunta' => $perguntaData['pergunta'],
                'tipo' => $perguntaData['tipo'],
                'obrigatoria' => $perguntaData['obrigatoria'] ?? false,
                'ordem' => $ordemPergunta,
                'formato_validacao' => $perguntaData['formato_validacao'] ?? 'texto_comum',
                'secao_id' => $secaoId,
            ]);

            // Criar opções se necessário
            if (in_array($perguntaData['tipo'], ['radio', 'checkbox', 'select']) && isset($perguntaData['opcoes'])) {
                foreach ($perguntaData['opcoes'] as $opcaoIndex => $opcao) {
                    $pergunta->opcoesResposta()->create([
                        'opcao' => $opcao,
                        'ordem' => $opcaoIndex + 1
                    ]);
                }
            }
        }

        return redirect()->route('admin.questionario.index')
                        ->with('success', 'Questionário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $questionario = Questionario::findOrFail($id);
        $questionario->delete();

        return redirect()->route('admin.questionario.index')
                        ->with('success', 'Questionário removido com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus($id)
    {
        $questionario = Questionario::findOrFail($id);
        $questionario->update(['ativo' => !$questionario->ativo]);

        $status = $questionario->ativo ? 'ativado' : 'desativado';
        return redirect()->route('admin.questionario.index')
                        ->with('success', "Questionário {$status} com sucesso!");
    }

        /**
     * Retorna as perguntas de um questionário em formato JSON
     */
    public function getPerguntas($id)
    {
        \Log::info('Método getPerguntas chamado com ID: ' . $id);

        try {
            $questionario = Questionario::with(['perguntas.opcoesResposta'])->findOrFail($id);
            \Log::info('Questionário encontrado: ' . $questionario->titulo);
            \Log::info('Total de perguntas: ' . $questionario->perguntas->count());

            $perguntas = $questionario->perguntas->map(function ($pergunta) {
                return [
                    'id' => $pergunta->id,
                    'pergunta' => $pergunta->pergunta,
                    'tipo' => $pergunta->tipo,
                    'obrigatoria' => $pergunta->obrigatoria,
                    'ordem' => $pergunta->ordem,
                    'secao_id' => $pergunta->secao_id,
                    'formato_validacao' => $pergunta->formato_validacao ?? 'texto_comum',
                    'opcoes' => $pergunta->opcoesResposta->map(function ($opcao) {
                        return [
                            'id' => $opcao->id,
                            'opcao' => $opcao->opcao,
                            'ordem' => $opcao->ordem
                        ];
                    })->toArray()
                ];
            });

            \Log::info('Perguntas processadas com sucesso');

            return response()->json([
                'success' => true,
                'perguntas' => $perguntas
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro no método getPerguntas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retorna as seções de um questionário em formato JSON
     */
    public function getSecoes($id)
    {
        try {
            $questionario = Questionario::with(['secoes'])->findOrFail($id);

            $secoes = $questionario->secoes->map(function ($secao) {
                return [
                    'id' => $secao->id,
                    'titulo' => $secao->titulo,
                    'descricao' => $secao->descricao,
                    'ordem' => $secao->ordem
                ];
            });

            return response()->json([
                'success' => true,
                'secoes' => $secoes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
