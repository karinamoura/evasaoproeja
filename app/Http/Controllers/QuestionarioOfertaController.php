<?php

namespace App\Http\Controllers;

use App\Models\Questionario;
use App\Models\Oferta;
use App\Models\QuestionarioOferta;
use App\Models\PerguntaOferta;
use App\Models\SecaoOferta;
use App\Models\OpcaoRespostaOferta;
use Illuminate\Http\Request;

class QuestionarioOfertaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionarioOfertas = QuestionarioOferta::with(['questionario', 'oferta'])
                                                ->orderBy('id', 'DESC')
                                                ->get();
        return view('admin.questionario-oferta.index', compact('questionarioOfertas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $questionarios = Questionario::where('ativo', true)->get();
        $ofertas = Oferta::all();
        $termos = \App\Models\TermoCondicao::where('ativo', true)->orderBy('titulo')->get();
        return view('admin.questionario-oferta.create', compact('questionarios', 'ofertas', 'termos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'questionario_id' => 'required|exists:questionarios,id',
            'oferta_id' => 'required|exists:ofertas,id',
            'titulo_personalizado' => 'nullable|max:255',
            'descricao_personalizada' => 'nullable|max:1000',
            'termo_condicao_id' => 'nullable|exists:termos_condicoes,id',
            'cor_personalizada' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'pergunta_identificadora' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && $request->questionario_id) {
                        $pergunta = \App\Models\Pergunta::where('id', $value)
                            ->where('questionario_id', $request->questionario_id)
                            ->first();
                        if (!$pergunta) {
                            $fail('A pergunta identificadora selecionada não pertence ao questionário escolhido.');
                        }
                    }
                },
            ],
            'perguntas' => 'array',
            'perguntas.*.personalizada' => 'sometimes|boolean',
            'perguntas.*.pergunta' => 'required_with:perguntas.*.personalizada|max:500',
            'perguntas.*.tipo' => 'required_with:perguntas.*.personalizada|in:texto_simples,texto_longo,radio,checkbox,select',
            'perguntas.*.obrigatoria' => 'boolean',
            'perguntas.*.opcoes' => 'sometimes|array|min:1',
            'perguntas.*.opcoes.*' => 'sometimes|required|max:255'
        ]);

        // Verificar se já existe questionário para esta oferta
        $existing = QuestionarioOferta::where('oferta_id', $request->oferta_id)->first();
        if ($existing) {
            return back()->withErrors(['oferta_id' => 'Esta oferta já possui um questionário associado.']);
        }

        // Criar questionário da oferta
        $questionarioOferta = QuestionarioOferta::create([
            'questionario_id' => $request->questionario_id,
            'oferta_id' => $request->oferta_id,
            'titulo_personalizado' => $request->titulo_personalizado,
            'descricao_personalizada' => $request->descricao_personalizada,
            'termo_condicao_id' => $request->termo_condicao_id,
            'cor_personalizada' => $request->cor_personalizada,
            'url_publica' => QuestionarioOferta::gerarUrlPublica()
        ]);

        // Copiar seções e perguntas do questionário base (eager load perguntas por seção)
        $questionario = Questionario::with(['secoes.perguntas.opcoesResposta', 'perguntas' => function($q){ $q->whereNull('secao_id')->with('opcoesResposta'); }])->find($request->questionario_id);

        // Mapeamento: pergunta base ID => pergunta oferta ID
        $mapPerguntaBaseParaOferta = [];

        // 1) Criar seções da oferta espelhando as seções do base
        $mapSecaoBaseParaOferta = [];
        foreach ($questionario->secoes as $secaoBase) {
            $secaoOferta = SecaoOferta::create([
                'questionario_oferta_id' => $questionarioOferta->id,
                'titulo' => $secaoBase->titulo,
                'descricao' => $secaoBase->descricao,
                'ordem' => $secaoBase->ordem,
            ]);
            $mapSecaoBaseParaOferta[$secaoBase->id] = $secaoOferta->id;
        }

        // 2) Copiar perguntas por seção (garantindo mapeamento correto)
        foreach ($questionario->secoes as $secaoBase) {
            $secaoOfertaId = $mapSecaoBaseParaOferta[$secaoBase->id] ?? null;
            foreach ($secaoBase->perguntas as $pergunta) {
                $perguntaOferta = $questionarioOferta->perguntas()->create([
                    'pergunta' => $pergunta->pergunta,
                    'tipo' => $pergunta->tipo,
                    'obrigatoria' => $pergunta->obrigatoria,
                    'ordem' => $pergunta->ordem,
                    'personalizada' => false,
                    'formato_validacao' => $pergunta->formato_validacao ?? 'texto_comum',
                    'secao_oferta_id' => $secaoOfertaId,
                ]);

                // Mapear pergunta base para pergunta oferta
                $mapPerguntaBaseParaOferta[$pergunta->id] = $perguntaOferta->id;

                // Copiar opções de resposta
                foreach ($pergunta->opcoesResposta as $opcao) {
                    $perguntaOferta->opcoesResposta()->create([
                        'opcao' => $opcao->opcao,
                        'ordem' => $opcao->ordem
                    ]);
                }
            }
        }

        // 3) Copiar perguntas sem seção (se houver)
        $perguntasSemSecaoBase = $questionario->perguntas; // carregadas com whereNull no with acima
        foreach ($perguntasSemSecaoBase as $pergunta) {
            $perguntaOferta = $questionarioOferta->perguntas()->create([
                'pergunta' => $pergunta->pergunta,
                'tipo' => $pergunta->tipo,
                'obrigatoria' => $pergunta->obrigatoria,
                'ordem' => $pergunta->ordem,
                'personalizada' => false,
                'formato_validacao' => $pergunta->formato_validacao ?? 'texto_comum',
                'secao_oferta_id' => null,
            ]);

            // Mapear pergunta base para pergunta oferta
            $mapPerguntaBaseParaOferta[$pergunta->id] = $perguntaOferta->id;

            foreach ($pergunta->opcoesResposta as $opcao) {
                $perguntaOferta->opcoesResposta()->create([
                    'opcao' => $opcao->opcao,
                    'ordem' => $opcao->ordem
                ]);
            }
        }

        // Adicionar perguntas personalizadas se houver
        if ($request->has('perguntas')) {
            foreach ($request->perguntas as $index => $perguntaData) {
                // Pular itens não personalizados (itens de base enviados apenas para controle de seção/ordem/oculta)
                if (empty($perguntaData['personalizada'])) {
                    continue;
                }
                $perguntaOferta = $questionarioOferta->perguntas()->create([
                    'pergunta' => $perguntaData['pergunta'],
                    'tipo' => $perguntaData['tipo'],
                    'obrigatoria' => $perguntaData['obrigatoria'] ?? false,
                    'ordem' => $questionario->perguntas->count() + $index + 1,
                    'personalizada' => true,
                    'formato_validacao' => $perguntaData['formato_validacao'] ?? 'texto_comum'
                ]);

                // Criar opções se necessário
                if (isset($perguntaData['tipo']) && in_array($perguntaData['tipo'], ['radio', 'checkbox', 'select']) && isset($perguntaData['opcoes'])) {
                    foreach ($perguntaData['opcoes'] as $opcaoIndex => $opcao) {
                        $perguntaOferta->opcoesResposta()->create([
                            'opcao' => $opcao,
                            'ordem' => $opcaoIndex + 1
                        ]);
                    }
                }
            }
        }

        // Salvar pergunta identificadora se informada
        if ($request->pergunta_identificadora) {
            // O ID recebido é da pergunta base, usar o mapeamento para obter o ID da pergunta oferta
            $perguntaOfertaId = $mapPerguntaBaseParaOferta[$request->pergunta_identificadora] ?? null;
            if ($perguntaOfertaId) {
            $questionarioOferta->update([
                    'pergunta_identificadora_id' => $perguntaOfertaId
            ]);
            }
        }

        return redirect()->route('admin.questionario-oferta.index')
                        ->with('success', 'Questionário da oferta criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $questionarioOferta = QuestionarioOferta::with(['questionario', 'oferta', 'perguntas.opcoesResposta'])
                                                ->findOrFail($id);
        return view('admin.questionario-oferta.show', compact('questionarioOferta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $questionarioOferta = QuestionarioOferta::with(['questionario', 'oferta', 'perguntas.opcoesResposta', 'secoes.perguntas.opcoesResposta', 'termoCondicao'])
                                                ->findOrFail($id);

        // Organizar perguntas por seção
        $perguntas = collect();
        $perguntasSemSecao = collect();

        foreach ($questionarioOferta->perguntas as $pergunta) {
            if ($pergunta->secao_oferta_id) {
                // Pergunta está em uma seção
                $perguntas->push($pergunta);
            } else {
                // Pergunta sem seção
                $perguntasSemSecao->push($pergunta);
            }
        }

        $termos = \App\Models\TermoCondicao::where('ativo', true)->orderBy('titulo')->get();
        return view('admin.questionario-oferta.edit', compact('questionarioOferta', 'perguntas', 'perguntasSemSecao', 'termos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
        $request->validate([
            'titulo_personalizado' => 'nullable|max:255',
            'descricao_personalizada' => 'nullable|max:1000',
            'termo_condicao_id' => 'nullable|exists:termos_condicoes,id',
            'cor_personalizada' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'pergunta_identificadora' => 'nullable|exists:pergunta_oferta,id',
            'secoes' => 'nullable|array',
            'secoes.*.titulo' => 'required_with:secoes|max:255',
            'secoes.*.descricao' => 'nullable|max:1000',
            'perguntas' => 'nullable|array',
            'perguntas.*.personalizada' => 'sometimes|boolean',
            'perguntas.*.pergunta' => 'required_with:perguntas.*.personalizada|max:500',
            'perguntas.*.tipo' => 'required_with:perguntas.*.personalizada|in:texto_simples,texto_longo,radio,checkbox,select',
            'perguntas.*.obrigatoria' => 'boolean',
            'perguntas.*.opcoes' => 'sometimes|array|min:1',
            'perguntas.*.opcoes.*' => 'sometimes|required|max:255'
        ]);

        $questionarioOferta = QuestionarioOferta::findOrFail($id);

        // Atualizar questionário da oferta
        $questionarioOferta->update([
            'titulo_personalizado' => $request->titulo_personalizado,
            'descricao_personalizada' => $request->descricao_personalizada,
            'termo_condicao_id' => $request->termo_condicao_id,
            'cor_personalizada' => $request->cor_personalizada,
            'pergunta_identificadora_id' => $request->pergunta_identificadora
        ]);

        // Obter todas as perguntas personalizadas existentes
        $perguntasPersonalizadasExistentes = $questionarioOferta->perguntas()
            ->where('personalizada', true)
            ->get()
            ->keyBy('id');

        // Processar seções PRIMEIRO para criar mapeamento de índices temporários para IDs reais
        $mapSecaoIndiceParaId = [];
        if ($request->has('secoes') && !empty($request->secoes)) {
            // Obter seções existentes
            $secoesExistentes = $questionarioOferta->secoes()->get()->keyBy('id');

            foreach ($request->secoes as $index => $secaoData) {
                if (!empty($secaoData['titulo'])) {
                    // Usar o índice do array ou o índice fornecido no campo hidden
                    $indiceSecao = isset($secaoData['indice']) ? (int)$secaoData['indice'] : ($index + 1);

                    if (isset($secaoData['id']) && $secoesExistentes->has($secaoData['id'])) {
                        // Atualizar seção existente
                        $secaoExistente = $secoesExistentes->get($secaoData['id']);
                        $secaoExistente->update([
                            'titulo' => $secaoData['titulo'],
                            'descricao' => $secaoData['descricao'] ?? null,
                            'ordem' => $index + 1
                        ]);
                        // Mapear índice para ID existente
                        $mapSecaoIndiceParaId[$indiceSecao] = $secaoExistente->id;
                        $secoesExistentes->forget($secaoData['id']); // Marcar como processada
                    } else {
                        // Criar nova seção
                        $novaSecao = $questionarioOferta->secoes()->create([
                            'titulo' => $secaoData['titulo'],
                            'descricao' => $secaoData['descricao'] ?? null,
                            'ordem' => $index + 1
                        ]);
                        // Mapear índice temporário para ID recém-criado
                        $mapSecaoIndiceParaId[$indiceSecao] = $novaSecao->id;
                    }
                }
            }

            // Remover seções que não foram processadas (foram deletadas no frontend)
            foreach ($secoesExistentes as $secaoParaRemover) {
                $secaoParaRemover->delete();
            }
        }

        // Processar perguntas personalizadas DEPOIS de processar as seções
        $perguntasProcessadas = [];
        if ($request->has('perguntas') && !empty($request->perguntas)) {
            \Illuminate\Support\Facades\Log::info('Perguntas recebidas:', $request->perguntas);
            $ordemAtual = $questionarioOferta->perguntas()->max('ordem') ?? 0;

            foreach ($request->perguntas as $index => $perguntaData) {
                // Pular itens não personalizados
                if (empty($perguntaData['personalizada'])) {
                    continue;
                }

                // Normalizar perguntaData para garantir que seja um array
                if (!is_array($perguntaData)) {
                    continue;
                }

                // Verificar se a pergunta está associada a uma seção
                $secaoOfertaId = null;
                if (isset($perguntaData['secao_id']) && !empty($perguntaData['secao_id'])) {
                    // Verificar se é um ID numérico (seção existente) ou um índice temporário (new-X)
                    if (is_numeric($perguntaData['secao_id'])) {
                        // Seção existente - verificar se existe
                        $secaoOferta = $questionarioOferta->secoes()->find($perguntaData['secao_id']);
                        if ($secaoOferta) {
                            $secaoOfertaId = $secaoOferta->id;
                        }
                    } elseif (strpos($perguntaData['secao_id'], 'new-') === 0) {
                        // Seção nova - extrair o índice e usar o mapeamento
                        $indiceSecao = (int) str_replace('new-', '', $perguntaData['secao_id']);
                        if (isset($mapSecaoIndiceParaId[$indiceSecao])) {
                            $secaoOfertaId = $mapSecaoIndiceParaId[$indiceSecao];
                        }
                    }
                }

                // Verificar se é uma pergunta existente ou nova
                if (isset($perguntaData['id']) && $perguntasPersonalizadasExistentes->has($perguntaData['id'])) {
                    // Atualizar pergunta existente
                    $perguntaExistente = $perguntasPersonalizadasExistentes->get($perguntaData['id']);
                    $perguntaExistente->update([
                        'pergunta' => $perguntaData['pergunta'],
                        'tipo' => $perguntaData['tipo'],
                        'obrigatoria' => $perguntaData['obrigatoria'] ?? false,
                        'formato_validacao' => $perguntaData['formato_validacao'] ?? 'texto_comum',
                        'secao_oferta_id' => $secaoOfertaId
                    ]);

                    // Atualizar opções de resposta
                    if (isset($perguntaData['tipo']) && in_array($perguntaData['tipo'], ['radio', 'checkbox', 'select'])) {
                        // Remover opções existentes
                        $perguntaExistente->opcoesResposta()->delete();

                        // Criar novas opções se fornecidas
                        if (isset($perguntaData['opcoes']) && !empty($perguntaData['opcoes'])) {
                            foreach ($perguntaData['opcoes'] as $opcaoIndex => $opcao) {
                                $perguntaExistente->opcoesResposta()->create([
                                    'opcao' => $opcao,
                                    'ordem' => $opcaoIndex + 1
                                ]);
                            }
                        }
                    }

                    $perguntasProcessadas[] = $perguntaData['id'];
                } else {
                    // Criar nova pergunta
                    $ordemAtual++;
                $perguntaOferta = $questionarioOferta->perguntas()->create([
                    'pergunta' => $perguntaData['pergunta'],
                    'tipo' => $perguntaData['tipo'],
                    'obrigatoria' => $perguntaData['obrigatoria'] ?? false,
                        'ordem' => $ordemAtual,
                    'personalizada' => true,
                        'formato_validacao' => $perguntaData['formato_validacao'] ?? 'texto_comum',
                        'secao_oferta_id' => $secaoOfertaId
                ]);

                // Criar opções se necessário
                if (isset($perguntaData['tipo']) && in_array($perguntaData['tipo'], ['radio', 'checkbox', 'select']) && isset($perguntaData['opcoes'])) {
                    foreach ($perguntaData['opcoes'] as $opcaoIndex => $opcao) {
                        $perguntaOferta->opcoesResposta()->create([
                            'opcao' => $opcao,
                            'ordem' => $opcaoIndex + 1
                        ]);
                    }
                }

                    if (isset($perguntaData['id'])) {
                        $perguntasProcessadas[] = $perguntaData['id'];
                    }
                }
            }
        }

        // Remover perguntas personalizadas que não foram processadas (foram deletadas no frontend)
        foreach ($perguntasPersonalizadasExistentes as $perguntaId => $pergunta) {
            if (!in_array($perguntaId, $perguntasProcessadas)) {
                $pergunta->delete();
            }
        }

        return redirect()->route('admin.questionario-oferta.index')
                        ->with('success', 'Questionário da oferta atualizado com sucesso!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Erro ao atualizar questionário oferta: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erro ao atualizar questionário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $questionarioOferta = QuestionarioOferta::findOrFail($id);
        $questionarioOferta->delete();

        return redirect()->route('admin.questionario-oferta.index')
                        ->with('success', 'Questionário da oferta removido com sucesso!');
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus($id)
    {
        $questionarioOferta = QuestionarioOferta::findOrFail($id);
        $questionarioOferta->update(['ativo' => !$questionarioOferta->ativo]);

        $status = $questionarioOferta->ativo ? 'ativado' : 'desativado';
        return redirect()->route('admin.questionario-oferta.index')
                        ->with('success', "Questionário da oferta {$status} com sucesso!");
    }

    /**
     * Exibir questionário público
     */
    public function publico($urlPublica)
    {
        $questionarioOferta = QuestionarioOferta::with(['perguntas.opcoesResposta'])
                                                ->where('url_publica', $urlPublica)
                                                ->where('ativo', true)
                                                ->firstOrFail();

        return view('questionario.publico', compact('questionarioOferta'));
    }
}
