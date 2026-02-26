<x-admin>
    @section('title', 'Histórico de Frequência - ' . $estudante->nome)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history me-2"></i>Histórico de Frequência - {{ $estudante->nome }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.frequencia.show', encrypt($disciplina->id)) }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i> Informações do Estudante</h5>
                        <p class="mb-1"><strong>Nome:</strong> {{ $estudante->nome }}</p>
                        <p class="mb-1"><strong>CPF:</strong> {{ $estudante->cpf }}</p>
                        <p class="mb-1"><strong>Matrícula:</strong> {{ $estudante->matricula ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Email:</strong> {{ $estudante->email ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-{{ $estatisticas['status'] == 'ok' ? 'success' : ($estatisticas['status'] == 'atencao' ? 'warning' : 'danger') }}">
                        <h5><i class="fas fa-chart-line me-2"></i> Estatísticas</h5>
                        <p class="mb-1"><strong>Disciplina:</strong> {{ $disciplina->nome }}</p>
                        <p class="mb-1"><strong>Frequência Total:</strong> {{ $estatisticas['frequencia_total'] }} / {{ $estatisticas['carga_horaria_total'] }} h/aula</p>
                        <p class="mb-1"><strong>Percentual:</strong>
                            <span class="badge badge-{{ $estatisticas['status'] == 'ok' ? 'success' : ($estatisticas['status'] == 'atencao' ? 'warning' : 'danger') }}">
                                {{ $estatisticas['percentual'] }}%
                            </span>
                        </p>
                        <p class="mb-1"><strong>Total de Registros:</strong> {{ $estatisticas['total_registros'] }}</p>
                        @if($disciplina->data_inicio && $disciplina->data_fim)
                            <p class="mb-1"><strong>Data de Início:</strong> {{ $disciplina->data_inicio->format('d/m/Y') }}</p>
                            <p class="mb-1"><strong>Data de Término:</strong> {{ $disciplina->data_fim->format('d/m/Y') }}</p>
                        @endif
                        <p class="mb-0">
                            @if($estatisticas['alerta_evasao'])
                                <span class="badge badge-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Risco de Evasão
                                </span>
                            @elseif($estatisticas['status'] == 'atencao')
                                <span class="badge badge-warning">
                                    <i class="fas fa-exclamation-circle me-1"></i> Atenção
                                </span>
                            @else
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle me-1"></i> Frequência Adequada
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <h5 class="mb-3">Histórico de Registros de Frequência</h5>
            @if($frequencias->count() > 0)
                <table class="table table-striped table-hover" id="historicoTable">
                    <thead>
                        <tr>
                            <th>Data da Aula</th>
                            <th>Horas/Aula</th>
                            <th>Observações</th>
                            <th>Registrado em</th>
                            <th width="100">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($frequencias as $frequencia)
                            <tr>
                                <td>{{ $frequencia->data_aula->format('d/m/Y') }}</td>
                                <td><strong>{{ $frequencia->hora_aula }}</strong> h/aula</td>
                                <td>{{ $frequencia->observacoes ?? '-' }}</td>
                                <td>{{ $frequencia->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.frequencia.edit', encrypt($frequencia->id)) }}"
                                           class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.frequencia.destroy', encrypt($frequencia->id)) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este registro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>Nenhum registro de frequência encontrado para este estudante.
                </div>
            @endif
        </div>
    </div>

    @section('js')
        <script>
            $(function() {
                $('#historicoTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "order": [[0, "desc"]],
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                    }
                });
            });
        </script>
    @endsection
</x-admin>

