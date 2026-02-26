<x-admin>
    @section('title', 'Visualizar Questionário de Oferta')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $questionarioOferta->titulo }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.questionario-oferta.edit', $questionarioOferta->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admin.questionario-oferta.respostas', $questionarioOferta->id) }}" class="btn btn-success">
                            <i class="fas fa-chart-bar"></i> Ver Respostas
                        </a>
                        <a href="{{ route('admin.questionario-oferta.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5>Informações Gerais</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Oferta:</strong></td>
                                    <td>{{ $questionarioOferta->oferta->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Instituição:</strong></td>
                                    <td>{{ $questionarioOferta->oferta->institution->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Questionário Base:</strong></td>
                                    <td>{{ $questionarioOferta->questionario->titulo }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Título Personalizado:</strong></td>
                                    <td>
                                        @if($questionarioOferta->titulo_personalizado)
                                            <span class="text-primary">{{ $questionarioOferta->titulo_personalizado }}</span>
                                        @else
                                            <span class="text-muted">Usando título base</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Descrição:</strong></td>
                                    <td>
                                        @if($questionarioOferta->descricao_personalizada)
                                            {{ $questionarioOferta->descricao_personalizada }}
                                        @else
                                            <span class="text-muted">{{ $questionarioOferta->questionario->descricao ?: 'Sem descrição' }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($questionarioOferta->ativo)
                                            <span class="badge badge-success">Ativo</span>
                                        @else
                                            <span class="badge badge-danger">Inativo</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>URL Pública:</strong></td>
                                    <td>
                                        @if($questionarioOferta->url_publica)
                                            <a href="{{ route('questionario.publico', $questionarioOferta->url_publica) }}"
                                               target="_blank" class="btn btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> {{ $questionarioOferta->url_publica }}
                                            </a>
                                        @else
                                            <span class="text-muted">Não configurado</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Criado em:</strong></td>
                                    <td>{{ $questionarioOferta->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Atualizado em:</strong></td>
                                    <td>{{ $questionarioOferta->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Estatísticas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <h3 class="text-primary">{{ $questionarioOferta->perguntas->count() }}</h3>
                                        <p class="mb-0">Total de Perguntas</p>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h3 class="text-success">{{ $questionarioOferta->perguntas->where('obrigatoria', true)->count() }}</h3>
                                        <p class="mb-0">Perguntas Obrigatórias</p>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h3 class="text-info">{{ $questionarioOferta->perguntas->where('personalizada', true)->count() }}</h3>
                                        <p class="mb-0">Perguntas Personalizadas</p>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h3 class="text-warning">{{ $questionarioOferta->respostas->count() }}</h3>
                                        <p class="mb-0">Total de Respostas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5>Perguntas do Questionário</h5>

                    @if($questionarioOferta->perguntas->count() > 0)
                        @foreach($questionarioOferta->perguntas as $index => $pergunta)
                            <div class="card mb-3 {{ $pergunta->personalizada ? 'border-warning' : 'border-info' }}">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <span class="badge badge-secondary mr-2">{{ $index + 1 }}</span>
                                            {{ $pergunta->pergunta }}
                                            @if($pergunta->personalizada)
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
                                    @if($pergunta->temOpcoes())
                                        <h6>Opções de Resposta:</h6>
                                        <ul class="list-group list-group-flush">
                                            @foreach($pergunta->opcoesResposta as $opcao)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ $opcao->opcao }}
                                                    <span class="badge badge-primary badge-pill">{{ $opcao->ordem }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-info-circle"></i>
                                            Esta pergunta não possui opções de resposta.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Este questionário ainda não possui perguntas.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @section('css')
        <style>
            .card-header h6 {
                margin-bottom: 0;
            }
            .badge {
                font-size: 0.8em;
            }
            .list-group-item {
                border-left: none;
                border-right: none;
            }
            .border-warning {
                border-color: #ffc107 !important;
            }
            .border-info {
                border-color: #17a2b8 !important;
            }
        </style>
    @endsection
</x-admin>
