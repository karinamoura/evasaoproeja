<x-admin>
    @section('title', 'Questionários')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-alt me-2"></i>Templates
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.questionario.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="questionarioTable">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Perguntas</th>
                        <th>Status</th>
                        <th>Data Criação</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($questionarios as $questionario)
                        <tr>
                            <td>{{ $questionario->id }}</td>
                            <td>
                                <strong>{{ $questionario->titulo }}</strong>
                            </td>
                            <td>{{ Str::limit($questionario->descricao, 50) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $questionario->perguntas->count() }} perguntas</span>
                            </td>
                            <td>
                                @if($questionario->ativo)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-danger">Inativo</span>
                                @endif
                            </td>
                            <td>{{ $questionario->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.questionario.show', $questionario->id) }}"
                                       class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.questionario.edit', $questionario->id) }}"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.questionario.destroy', $questionario->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir este questionário?')"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.questionario.toggle-status', $questionario->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $questionario->ativo ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $questionario->ativo ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas {{ $questionario->ativo ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>Nenhum template cadastrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @section('js')
        <script>
            $(function() {
                $('#questionarioTable').DataTable({
                    "paging": true,
                    "lengthChange": true,
                    "searching": true,
                    "ordering": true,
                    "order": [],
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
