<div class="row">
    <!-- Seção Principal: Indicadores Operacionais -->
    @if(auth()->user()->can('estudantes.view') || auth()->user()->can('frequencias.view') || auth()->user()->can('relatorios.view'))
        @can('estudantes.view')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalEstudantes ?? 0 }}</h3>
                    <p>Total de Estudantes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <a href="{{ route('admin.estudante.index') }}" class="small-box-footer">Ver Detalhes <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endcan

        @can('frequencias.view')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalFrequencias ?? 0 }}</h3>
                    <p>Registros de Frequência</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <a href="{{ route('admin.frequencia.index') }}" class="small-box-footer">Ver Detalhes <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endcan

        @can('relatorios.view')
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $estudantesRisco ?? 0 }}</h3>
                    <p>Estudantes em Risco</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <a href="{{ route('admin.relatorio.estudantes-em-risco') }}" class="small-box-footer">Ver Relatório <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalRespostas ?? 0 }}</h3>
                    <p>Questionários Respondidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <a href="{{ route('admin.relatorio.questionarios-respondidos') }}" class="small-box-footer">Ver Relatório <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endcan
    @endif

    <!-- Gráficos e Análises -->
    @if(auth()->user()->can('estudantes.view') || auth()->user()->can('frequencias.view') || auth()->user()->can('relatorios.view'))
        <div class="col-12 mt-4">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar me-2"></i> Análises e Gráficos
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Gráfico de Frequência por Mês -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-line me-2"></i> Frequência Registrada por Mês
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="frequenciaPorMesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Status de Estudantes -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-pie me-2"></i> Status de Frequência
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="statusEstudantesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Gráfico de Estudantes por Oferta -->
                        <div class="col-lg-12 mt-3">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-bar me-2"></i> Top 5 Ofertas por Número de Estudantes
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="estudantesPorOfertaChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acesso Rápido aos Relatórios -->
        <div class="col-12 mt-3">
            <div class="card card-primary card-outline">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-chart-line me-2"></i> Acesso Rápido aos Relatórios
                    </h5>
                    <p class="card-text mb-4">
                        Acesse a área completa de relatórios para análises detalhadas de evasão, frequência e desempenho.
                    </p>
                    <a href="{{ route('admin.relatorio.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-arrow-right me-1"></i>Ver Todos os Relatórios
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Seção: Cadastros e Configurações (visível para quem tem ao menos uma permissão) -->
    @if(auth()->user()->can('usuarios.view') || auth()->user()->can('instituicoes.view') || auth()->user()->can('escolas.view') || auth()->user()->can('ofertas.view') || auth()->user()->can('questionarios.view') || auth()->user()->can('questionario-oferta.view'))
        <div class="col-12 mt-4">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-cog me-2"></i>Cadastros e Configurações
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('usuarios.view')
                        <div class="col-lg-3 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-users"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Usuários</span>
                                    <span class="info-box-number">{{ $user }}</span>
                                    <a href="{{ route('admin.user.index') }}" class="small">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('instituicoes.view')
                        <div class="col-lg-3 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-university"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Instituições</span>
                                    <span class="info-box-number">{{ $institution }}</span>
                                    <a href="{{ route('admin.campi.index') }}" class="small">Ver todas <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('escolas.view')
                        <div class="col-lg-3 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary elevation-1">
                                    <i class="fas fa-school"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Escolas</span>
                                    <span class="info-box-number">{{ $school }}</span>
                                    <a href="{{ route('admin.escola.index') }}" class="small">Ver todas <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('ofertas.view')
                        <div class="col-lg-3 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary elevation-1">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Ofertas</span>
                                    <span class="info-box-number">{{ $oferta }}</span>
                                    <a href="{{ route('admin.oferta.index') }}" class="small">Ver todas <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('questionarios.view')
                        <div class="col-lg-3 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1">
                                    <i class="fas fa-clipboard-list"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Questionários</span>
                                    <span class="info-box-number">{{ $questionario }}</span>
                                    <a href="{{ route('admin.questionario.index') }}" class="small">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endcan
                        @can('questionario-oferta.view')
                        <div class="col-lg-3 col-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1">
                                    <i class="fas fa-clipboard-check"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-dark">Questionários Ofertados</span>
                                    <span class="info-box-number">{{ $questionarioOferta }}</span>
                                    <a href="{{ route('admin.questionario-oferta.index') }}" class="small">Ver todos <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(auth()->user()->can('estudantes.view') || auth()->user()->can('frequencias.view') || auth()->user()->can('relatorios.view'))
@section('js')
<script>
$(document).ready(function() {
    // Gráfico de Frequência por Mês
    var frequenciaPorMesCanvas = document.getElementById('frequenciaPorMesChart');
    if (frequenciaPorMesCanvas) {
        var frequenciaPorMesCtx = frequenciaPorMesCanvas.getContext('2d');
        var frequenciaPorMesChart = new Chart(frequenciaPorMesCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($frequenciaPorMes ?? [])->pluck('mes')) !!},
                datasets: [{
                    label: 'Horas de Frequência',
                    data: {!! json_encode(collect($frequenciaPorMes ?? [])->pluck('total')) !!},
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    pointRadius: false,
                    pointColor: '#4bc0c0',
                    pointStrokeColor: 'rgba(75, 192, 192, 1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(75, 192, 192, 1)',
                    fill: true
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
                        ticks: {
                            stepSize: 1
                        },
                        gridLines: {
                            display: true
                        }
                    }]
                }
            }
        });
    }

    // Gráfico de Status de Estudantes (Pizza)
    var statusEstudantesCanvas = document.getElementById('statusEstudantesChart');
    if (statusEstudantesCanvas) {
        var statusEstudantesCtx = statusEstudantesCanvas.getContext('2d');
        var statusEstudantesChart = new Chart(statusEstudantesCtx, {
            type: 'doughnut',
            data: {
                labels: ['Normal', 'Em Risco'],
                datasets: [{
                    data: [
                        {{ $estudantesNormal ?? 0 }},
                        {{ $estudantesRisco ?? 0 }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)'
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

    // Gráfico de Estudantes por Oferta
    var estudantesPorOfertaCanvas = document.getElementById('estudantesPorOfertaChart');
    if (estudantesPorOfertaCanvas) {
        var estudantesPorOfertaCtx = estudantesPorOfertaCanvas.getContext('2d');
        var estudantesPorOfertaChart = new Chart(estudantesPorOfertaCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($estudantesPorOferta ?? [])->pluck('nome')) !!},
                datasets: [{
                    label: 'Número de Estudantes',
                    data: {!! json_encode(collect($estudantesPorOferta ?? [])->pluck('total')) !!},
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 206, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
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
                        ticks: {
                            stepSize: 1
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
@endsection
@endif
