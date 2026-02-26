<?php

namespace App\Http\Controllers;

use App\Models\Estudante;
use App\Models\Oferta;
use App\Models\Disciplina;
use App\Models\Frequencia;
use App\Models\QuestionarioOferta;
use App\Models\RespostaQuestionario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    /**
     * Display a listing of available reports.
     */
    public function index()
    {
        return view('admin.relatorios.index');
    }

    /**
     * Relatório de Evasão por Oferta
     */
    public function evasaoPorOferta(Request $request)
    {
        $ofertaId = $request->get('oferta_id');
        $percentualMinimo = $request->get('percentual_minimo', 75); // Padrão 75%

        $ofertas = Oferta::with('institution', 'school')->orderBy('name')->get();

        $dados = [];
        if ($ofertaId) {
            $oferta = Oferta::with('institution', 'school', 'disciplinas')->findOrFail($ofertaId);

            $estudantes = Estudante::where('oferta_id', $ofertaId)
                ->with(['disciplinas', 'frequencias'])
                ->get();

            foreach ($estudantes as $estudante) {
                $totalHorasEsperadas = 0; // Horas que deveriam ter sido frequentadas até agora
                $horasFrequentes = 0;
                $totalHorasDisciplinas = 0; // Para exibição
                $disciplinasEstudante = [];

                foreach ($oferta->disciplinas as $disciplina) {
                    // Calcular percentual de tempo decorrido da disciplina
                    $percentualTempoDecorrido = $disciplina->getPercentualTempoDecorrido();

                    // Se a disciplina ainda não começou, não considerar
                    if ($percentualTempoDecorrido <= 0) {
                        continue;
                    }

                    // Calcular horas esperadas até agora (baseado no tempo decorrido)
                    $horasEsperadasAteAgora = ($disciplina->carga_horaria_total * $percentualTempoDecorrido) / 100;

                    // Calcular horas mínimas esperadas (aplicando o percentual mínimo)
                    $horasMinimasEsperadas = ($horasEsperadasAteAgora * $percentualMinimo) / 100;

                    // Buscar frequências do estudante nesta disciplina
                    $frequencias = $estudante->frequencias()
                        ->where('disciplina_id', $disciplina->id)
                        ->get();

                    $horasDisciplina = $frequencias->sum('hora_aula');

                    $totalHorasEsperadas += $horasMinimasEsperadas;
                    $horasFrequentes += $horasDisciplina;
                    $totalHorasDisciplinas += $disciplina->carga_horaria_total;

                    // Percentual baseado no esperado até agora
                    $percentualDisciplina = $horasMinimasEsperadas > 0
                        ? ($horasDisciplina / $horasMinimasEsperadas) * 100
                        : 0;

                    $disciplinasEstudante[] = [
                        'nome' => $disciplina->nome,
                        'carga_horaria' => $disciplina->carga_horaria_total,
                        'horas_frequentes' => $horasDisciplina,
                        'horas_esperadas' => round($horasMinimasEsperadas, 2),
                        'percentual' => round($percentualDisciplina, 2),
                    ];
                }

                // Se não há disciplinas em andamento, não considerar
                if ($totalHorasEsperadas == 0) {
                    continue;
                }

                // Calcular percentual geral: horas frequentes vs horas mínimas esperadas
                $percentualGeral = $totalHorasEsperadas > 0 ? ($horasFrequentes / $totalHorasEsperadas) * 100 : 0;
                $status = $percentualGeral < 100 ? 'Risco' : 'Normal';

                $dados[] = [
                    'estudante' => $estudante,
                    'total_horas' => $totalHorasDisciplinas,
                    'horas_frequentes' => $horasFrequentes,
                    'horas_esperadas' => round($totalHorasEsperadas, 2),
                    'percentual' => round($percentualGeral, 2),
                    'status' => $status,
                    'disciplinas' => $disciplinasEstudante,
                ];
            }

            // Ordenar por percentual (menor primeiro - maior risco)
            usort($dados, function($a, $b) {
                return $a['percentual'] <=> $b['percentual'];
            });
        }

        return view('admin.relatorios.evasao-por-oferta', compact('ofertas', 'dados', 'ofertaId', 'percentualMinimo'));
    }

    /**
     * Relatório de Frequência por Disciplina
     */
    public function frequenciaPorDisciplina(Request $request)
    {
        $disciplinaId = $request->get('disciplina_id');
        $dataInicio = $request->get('data_inicio');
        $dataFim = $request->get('data_fim');

        $disciplinas = Disciplina::with('oferta', 'professor')->orderBy('nome')->get();

        $dados = [];
        if ($disciplinaId) {
            $disciplina = Disciplina::with('oferta', 'professor', 'estudantes')->findOrFail($disciplinaId);

            $query = Frequencia::where('disciplina_id', $disciplinaId)
                ->with('estudante');

            if ($dataInicio) {
                $query->where('data_aula', '>=', $dataInicio);
            }
            if ($dataFim) {
                $query->where('data_aula', '<=', $dataFim);
            }

            $frequencias = $query->orderBy('data_aula', 'desc')->get();

            // Agrupar por estudante
            $estudantesFrequencia = [];
            foreach ($frequencias as $frequencia) {
                $estudanteId = $frequencia->estudante_id;
                if (!isset($estudantesFrequencia[$estudanteId])) {
                    $estudantesFrequencia[$estudanteId] = [
                        'estudante' => $frequencia->estudante,
                        'total_horas' => 0,
                        'registros' => [],
                    ];
                }
                $estudantesFrequencia[$estudanteId]['total_horas'] += $frequencia->hora_aula;
                $estudantesFrequencia[$estudanteId]['registros'][] = $frequencia;
            }

            // Calcular percentual para cada estudante
            foreach ($estudantesFrequencia as &$item) {
                $item['percentual'] = $disciplina->carga_horaria_total > 0
                    ? round(($item['total_horas'] / $disciplina->carga_horaria_total) * 100, 2)
                    : 0;
            }

            $dados = array_values($estudantesFrequencia);
        }

        return view('admin.relatorios.frequencia-por-disciplina', compact('disciplinas', 'dados', 'disciplinaId', 'dataInicio', 'dataFim'));
    }

    /**
     * Relatório de Estudantes em Risco
     */
    public function estudantesEmRisco(Request $request)
    {
        $percentualMinimo = $request->get('percentual_minimo', 75);
        $ofertaId = $request->get('oferta_id');

        $ofertas = Oferta::orderBy('name')->get();

        $query = Estudante::with(['oferta', 'disciplinas', 'frequencias']);

        if ($ofertaId) {
            $query->where('oferta_id', $ofertaId);
        }

        $estudantes = $query->get();
        $estudantesRisco = [];

        foreach ($estudantes as $estudante) {
            $totalHorasEsperadas = 0; // Horas que deveriam ter sido frequentadas até agora
            $horasFrequentes = 0;
            $totalHorasDisciplinas = 0; // Para exibição

            foreach ($estudante->disciplinas as $disciplina) {
                // Calcular percentual de tempo decorrido da disciplina
                $percentualTempoDecorrido = $disciplina->getPercentualTempoDecorrido();

                // Se a disciplina ainda não começou, não considerar
                if ($percentualTempoDecorrido <= 0) {
                    continue;
                }

                // Calcular horas esperadas até agora (baseado no tempo decorrido)
                $horasEsperadasAteAgora = ($disciplina->carga_horaria_total * $percentualTempoDecorrido) / 100;

                // Calcular horas mínimas esperadas (aplicando o percentual mínimo)
                $horasMinimasEsperadas = ($horasEsperadasAteAgora * $percentualMinimo) / 100;

                // Buscar frequências do estudante nesta disciplina
                $frequencias = $estudante->frequencias()
                    ->where('disciplina_id', $disciplina->id)
                    ->get();

                $horasDisciplina = $frequencias->sum('hora_aula');

                $totalHorasEsperadas += $horasMinimasEsperadas;
                $horasFrequentes += $horasDisciplina;
                $totalHorasDisciplinas += $disciplina->carga_horaria_total;
            }

            // Se não há disciplinas em andamento, não considerar em risco
            if ($totalHorasEsperadas == 0) {
                continue;
            }

            // Calcular percentual: horas frequentes vs horas mínimas esperadas
            $percentual = $totalHorasEsperadas > 0 ? ($horasFrequentes / $totalHorasEsperadas) * 100 : 0;

            // Está em risco se não atingiu o mínimo esperado até agora
            if ($percentual < 100) {
                $estudantesRisco[] = [
                    'estudante' => $estudante,
                    'total_horas' => $totalHorasDisciplinas,
                    'horas_frequentes' => $horasFrequentes,
                    'horas_esperadas' => round($totalHorasEsperadas, 2),
                    'percentual' => round($percentual, 2),
                    'deficit' => round(100 - $percentual, 2),
                ];
            }
        }

        // Ordenar por percentual (menor primeiro)
        usort($estudantesRisco, function($a, $b) {
            return $a['percentual'] <=> $b['percentual'];
        });

        return view('admin.relatorios.estudantes-em-risco', compact('estudantesRisco', 'ofertas', 'ofertaId', 'percentualMinimo'));
    }

    /**
     * Relatório de Frequência por Período
     */
    public function frequenciaPorPeriodo(Request $request)
    {
        $dataInicio = $request->get('data_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $ofertaId = $request->get('oferta_id');

        $ofertas = Oferta::orderBy('name')->get();

        $query = Frequencia::with(['estudante', 'disciplina'])
            ->whereBetween('data_aula', [$dataInicio, $dataFim]);

        if ($ofertaId) {
            $query->whereHas('estudante', function($q) use ($ofertaId) {
                $q->where('oferta_id', $ofertaId);
            });
        }

        $frequencias = $query->orderBy('data_aula')->get();

        // Agrupar por data
        $dadosPorData = [];
        foreach ($frequencias as $frequencia) {
            $data = $frequencia->data_aula->format('Y-m-d');
            if (!isset($dadosPorData[$data])) {
                $dadosPorData[$data] = [
                    'data' => $frequencia->data_aula,
                    'total_registros' => 0,
                    'total_horas' => 0,
                    'estudantes' => [],
                ];
            }
            $dadosPorData[$data]['total_registros']++;
            $dadosPorData[$data]['total_horas'] += $frequencia->hora_aula;

            $estudanteId = $frequencia->estudante_id;
            if (!in_array($estudanteId, $dadosPorData[$data]['estudantes'])) {
                $dadosPorData[$data]['estudantes'][] = $estudanteId;
            }
        }

        ksort($dadosPorData);
        $dados = array_values($dadosPorData);

        return view('admin.relatorios.frequencia-por-periodo', compact('dados', 'ofertas', 'ofertaId', 'dataInicio', 'dataFim'));
    }

    /**
     * Relatório de Questionários Respondidos
     */
    public function questionariosRespondidos(Request $request)
    {
        $questionarioOfertaId = $request->get('questionario_oferta_id');

        $questionariosOferta = QuestionarioOferta::with('oferta', 'questionario')
            ->where('ativo', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $dados = [];
        if ($questionarioOfertaId) {
            $questionarioOferta = QuestionarioOferta::with('oferta', 'questionario')->findOrFail($questionarioOfertaId);

            $respostas = RespostaQuestionario::where('questionario_oferta_id', $questionarioOfertaId)
                ->orderBy('created_at', 'desc')
                ->get();

            $dados = [
                'questionario_oferta' => $questionarioOferta,
                'total_respostas' => $respostas->count(),
                'respostas' => $respostas,
            ];
        }

        return view('admin.relatorios.questionarios-respondidos', compact('questionariosOferta', 'dados', 'questionarioOfertaId'));
    }

    /**
     * Relatório Comparativo entre Ofertas
     */
    public function comparativoOfertas(Request $request)
    {
        $ofertaIds = $request->get('oferta_ids', []);

        $ofertas = Oferta::with('institution', 'school')->orderBy('name')->get();

        $dados = [];
        if (!empty($ofertaIds)) {
            foreach ($ofertaIds as $ofertaId) {
                $oferta = Oferta::with('disciplinas', 'estudantes')->findOrFail($ofertaId);

                $totalEstudantes = $oferta->estudantes->count();
                $totalDisciplinas = $oferta->disciplinas->count();

                $totalHorasOferta = $oferta->disciplinas->sum('carga_horaria_total');

                $totalHorasFrequentes = 0;
                $estudantesComFrequencia = 0;

                foreach ($oferta->estudantes as $estudante) {
                    $horasEstudante = 0;
                    foreach ($oferta->disciplinas as $disciplina) {
                        $horasEstudante += $estudante->frequencias()
                            ->where('disciplina_id', $disciplina->id)
                            ->sum('hora_aula');
                    }

                    if ($horasEstudante > 0) {
                        $estudantesComFrequencia++;
                        $totalHorasFrequentes += $horasEstudante;
                    }
                }

                $percentualMedio = $totalEstudantes > 0 && $totalHorasOferta > 0
                    ? (($totalHorasFrequentes / $totalEstudantes) / $totalHorasOferta) * 100
                    : 0;

                $dados[] = [
                    'oferta' => $oferta,
                    'total_estudantes' => $totalEstudantes,
                    'total_disciplinas' => $totalDisciplinas,
                    'total_horas_oferta' => $totalHorasOferta,
                    'total_horas_frequentes' => $totalHorasFrequentes,
                    'estudantes_com_frequencia' => $estudantesComFrequencia,
                    'percentual_medio' => round($percentualMedio, 2),
                ];
            }
        }

        return view('admin.relatorios.comparativo-ofertas', compact('ofertas', 'dados', 'ofertaIds'));
    }
}
