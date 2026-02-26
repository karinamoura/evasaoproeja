<x-admin>
    @section('title', 'Estudantes em Risco')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-times me-2"></i>Estudantes em Risco de Evasão
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.relatorio.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.relatorio.estudantes-em-risco') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="oferta_id">Oferta (Opcional)</label>
                            <select name="oferta_id" id="oferta_id" class="form-control">
                                <option value="">Todas as Ofertas</option>
                                @foreach($ofertas as $oferta)
                                    <option value="{{ $oferta->id }}" {{ $ofertaId == $oferta->id ? 'selected' : '' }}>
                                        {{ $oferta->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="percentual_minimo">Percentual Mínimo (%)</label>
                            <input type="number" name="percentual_minimo" id="percentual_minimo"
                                   class="form-control" value="{{ $percentualMinimo }}" min="0" max="100">
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

            @if(count($estudantesRisco) > 0)
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Total de Estudantes em Risco:</strong> {{ count($estudantesRisco) }}
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Estudante</th>
                                <th>CPF</th>
                                <th>Oferta</th>
                                <th>Total Horas</th>
                                <th>Horas Frequentes</th>
                                <th>Percentual</th>
                                <th>Déficit</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($estudantesRisco as $item)
                                <tr class="table-danger">
                                    <td><strong>{{ $item['estudante']->nome }}</strong></td>
                                    <td>{{ $item['estudante']->cpf }}</td>
                                    <td>{{ $item['estudante']->oferta->name ?? 'N/A' }}</td>
                                    <td>{{ $item['total_horas'] }}h</td>
                                    <td>{{ $item['horas_frequentes'] }}h</td>
                                    <td>
                                        <span class="badge badge-danger">{{ $item['percentual'] }}%</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{ $item['deficit'] }}% abaixo do mínimo</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.estudante.show', encrypt($item['estudante']->id)) }}"
                                           class="btn btn-sm btn-info" title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>Nenhum estudante em risco encontrado com os critérios selecionados.
                </div>
            @endif
        </div>
    </div>
</x-admin>
