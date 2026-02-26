<x-admin>
    @section('title','Permissões')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-key me-2"></i>Permissões
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Nova
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="permissionTable">
                    <thead>
                        <tr>
                            <th>Módulo</th>
                            <th>Permissão</th>
                            <th>Criado em</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $permission)
                            <tr>
                                <td>
                                    <span class="badge badge-secondary">{{ \Database\Seeders\PermissionSeeder::getModuleForPermission($permission->name) }}</span>
                                </td>
                                <td>
                                    <strong>{{ $permission->name }}</strong>
                                </td>
                                <td>{{ $permission->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.permission.edit', encrypt($permission->id)) }}"
                                           class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.permission.destroy', encrypt($permission->id)) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta permissão?')">
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
                                    <i class="fas fa-info-circle me-2"></i>Nenhuma permissão cadastrada
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
                $('#permissionTable').DataTable({
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
