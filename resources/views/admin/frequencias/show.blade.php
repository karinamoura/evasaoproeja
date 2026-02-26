<x-admin>
    @section('title', 'Frequências - ' . $disciplina->nome)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-check me-2"></i>Frequências - {{ $disciplina->nome }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.frequencia.create', encrypt($disciplina->id)) }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-1"></i>Registrar Frequência
                </a>
                <a href="{{ route('admin.frequencia.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i> Informações da Disciplina</h5>
                        <p class="mb-1"><strong>Oferta:</strong> {{ $disciplina->oferta->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Professor:</strong> {{ $disciplina->professor->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Período:</strong> {{ $disciplina->periodo }}</p>
                        <p class="mb-1"><strong>Carga Horária Total:</strong> {{ $disciplina->carga_horaria_total }} h/aula</p>
                        @if($disciplina->data_inicio && $disciplina->data_fim)
                            <p class="mb-1"><strong>Data de Início:</strong> {{ $disciplina->data_inicio->format('d/m/Y') }}</p>
                            <p class="mb-0"><strong>Data de Término:</strong> {{ $disciplina->data_fim->format('d/m/Y') }}</p>
                        @else
                            <p class="mb-0 text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Datas de início e término não definidas</p>
                        @endif
                    </div>
                </div>
            </div>

            <h5 class="mb-3">Estatísticas por Estudante</h5>
            <table class="table table-striped table-bordered mb-4">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Frequência Total</th>
                        <th>% Frequência</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($disciplina->estudantes as $estudante)
                        @php
                            $stats = $estatisticas[$estudante->id] ?? null;
                        @endphp
                        @if($stats)
                            <tr class="{{ $stats['status'] == 'risco' ? 'table-danger' : ($stats['status'] == 'atencao' ? 'table-warning' : '') }}">
                                <td>
                                    <a href="{{ route('admin.frequencia.estudante-historico', ['disciplinaId' => encrypt($disciplina->id), 'estudanteId' => encrypt($estudante->id)]) }}"
                                       class="text-primary font-weight-bold"
                                       title="Ver histórico de frequência">
                                        <i class="fas fa-history me-1"></i> {{ $estudante->nome }}
                                    </a>
                                </td>
                                <td>{{ $estudante->cpf }}</td>
                                <td>
                                    <strong>{{ $stats['frequencia_total'] }}</strong> / {{ $stats['carga_horaria_total'] }} h/aula
                                </td>
                                <td>
                                    <span class="badge badge-{{ $stats['status'] == 'ok' ? 'success' : ($stats['status'] == 'atencao' ? 'warning' : 'danger') }}">
                                        {{ $stats['percentual'] }}%
                                    </span>
                                </td>
                                <td>
                                    @if($stats['alerta_evasao'])
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Risco de Evasão
                                        </span>
                                    @elseif($stats['status'] == 'atencao')
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-circle me-1"></i> Atenção
                                        </span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle me-1"></i> OK
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <h5 class="mb-3">Últimos Registros de Frequência</h5>
            @if($frequencias->count() > 0)
                <table class="table table-striped table-hover" id="frequenciasTable">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Estudante</th>
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
                                <td>{{ $frequencia->estudante->nome }}</td>
                                <td>{{ $frequencia->hora_aula }} h/aula</td>
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
                    <i class="fas fa-info-circle me-2"></i>Nenhum registro de frequência encontrado.
                </div>
            @endif
        </div>
    </div>

    @section('js')
        <script>
            $(function() {
                $('#frequenciasTable').DataTable({
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

