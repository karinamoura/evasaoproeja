<x-admin>
    @section('title', 'Resultados da Importação de Estudantes')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-import me-2"></i>Resultados da Importação de Estudantes
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.estudante.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Informações da Importação -->
            @if($oferta)
                <div class="alert alert-info mb-4">
                    <h5><i class="fas fa-info-circle me-2"></i>Informações da Importação</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Oferta:</strong> {{ $oferta->name }}</p>
                            <p class="mb-0"><strong>Total Processado:</strong> {{ $resultados['total_processado'] }} linha(s)</p>
                        </div>
                        <div class="col-md-6">
                            @if($disciplina)
                                <p class="mb-1"><strong>Disciplina Selecionada:</strong> {{ $disciplina->nome }}</p>
                                <p class="mb-0"><strong>Professor:</strong> {{ $disciplina->professor->name ?? 'N/A' }}</p>
                            @else
                                <p class="mb-0"><strong>Disciplina:</strong> Todas as disciplinas da oferta</p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt me-1"></i>
                            <strong>Importação concluída.</strong> Ao recarregar esta página, os dados não serão processados novamente.
                        </small>
                    </div>
                </div>
            @endif

            <!-- Cards de Estatísticas -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Importados com Sucesso</span>
                            <span class="info-box-number">{{ count($resultados['sucessos']) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Avisos</span>
                            <span class="info-box-number">{{ count($resultados['warnings'] ?? []) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Não Importados</span>
                            <span class="info-box-number">{{ count($resultados['erros']) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabela de Sucessos -->
            @if(count($resultados['sucessos']) > 0)
                <h5 class="mb-3">
                    <i class="fas fa-check-circle text-success me-2"></i> Estudantes Importados com Sucesso ({{ count($resultados['sucessos']) }})
                </h5>
                <table class="table table-striped table-hover mb-4" id="sucessosTable">
                    <thead>
                        <tr>
                            <th>Linha</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Matrícula</th>
                            <th>Email</th>
                            <th>Disciplina(s)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['sucessos'] as $sucesso)
                            <tr>
                                <td><strong>#{{ $sucesso['linha'] }}</strong></td>
                                <td><strong>{{ $sucesso['nome'] }}</strong></td>
                                <td><code>{{ $sucesso['cpf'] }}</code></td>
                                <td>{{ $sucesso['matricula'] ?? '-' }}</td>
                                <td>{{ $sucesso['email'] ?? '-' }}</td>
                                <td>
                                    @if(isset($sucesso['disciplina_nome']))
                                        <small>{{ $sucesso['disciplina_nome'] }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check me-1"></i> {{ $sucesso['mensagem'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Tabela de Warnings -->
            @if(count($resultados['warnings'] ?? []) > 0)
                <h5 class="mb-3">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i> Avisos ({{ count($resultados['warnings']) }})
                </h5>
                <table class="table table-striped table-hover mb-4" id="warningsTable">
                    <thead>
                        <tr>
                            <th>Linha</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Matrícula</th>
                            <th>Email</th>
                            <th>Disciplina(s)</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['warnings'] as $warning)
                            <tr class="table-warning">
                                <td><strong>#{{ $warning['linha'] }}</strong></td>
                                <td><strong>{{ $warning['nome'] }}</strong></td>
                                <td><code>{{ $warning['cpf'] }}</code></td>
                                <td>{{ $warning['matricula'] ?? '-' }}</td>
                                <td>{{ $warning['email'] ?? '-' }}</td>
                                <td>
                                    @if(isset($warning['disciplina_nome']))
                                        <small>{{ $warning['disciplina_nome'] }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle me-1"></i> {{ $warning['mensagem'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Tabela de Erros -->
            @if(count($resultados['erros']) > 0)
                <h5 class="mb-3">
                    <i class="fas fa-times-circle text-danger me-2"></i> Estudantes NÃO Importados ({{ count($resultados['erros']) }})
                </h5>
                <table class="table table-striped table-hover" id="errosTable">
                    <thead>
                        <tr>
                            <th>Linha</th>
                            <th>Nome</th>
                            <th>CPF</th>
                            <th>Motivo da Falha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resultados['erros'] as $erro)
                            <tr class="table-danger">
                                <td><strong>#{{ $erro['linha'] }}</strong></td>
                                <td>{{ $erro['nome'] ?: '<em class="text-muted">Não informado</em>' }}</td>
                                <td><code>{{ $erro['cpf'] ?: '-' }}</code></td>
                                <td>
                                    <span class="badge badge-danger me-2">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    {{ $erro['mensagem'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <!-- Estado Vazio -->
            @if(count($resultados['sucessos']) == 0 && count($resultados['erros']) == 0)
                <div class="alert alert-warning text-center">
                    <i class="fas fa-inbox fa-2x mb-2"></i>
                    <h5>Nenhum registro foi processado</h5>
                    <p class="mb-0">Verifique se o arquivo contém dados válidos.</p>
                </div>
            @endif

            <!-- Botões de Ação -->
            <div class="row mt-4">
                <div class="col-12 text-right">
                    <a href="{{ route('admin.estudante.upload') }}" class="btn btn-outline-primary">
                        <i class="fas fa-upload me-1"></i>Nova Importação
                    </a>
                    <a href="{{ route('admin.estudante.index') }}" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Concluir
                    </a>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            $(function() {
                @if(count($resultados['sucessos']) > 0)
                $('#sucessosTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "order": [[0, "asc"]],
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 25,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                    }
                });
                @endif

                @if(count($resultados['warnings'] ?? []) > 0)
                $('#warningsTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "order": [[0, "asc"]],
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 25,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                    }
                });
                @endif

                @if(count($resultados['erros']) > 0)
                $('#errosTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "order": [[0, "asc"]],
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "pageLength": 25,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                    }
                });
                @endif
            });
        </script>
    @endsection
</x-admin>
