<x-admin>
    @section('title','Perfis')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-shield me-2"></i>Perfis
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.role.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="roleTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Permissões</th>
                        <th>Criado em</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $role)
                        <tr>
                            <td>
                                <strong>{{ \App\Http\Controllers\RoleController::ROLE_LABELS[$role->name] ?? $role->name }}</strong>
                                @if(isset(\App\Http\Controllers\RoleController::ROLE_LABELS[$role->name]))
                                    <br><small class="text-muted">{{ $role->name }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $role->permissions->count() }} permissões</span>
                            </td>
                            <td>{{ $role->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.role.edit',encrypt($role->id)) }}"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.role.destroy',encrypt($role->id)) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este perfil?')">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>Nenhum perfil cadastrado
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
                $('#roleTable').DataTable({
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
