<x-admin>
    @section('title', 'Usuários')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-users me-2"></i>Usuários
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.user.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="userTable">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Perfil</th>
                            <th>Criado em</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-info">{{ $roleLabels[$role->name] ?? $role->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Sem perfil</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.user.edit', encrypt($user->id)) }}"
                                           class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.user.destroy', encrypt($user->id)) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
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
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-2"></i>Nenhum usuário cadastrado
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
                $('#userTable').DataTable({
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
