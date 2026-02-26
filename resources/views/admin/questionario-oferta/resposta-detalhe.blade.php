<x-admin>
    @section('title', 'Detalhes da Resposta')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye me-2"></i>
                        Detalhes da Resposta #{{ $resposta->id }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.questionario-oferta.respostas', $questionarioOferta->id) }}"
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informações da Resposta -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informações da Resposta</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID da Resposta:</strong></td>
                                    <td>{{ $resposta->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Respondente:</strong></td>
                                    <td>
                                        <span class="badge badge-primary">{{ $resposta->identificador_respondente }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Data da Resposta:</strong></td>
                                    <td>{{ $resposta->data_resposta->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Questionário:</strong></td>
                                    <td>{{ $questionarioOferta->titulo }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Oferta:</strong></td>
                                    <td>{{ $questionarioOferta->oferta->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Instituição:</strong></td>
                                    <td>{{ $questionarioOferta->oferta->institution->name }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Estatísticas</h5>
                            <div class="row">
                                <div class="col-6">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-list"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Perguntas</span>
                                            <span class="info-box-number">{{ $questionarioOferta->perguntas->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Respondidas</span>
                                            <span class="info-box-number">{{ $resposta->respostasIndividuais->count() + ($resposta->identificador_respondente ? 1 : 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tempo</span>
                                            <span class="info-box-number">
                                                @php
                                                    $tempo = $resposta->created_at->diffForHumans($resposta->data_resposta);
                                                @endphp
                                                {{ $tempo }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-box bg-primary">
                                        <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Completude</span>
                                            <span class="info-box-number">
                                                @php
                                                    $totalRespondidas = $resposta->respostasIndividuais->count() + ($resposta->identificador_respondente ? 1 : 0);
                                                    $completude = $questionarioOferta->perguntas->count() > 0
                                                        ? round(($totalRespondidas / $questionarioOferta->perguntas->count()) * 100)
                                                        : 0;
                                                @endphp
                                                {{ $completude }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($questionarioOferta->temCampoCpf())
                        @php $estudante = $resposta->estudante_vinculado; @endphp
                        <hr>
                        <h5><i class="fas fa-link me-2"></i>Cruzamento com Frequência (CPF)</h5>
                        @if($estudante)
                            <div class="card border-primary mb-4">
                                <div class="card-header bg-primary text-white">
                                    <strong>Estudante vinculado pelo CPF</strong>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Nome:</strong>
                                                <a href="{{ route('admin.estudante.show', $estudante->id) }}">{{ $estudante->nome }}</a>
                                            </p>
                                            <p class="mb-1"><strong>CPF:</strong> {{ $estudante->cpf }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            @php
                                                $totalRegistros = $estudante->frequencias()->count();
                                                $totalHoras = $estudante->frequencias()->sum('hora_aula');
                                            @endphp
                                            <p class="mb-1"><strong>Registros de frequência:</strong> {{ $totalRegistros }}</p>
                                            <p class="mb-1"><strong>Total de horas:</strong> {{ $totalHoras }} h</p>
                                        </div>
                                    </div>
                                    @php $frequencias = $estudante->frequencias()->with('disciplina')->orderBy('data_aula', 'desc')->limit(20)->get(); @endphp
                                    @if($frequencias->isNotEmpty())
                                        <h6 class="mt-3">Últimos registros de frequência</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Data</th>
                                                        <th>Disciplina</th>
                                                        <th>Horas</th>
                                                        <th>Observações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($frequencias as $f)
                                                        <tr>
                                                            <td>{{ $f->data_aula->format('d/m/Y') }}</td>
                                                            <td>{{ $f->disciplina->nome ?? '-' }}</td>
                                                            <td>{{ $f->hora_aula }}</td>
                                                            <td>{{ Str::limit($f->observacoes, 30) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @if($estudante->frequencias()->count() > 20)
                                            <small class="text-muted">Exibindo os 20 mais recentes.</small>
                                        @endif
                                    @else
                                        <p class="text-muted mb-0 mt-2">Nenhum registro de frequência para este estudante.</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                O CPF informado na resposta não foi encontrado entre os estudantes desta oferta, ou o valor não é um CPF válido. Não há cruzamento com frequência.
                            </div>
                        @endif
                    @endif

                    <hr>
                    <h5>Respostas às Perguntas</h5>

                    @if($resposta->respostasIndividuais->count() > 0 || $resposta->identificador_respondente)
                        @foreach($questionarioOferta->perguntas as $index => $pergunta)
                            @php
                                $respostaIndividual = $resposta->respostasIndividuais->where('pergunta_oferta_id', $pergunta->id)->first();
                                $isPerguntaIdentificadora = $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id;
                            @endphp

                            <div class="card mb-3 {{ ($respostaIndividual || ($isPerguntaIdentificadora && $resposta->identificador_respondente)) ? 'border-success' : 'border-warning' }}">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <span class="badge badge-secondary mr-2">{{ $index + 1 }}</span>
                                            {{ $pergunta->pergunta }}
                                            @if($isPerguntaIdentificadora)
                                                <span class="badge badge-primary ml-2">Identificadora</span>
                                            @elseif($pergunta->personalizada)
                                                <span class="badge badge-warning ml-2">Personalizada</span>
                                            @else
                                                <span class="badge badge-info ml-2">Base</span>
                                            @endif
                                        </h6>
                                        <div>
                                            @if($pergunta->obrigatoria)
                                                <span class="badge badge-danger">Obrigatória</span>
                                            @else
                                                <span class="badge badge-secondary">Opcional</span>
                                            @endif
                                            <span class="badge badge-info ml-2">{{ ucfirst(str_replace('_', ' ', $pergunta->tipo)) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($respostaIndividual || ($isPerguntaIdentificadora && $resposta->identificador_respondente))
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6>Resposta:</h6>
                                                <div class="resposta-content">
                                                    @if($isPerguntaIdentificadora && $resposta->identificador_respondente)
                                                        <div class="alert alert-primary">
                                                            <i class="fas fa-user me-2"></i>
                                                            <strong>{{ $resposta->identificador_respondente }}</strong>
                                                        </div>
                                                    @else
                                                    @switch($pergunta->tipo)
                                                        @case('texto_simples')
                                                        @case('texto_longo')
                                                            <div class="alert alert-info">
                                                                <i class="fas fa-comment me-2"></i>
                                                                <strong>{{ $respostaIndividual->resposta_texto }}</strong>
                                                            </div>
                                                            @break

                                                        @case('radio')
                                                        @case('select')
                                                            @php
                                                                $opcao = $pergunta->opcoesResposta->where('id', $respostaIndividual->resposta_unica)->first();
                                                            @endphp
                                                            @if($opcao)
                                                                <div class="alert alert-success">
                                                                    <i class="fas fa-check-circle me-2"></i>
                                                                    <strong>{{ $opcao->opcao }}</strong>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-warning">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                                    Opção não encontrada
                                                                </div>
                                                            @endif
                                                            @break

                                                        @case('checkbox')
                                                            @if($respostaIndividual->resposta_multipla && count($respostaIndividual->resposta_multipla) > 0)
                                                                <div class="alert alert-success">
                                                                    <i class="fas fa-check-double me-2"></i>
                                                                    <strong>Opções selecionadas:</strong>
                                                                    <ul class="mb-0 mt-2">
                                                                        @foreach($respostaIndividual->resposta_multipla as $opcaoId)
                                                                            @php
                                                                                $opcao = $pergunta->opcoesResposta->where('id', $opcaoId)->first();
                                                                            @endphp
                                                                            @if($opcao)
                                                                                <li>{{ $opcao->opcao }}</li>
                                                                            @endif
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @else
                                                                <div class="alert alert-warning">
                                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                                    Nenhuma opção selecionada
                                                                </div>
                                                            @endif
                                                            @break
                                                    @endswitch
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <h6>Informações:</h6>
                                                <ul class="list-unstyled">
                                                    <li><strong>Tipo:</strong> {{ $isPerguntaIdentificadora ? 'Identificadora' : ucfirst(str_replace('_', ' ', $pergunta->tipo)) }}</li>
                                                    <li><strong>Status:</strong>
                                                        <span class="badge badge-success">Respondida</span>
                                                    </li>
                                                    <li><strong>Respondida em:</strong>
                                                        {{ $isPerguntaIdentificadora ? $resposta->created_at->format('H:i:s') : $respostaIndividual->created_at->format('H:i:s') }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>Esta pergunta não foi respondida.</strong>
                                            @if($pergunta->obrigatoria || $isPerguntaIdentificadora)
                                                <br><small class="text-danger">Pergunta obrigatória não respondida.</small>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Nenhuma resposta individual encontrada.</strong>
                            <br><small>Esta resposta pode estar incompleta ou com problemas.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @section('css')
        <style>
            .info-box {
                display: flex;
                min-height: 60px;
                background: #fff;
                width: 100%;
                box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                border-radius: 0.25rem;
                margin-bottom: 1rem;
            }

            .info-box-icon {
                border-radius: 0.25rem 0 0 0.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                font-weight: 300;
                text-align: center;
                width: 50px;
                color: #fff;
            }

            .info-box-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                line-height: 1.6;
                flex: 1;
                padding: 0 10px;
            }

            .info-box-text {
                display: block;
                font-size: 0.75rem;
                color: #6c757d;
            }

            .info-box-number {
                display: block;
                font-weight: 700;
                font-size: 1rem;
            }

            .bg-info {
                background-color: #17a2b8 !important;
            }

            .bg-success {
                background-color: #28a745 !important;
            }

            .bg-warning {
                background-color: #ffc107 !important;
            }

            .bg-primary {
                background-color: #007bff !important;
            }

            .border-success {
                border-color: #28a745 !important;
            }

            .border-warning {
                border-color: #ffc107 !important;
            }

            .resposta-content .alert {
                margin-bottom: 0;
            }
        </style>
    @endsection
</x-admin>
