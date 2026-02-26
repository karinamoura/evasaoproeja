<x-admin>
    @section('title', 'Termos e Condições')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-contract me-2"></i>Termos e Condições
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.termo-condicao.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <table class="table table-striped table-hover" id="termoCondicaoTable">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Título</th>
                        <th>Conteúdo</th>
                        <th>Questionários</th>
                        <th>Status</th>
                        <th>Criado em</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($termos as $termo)
                        <tr>
                            <td>{{ $termo->id }}</td>
                            <td>
                                <strong>{{ $termo->titulo }}</strong>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit(strip_tags($termo->conteudo), 100) }}</span>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $termo->questionarioOfertas->count() }} questionário(s)</span>
                            </td>
                            <td>
                                @if($termo->ativo)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-secondary">Inativo</span>
                                @endif
                            </td>
                            <td>{{ $termo->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.termo-condicao.show', $termo->id) }}"
                                       class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.termo-condicao.edit', $termo->id) }}"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.termo-condicao.destroy', $termo->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir este termo e condições?')"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                <p class="text-muted">Nenhum termo e condições cadastrado ainda.</p>
                                <a href="{{ route('admin.termo-condicao.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Criar
                                </a>
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
                $('#termoCondicaoTable').DataTable({
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

