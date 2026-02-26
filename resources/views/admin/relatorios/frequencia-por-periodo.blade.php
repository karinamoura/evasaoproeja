<x-admin>
    @section('title', 'Frequência por Período')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt me-2"></i>Relatório de Frequência por Período
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.relatorio.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.relatorio.frequencia-por-periodo') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
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

            @if(count($dados) > 0)
                <!-- Gráfico de Frequência por Data -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-line me-2"></i>Evolução da Frequência no Período
                                </h5>
                            </div>
                            <div class="card-body">
                                <canvas id="frequenciaPeriodoChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Total de Registros</th>
                                <th>Total de Horas</th>
                                <th>Estudantes Únicos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dados as $item)
                                <tr>
                                    <td><strong>{{ $item['data']->format('d/m/Y') }}</strong></td>
                                    <td>{{ $item['total_registros'] }}</td>
                                    <td>{{ $item['total_horas'] }}h</td>
                                    <td>{{ count($item['estudantes']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Nenhum registro de frequência encontrado para o período selecionado.
                </div>
            @endif
        </div>
    </div>

    @section('js')
    @if(count($dados) > 0)
    <script>
    $(document).ready(function() {
        var frequenciaCanvas = document.getElementById('frequenciaPeriodoChart');
        if (frequenciaCanvas) {
            var frequenciaCtx = frequenciaCanvas.getContext('2d');
            var datas = {!! json_encode(collect($dados)->map(function($item) { return $item['data']->format('d/m'); })->values()) !!};
            var totalHoras = {!! json_encode(collect($dados)->pluck('total_horas')->values()) !!};
            var totalRegistros = {!! json_encode(collect($dados)->pluck('total_registros')->values()) !!};

            new Chart(frequenciaCtx, {
                type: 'line',
                data: {
                    labels: datas,
                    datasets: [
                        {
                            label: 'Total de Horas',
                            data: totalHoras,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            pointRadius: false,
                            pointColor: '#4bc0c0',
                            pointStrokeColor: 'rgba(75, 192, 192, 1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(75, 192, 192, 1)',
                            fill: true,
                            yAxisID: 'y-axis-0'
                        },
                        {
                            label: 'Total de Registros',
                            data: totalRegistros,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            pointRadius: false,
                            pointColor: '#ff6384',
                            pointStrokeColor: 'rgba(255, 99, 132, 1)',
                            pointHighlightFill: '#fff',
                            pointHighlightStroke: 'rgba(255, 99, 132, 1)',
                            fill: true,
                            yAxisID: 'y-axis-1'
                        }
                    ]
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
                        yAxes: [
                            {
                                id: 'y-axis-0',
                                type: 'linear',
                                position: 'left',
                                beginAtZero: true,
                                gridLines: {
                                    display: true
                                }
                            },
                            {
                                id: 'y-axis-1',
                                type: 'linear',
                                position: 'right',
                                beginAtZero: true,
                                gridLines: {
                                    drawOnChartArea: false
                                }
                            }
                        ]
                    }
                }
            });
        }
    });
    </script>
    @endif
    @endsection
</x-admin>
