<x-admin>
    @section('title', 'Questionários Respondidos')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-check me-2"></i>Relatório de Questionários Respondidos
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.relatorio.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.relatorio.questionarios-respondidos') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="questionario_oferta_id">Questionário da Oferta</label>
                            <select name="questionario_oferta_id" id="questionario_oferta_id" class="form-control">
                                <option value="">Selecione um Questionário</option>
                                @foreach($questionariosOferta as $qo)
                                    <option value="{{ $qo->id }}" {{ $questionarioOfertaId == $qo->id ? 'selected' : '' }}>
                                        {{ $qo->titulo_personalizado ?? $qo->questionario->titulo ?? '' }} - {{ $qo->oferta->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search me-1"></i>Gerar Relatório
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if(isset($dados) && isset($dados['questionario_oferta']))
                <div class="alert alert-info">
                    <strong>Questionário:</strong> {{ $dados['questionario_oferta']->titulo_personalizado ?? $dados['questionario_oferta']->questionario->titulo ?? '' }} |
                    <strong>Oferta:</strong> {{ $dados['questionario_oferta']->oferta->name ?? '' }} |
                    <strong>Total de Respostas:</strong> {{ $dados['total_respostas'] }}
                </div>

                @if($dados['total_respostas'] > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Hora</th>
                                    <th>Identificador</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dados['respostas'] as $resposta)
                                    <tr>
                                        <td>{{ $resposta->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $resposta->identificador_respondente ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.questionario-oferta.resposta-detalhe', [
                                                'questionarioOfertaId' => $dados['questionario_oferta']->id,
                                                'respostaId' => $resposta->id
                                            ]) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Ver Detalhes
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>Nenhuma resposta encontrada para este questionário.
                    </div>
                @endif
            @elseif(isset($questionarioOfertaId))
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Nenhum dado encontrado.
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Selecione um questionário para gerar o relatório.
                </div>
            @endif
        </div>
    </div>
</x-admin>
