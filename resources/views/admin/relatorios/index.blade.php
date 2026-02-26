<x-admin>
    @section('title', 'Relatórios')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar me-2"></i>Relatórios do Sistema
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Relatório de Evasão por Oferta -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-primary card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                        Evasão por Oferta
                                    </h5>
                                    <p class="card-text">
                                        Análise detalhada da frequência e evasão dos estudantes por oferta, identificando aqueles em risco.
                                    </p>
                                    <a href="{{ route('admin.relatorio.evasao-por-oferta') }}" class="btn btn-primary">
                                        <i class="fas fa-arrow-right me-1"></i>Acessar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório de Frequência por Disciplina -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-success card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-book text-success me-2"></i>
                                        Frequência por Disciplina
                                    </h5>
                                    <p class="card-text">
                                        Relatório de frequência dos estudantes em uma disciplina específica, com filtros por período.
                                    </p>
                                    <a href="{{ route('admin.relatorio.frequencia-por-disciplina') }}" class="btn btn-success">
                                        <i class="fas fa-arrow-right me-1"></i>Acessar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório de Estudantes em Risco -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-danger card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-user-times text-danger me-2"></i>
                                        Estudantes em Risco
                                    </h5>
                                    <p class="card-text">
                                        Lista de estudantes com frequência abaixo do percentual mínimo configurado.
                                    </p>
                                    <a href="{{ route('admin.relatorio.estudantes-em-risco') }}" class="btn btn-danger">
                                        <i class="fas fa-arrow-right me-1"></i>Acessar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório de Frequência por Período -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-info card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-calendar-alt text-info me-2"></i>
                                        Frequência por Período
                                    </h5>
                                    <p class="card-text">
                                        Análise da frequência registrada em um período específico, agrupada por data.
                                    </p>
                                    <a href="{{ route('admin.relatorio.frequencia-por-periodo') }}" class="btn btn-info">
                                        <i class="fas fa-arrow-right me-1"></i>Acessar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório de Questionários Respondidos -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-warning card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-clipboard-check text-warning me-2"></i>
                                        Questionários Respondidos
                                    </h5>
                                    <p class="card-text">
                                        Relatório de respostas recebidas para os questionários aplicados nas ofertas.
                                    </p>
                                    <a href="{{ route('admin.relatorio.questionarios-respondidos') }}" class="btn btn-warning">
                                        <i class="fas fa-arrow-right me-1"></i>Acessar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório Comparativo entre Ofertas -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card card-secondary card-outline">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-balance-scale text-secondary me-2"></i>
                                        Comparativo entre Ofertas
                                    </h5>
                                    <p class="card-text">
                                        Comparação de indicadores de frequência e desempenho entre diferentes ofertas.
                                    </p>
                                    <a href="{{ route('admin.relatorio.comparativo-ofertas') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-right me-1"></i>Acessar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin>
