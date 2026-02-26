<x-admin>
    @section('title', 'Relatório de Evasão por Oferta')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-exclamation-triangle me-2"></i>Relatório de Evasão por Oferta
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.relatorio.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.relatorio.evasao-por-oferta') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="oferta_id">Oferta</label>
                            <select name="oferta_id" id="oferta_id" class="form-control">
                                <option value="">Selecione uma Oferta</option>
                                @foreach($ofertas as $oferta)
                                    <option value="{{ $oferta->id }}" {{ $ofertaId == $oferta->id ? 'selected' : '' }}>
                                        {{ $oferta->name }} - {{ $oferta->institution->name ?? '' }}
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

            @if(isset($dados) && count($dados) > 0)
                <div class="alert alert-info">
                    <strong>Total de Estudantes:</strong> {{ count($dados) }} |
                    <strong>Em Risco:</strong> {{ collect($dados)->where('status', 'Risco')->count() }} |
                    <strong>Normal:</strong> {{ collect($dados)->where('status', 'Normal')->count() }}
                </div>

                <!-- Gráficos -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Percentual de Frequência por Estudante
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="percentualFrequenciaChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-pie me-2"></i>Distribuição de Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="statusDistribuicaoChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Estudante</th>
                                <th>CPF</th>
                                <th>Matrícula</th>
                                <th>Total Horas</th>
                                <th>Horas Frequentes</th>
                                <th>Percentual</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dados as $item)
                                <tr class="{{ $item['status'] == 'Risco' ? 'table-danger' : '' }}">
                                    <td><strong>{{ $item['estudante']->nome }}</strong></td>
                                    <td>{{ $item['estudante']->cpf }}</td>
                                    <td>{{ $item['estudante']->matricula ?? 'N/A' }}</td>
                                    <td>{{ $item['total_horas'] }}h</td>
                                    <td>{{ $item['horas_frequentes'] }}h</td>
                                    <td>
                                        <span class="badge badge-{{ $item['percentual'] >= $percentualMinimo ? 'success' : 'danger' }}">
                                            {{ $item['percentual'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $item['status'] == 'Risco' ? 'danger' : 'success' }}">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(isset($ofertaId))
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Nenhum dado encontrado para os filtros selecionados.
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Selecione uma oferta para gerar o relatório.
                </div>
            @endif
        </div>
    </div>

    @section('js')
    @if(isset($dados) && count($dados) > 0)
    <script>
    $(document).ready(function() {
        // Gráfico de Percentual de Frequência
        var percentualCanvas = document.getElementById('percentualFrequenciaChart');
        if (percentualCanvas) {
            var percentualCtx = percentualCanvas.getContext('2d');
            var percentuais = {!! json_encode(collect($dados)->pluck('percentual')->values()) !!};
            var nomes = {!! json_encode(collect($dados)->map(function($item) { return \Illuminate\Support\Str::limit($item['estudante']->nome, 20); })->values()) !!};

            new Chart(percentualCtx, {
                type: 'bar',
                data: {
                    labels: nomes,
                    datasets: [{
                        label: 'Percentual de Frequência (%)',
                        data: percentuais,
                        backgroundColor: percentuais.map(function(p) {
                            return p >= {{ $percentualMinimo }} ? 'rgba(40, 167, 69, 0.8)' : 'rgba(220, 53, 69, 0.8)';
                        }),
                        borderColor: percentuais.map(function(p) {
                            return p >= {{ $percentualMinimo }} ? 'rgba(40, 167, 69, 1)' : 'rgba(220, 53, 69, 1)';
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            },
                            gridLines: {
                                display: true
                            }
                        }]
                    }
                }
            });
        }

        // Gráfico de Distribuição de Status
        var statusCanvas = document.getElementById('statusDistribuicaoChart');
        if (statusCanvas) {
            var statusCtx = statusCanvas.getContext('2d');
            var emRisco = {{ collect($dados)->where('status', 'Risco')->count() }};
            var normal = {{ collect($dados)->where('status', 'Normal')->count() }};

            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Normal', 'Em Risco'],
                    datasets: [{
                        data: [normal, emRisco],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(220, 53, 69, 0.8)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            });
        }
    });
    </script>
    @endif
    @endsection
</x-admin>
