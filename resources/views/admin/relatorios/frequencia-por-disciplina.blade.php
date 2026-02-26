<x-admin>
    @section('title', 'Frequência por Disciplina')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-book me-2"></i>Relatório de Frequência por Disciplina
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.relatorio.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.relatorio.frequencia-por-disciplina') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="disciplina_id">Disciplina</label>
                            <select name="disciplina_id" id="disciplina_id" class="form-control">
                                <option value="">Selecione uma Disciplina</option>
                                @foreach($disciplinas as $disciplina)
                                    <option value="{{ $disciplina->id }}" {{ $disciplinaId == $disciplina->id ? 'selected' : '' }}>
                                        {{ $disciplina->nome }} - {{ $disciplina->oferta->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_inicio">Data Início</label>
                            <input type="date" name="data_inicio" id="data_inicio"
                                   class="form-control" value="{{ $dataInicio }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="data_fim">Data Fim</label>
                            <input type="date" name="data_fim" id="data_fim"
                                   class="form-control" value="{{ $dataFim }}">
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

            @if(isset($dados) && count($dados) > 0)
                @php
                    $disciplina = \App\Models\Disciplina::find($disciplinaId);
                @endphp
                <div class="alert alert-info">
                    <strong>Disciplina:</strong> {{ $disciplina->nome ?? '' }} |
                    <strong>Carga Horária Total:</strong> {{ $disciplina->carga_horaria_total ?? 0 }}h |
                    <strong>Total de Estudantes:</strong> {{ count($dados) }}
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Estudante</th>
                                <th>CPF</th>
                                <th>Matrícula</th>
                                <th>Horas Frequentes</th>
                                <th>Carga Horária</th>
                                <th>Percentual</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dados as $item)
                                <tr class="{{ $item['percentual'] < 75 ? 'table-warning' : '' }}">
                                    <td><strong>{{ $item['estudante']->nome }}</strong></td>
                                    <td>{{ $item['estudante']->cpf }}</td>
                                    <td>{{ $item['estudante']->matricula ?? 'N/A' }}</td>
                                    <td>{{ $item['total_horas'] }}h</td>
                                    <td>{{ $disciplina->carga_horaria_total ?? 0 }}h</td>
                                    <td>
                                        <span class="badge badge-{{ $item['percentual'] >= 75 ? 'success' : 'warning' }}">
                                            {{ $item['percentual'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if($item['percentual'] >= 75)
                                            <span class="badge badge-success">Normal</span>
                                        @else
                                            <span class="badge badge-warning">Atenção</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(isset($disciplinaId))
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Nenhum registro de frequência encontrado para os filtros selecionados.
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Selecione uma disciplina para gerar o relatório.
                </div>
            @endif
        </div>
    </div>
</x-admin>
