<?php

namespace App\Http\Controllers;

use App\Models\QuestionarioOferta;
use App\Models\RespostaQuestionario;
use App\Models\RespostaIndividual;
use Illuminate\Http\Request;

class RespostaQuestionarioController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $urlPublica)
    {


        $questionarioOferta = QuestionarioOferta::with(['perguntas.opcoesResposta', 'perguntaIdentificadora', 'termoCondicao'])
                                                ->where('url_publica', $urlPublica)
                                                ->where('ativo', true)
                                                ->firstOrFail();

            $request->validate([
                'identificador_respondente' => 'nullable|max:255',
                'respostas' => 'required|array',
                'respostas.*.texto' => 'nullable|max:1000',
                'respostas.*.unica' => 'nullable|max:255',
                'respostas.*.multipla' => 'nullable|array'
            ]);


        // Validar identificador_respondente se for formato número
        if ($request->identificador_respondente && $questionarioOferta->perguntaIdentificadora) {
            $perguntaIdentificadora = $questionarioOferta->perguntaIdentificadora;
            if ($perguntaIdentificadora->tipo === 'texto_simples' && $perguntaIdentificadora->formato_validacao === 'numero') {
                if (!preg_match('/^\d+$/', $request->identificador_respondente)) {
                    return response()->json([
                        'success' => false,
                        'message' => "O identificador deve conter apenas números.",
                        'errors' => [
                            'identificador_respondente' => ["O identificador deve conter apenas números."]
                        ]
                    ], 422);
                }
            }
        }

        // Criar resposta do questionário
        $respostaQuestionario = RespostaQuestionario::create([
            'questionario_oferta_id' => $questionarioOferta->id,
            'identificador_respondente' => $request->identificador_respondente
        ]);

        // Salvar respostas individuais
        foreach ($request->respostas as $perguntaId => $respostaData) {
            $pergunta = $questionarioOferta->perguntas()
                                            ->where('id', $perguntaId)
                                            ->first();

            if (!$pergunta) continue;

            // Validar formato específico se for texto simples
            if ($pergunta->tipo === 'texto_simples' && isset($respostaData['texto']) && !empty($respostaData['texto'])) {
                $formato = $pergunta->formato_validacao ?? 'texto_comum';

                if ($formato === 'numero') {
                    // Validar se é apenas números
                    if (!preg_match('/^\d+$/', $respostaData['texto'])) {
                        return response()->json([
                            'success' => false,
                            'message' => "A pergunta '{$pergunta->pergunta}' deve conter apenas números.",
                            'errors' => [
                                "respostas.{$perguntaId}.texto" => ["A resposta deve conter apenas números."]
                            ]
                        ], 422);
                    }
                }
            }

            $respostaIndividual = [
                'resposta_questionario_id' => $respostaQuestionario->id,
                'pergunta_oferta_id' => $pergunta->id
            ];

            // Determinar tipo de resposta baseado no tipo da pergunta
            if ($pergunta->tipo === 'texto_simples' || $pergunta->tipo === 'texto_longo') {
                $respostaIndividual['resposta_texto'] = $respostaData['texto'] ?? '';
            } elseif ($pergunta->tipo === 'checkbox') {
                $respostaIndividual['resposta_multipla'] = $respostaData['multipla'] ?? [];
            } else {
                $respostaIndividual['resposta_unica'] = $respostaData['unica'] ?? '';
            }

            RespostaIndividual::create($respostaIndividual);
        }

        return response()->json([
            'success' => true,
            'message' => 'Resposta enviada com sucesso!'
        ]);
    }

    /**
     * Display a listing of responses for admin.
     */
    public function index($questionarioOfertaId)
    {
        $questionarioOferta = QuestionarioOferta::with(['perguntas.opcoesResposta', 'oferta', 'perguntaIdentificadora'])
                                                ->findOrFail($questionarioOfertaId);

        $respostas = RespostaQuestionario::with([
            'respostasIndividuais.perguntaOferta',
            'questionarioOferta' => function ($q) {
                $q->with(['oferta', 'perguntaIdentificadora', 'perguntas']);
            }
        ])
            ->where('questionario_oferta_id', $questionarioOfertaId)
            ->orderBy('created_at', 'DESC')
            ->get();

        return view('admin.questionario-oferta.respostas', compact('questionarioOferta', 'respostas'));
    }

    /**
     * Show the specified response.
     */
    public function show($questionarioOfertaId, $respostaId)
    {
        $questionarioOferta = QuestionarioOferta::with(['perguntas.opcoesResposta', 'oferta', 'perguntaIdentificadora', 'perguntas'])
                                                ->findOrFail($questionarioOfertaId);

        $resposta = RespostaQuestionario::with([
            'respostasIndividuais.perguntaOferta',
            'questionarioOferta' => function ($q) {
                $q->with(['oferta', 'perguntaIdentificadora', 'perguntas']);
            }
        ])
            ->where('questionario_oferta_id', $questionarioOfertaId)
            ->findOrFail($respostaId);

        return view('admin.questionario-oferta.resposta-detalhe', compact('questionarioOferta', 'resposta'));
    }

    /**
     * Display the public questionnaire form.
     */
    public function publico($urlPublica)
    {
        $questionarioOferta = QuestionarioOferta::where('url_publica', $urlPublica)
                                                ->where('ativo', true)
                                                ->firstOrFail();

        // Incluir formato_validacao nas perguntas e termoCondicao
        $questionarioOferta->load(['perguntas' => function($query) {
            $query->select('id', 'questionario_oferta_id', 'pergunta', 'tipo', 'obrigatoria', 'ordem', 'personalizada', 'formato_validacao')
                  ->orderBy('ordem', 'asc');
        }, 'perguntas.opcoesResposta', 'oferta.institution', 'termoCondicao']);

        return view('questionario.publico', compact('questionarioOferta'));
    }

    /**
     * Export responses to CSV.
     */
    public function exportCsv($questionarioOfertaId)
    {
        $questionarioOferta = QuestionarioOferta::with(['perguntas.opcoesResposta', 'oferta', 'perguntaIdentificadora', 'perguntas'])
                                                ->findOrFail($questionarioOfertaId);

        $respostas = RespostaQuestionario::with([
            'respostasIndividuais.perguntaOferta.opcoesResposta',
            'questionarioOferta' => function ($q) {
                $q->with(['oferta', 'perguntaIdentificadora', 'perguntas']);
            }
        ])
            ->where('questionario_oferta_id', $questionarioOfertaId)
            ->orderBy('created_at', 'ASC')
            ->get();

        $filename = 'respostas_' . $questionarioOferta->oferta->name . '_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $temCpf = $questionarioOferta->temCampoCpf();

        $callback = function() use ($questionarioOferta, $respostas, $temCpf) {
            $file = fopen('php://output', 'w');

            // Adicionar BOM para UTF-8 (ajuda no Excel)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Ordenar perguntas por ordem
            $perguntas = $questionarioOferta->perguntas->sortBy('ordem')->values();

            // Cabeçalho
            $header = ['Data', 'Identificador'];
            if ($temCpf) {
                $header[] = 'Estudante (nome)';
                $header[] = 'Total registros frequência';
                $header[] = 'Total horas frequência';
            }
            foreach ($perguntas as $pergunta) {
                $header[] = $pergunta->pergunta;
            }
            fputcsv($file, $header, ';');

            // Dados
            foreach ($respostas as $resposta) {
                $row = [
                    $resposta->data_resposta->format('d/m/Y'),
                    $resposta->identificador_respondente ?? ''
                ];
                if ($temCpf) {
                    $estudante = $resposta->estudante_vinculado;
                    $row[] = $estudante ? $estudante->nome : '';
                    $row[] = $estudante ? $estudante->frequencias()->count() : '';
                    $row[] = $estudante ? $estudante->frequencias()->sum('hora_aula') : '';
                }

                // Criar um mapa de respostas por pergunta_id para acesso rápido
                $mapaRespostas = [];
                foreach ($resposta->respostasIndividuais as $respostaIndividual) {
                    $mapaRespostas[$respostaIndividual->pergunta_oferta_id] = $respostaIndividual;
                }

                foreach ($perguntas as $pergunta) {
                    // Buscar resposta usando o mapa
                    $respostaIndividual = $mapaRespostas[$pergunta->id] ?? null;

                    if ($respostaIndividual) {
                        $valorFormatado = '';

                        // Carregar opções da pergunta se necessário
                        if (!$pergunta->relationLoaded('opcoesResposta')) {
                            $pergunta->load('opcoesResposta');
                        }

                        // Formatar resposta baseado no tipo
                        if (!empty($respostaIndividual->resposta_texto)) {
                            // Resposta de texto
                            $valorFormatado = $respostaIndividual->resposta_texto;
                        } elseif (!empty($respostaIndividual->resposta_multipla) && is_array($respostaIndividual->resposta_multipla)) {
                            // Resposta múltipla (checkbox) - buscar textos das opções
                            $opcoesSelecionadas = [];
                            foreach ($respostaIndividual->resposta_multipla as $opcaoId) {
                                $opcao = $pergunta->opcoesResposta->where('id', $opcaoId)->first();
                                if ($opcao) {
                                    $opcoesSelecionadas[] = $opcao->opcao;
                                }
                            }
                            $valorFormatado = !empty($opcoesSelecionadas) ? implode('; ', $opcoesSelecionadas) : '';
                        } elseif (!empty($respostaIndividual->resposta_unica)) {
                            // Resposta única (radio/select) - buscar texto da opção
                            $opcao = $pergunta->opcoesResposta->where('id', $respostaIndividual->resposta_unica)->first();
                            if ($opcao) {
                                $valorFormatado = $opcao->opcao;
                            } else {
                                // Fallback: mostrar ID se não encontrar a opção
                                $valorFormatado = $respostaIndividual->resposta_unica;
                            }
                        }

                        $row[] = $valorFormatado ?: 'Sem resposta';
                    } else {
                        $row[] = 'Sem resposta';
                    }
                }

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
