<x-admin>
    @section('title', 'Comparativo entre Ofertas')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-balance-scale me-2"></i>Relatório Comparativo entre Ofertas
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.relatorio.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.relatorio.comparativo-ofertas') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="oferta_ids">Selecione as Ofertas (Múltipla escolha)</label>
                            <select name="oferta_ids[]" id="oferta_ids" class="form-control" multiple size="5">
                                @foreach($ofertas as $oferta)
                                    <option value="{{ $oferta->id }}"
                                        {{ in_array($oferta->id, $ofertaIds ?? []) ? 'selected' : '' }}>
                                        {{ $oferta->name }} - {{ $oferta->institution->name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Mantenha Ctrl pressionado para selecionar múltiplas ofertas</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search me-1"></i>Gerar Relatório
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            @if(count($dados) > 0)
                <!-- Gráfico Comparativo -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-bar me-2"></i>Comparativo de Percentual Médio de Frequência
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="comparativoOfertasChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Oferta</th>
                                <th>Instituição</th>
                                <th>Total Estudantes</th>
                                <th>Total Disciplinas</th>
                                <th>Carga Horária Total</th>
                                <th>Horas Frequentes</th>
                                <th>Estudantes com Frequência</th>
                                <th>Percentual Médio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dados as $item)
                                <tr>
                                    <td><strong>{{ $item['oferta']->name }}</strong></td>
                                    <td>{{ $item['oferta']->institution->name ?? 'N/A' }}</td>
                                    <td>{{ $item['total_estudantes'] }}</td>
                                    <td>{{ $item['total_disciplinas'] }}</td>
                                    <td>{{ $item['total_horas_oferta'] }}h</td>
                                    <td>{{ $item['total_horas_frequentes'] }}h</td>
                                    <td>{{ $item['estudantes_com_frequencia'] }}</td>
                                    <td>
                                        <span class="badge badge-{{ $item['percentual_medio'] >= 75 ? 'success' : ($item['percentual_medio'] >= 50 ? 'warning' : 'danger') }}">
                                            {{ $item['percentual_medio'] }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Selecione pelo menos uma oferta para gerar o relatório comparativo.
                </div>
            @endif
        </div>
    </div>

    @section('js')
    @if(count($dados) > 0)
    <script>
    $(document).ready(function() {
        var comparativoCanvas = document.getElementById('comparativoOfertasChart');
        if (comparativoCanvas) {
            var comparativoCtx = comparativoCanvas.getContext('2d');
            var nomesOfertas = {!! json_encode(collect($dados)->map(function($item) { return \Illuminate\Support\Str::limit($item['oferta']->name, 30); })->values()) !!};
            var percentuais = {!! json_encode(collect($dados)->pluck('percentual_medio')->values()) !!};

            new Chart(comparativoCtx, {
                type: 'bar',
                data: {
                    labels: nomesOfertas,
                    datasets: [{
                        label: 'Percentual Médio (%)',
                        data: percentuais,
                        backgroundColor: percentuais.map(function(p) {
                            if (p >= 75) return 'rgba(40, 167, 69, 0.8)';
                            if (p >= 50) return 'rgba(255, 193, 7, 0.8)';
                            return 'rgba(220, 53, 69, 0.8)';
                        }),
                        borderColor: percentuais.map(function(p) {
                            if (p >= 75) return 'rgba(40, 167, 69, 1)';
                            if (p >= 50) return 'rgba(255, 193, 7, 1)';
                            return 'rgba(220, 53, 69, 1)';
                        }),
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true,
                        position: 'top'
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
    });
    </script>
    @endif
    @endsection
</x-admin>
