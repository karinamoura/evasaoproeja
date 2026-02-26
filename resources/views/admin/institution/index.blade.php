<x-admin>
    @section('title','Campis')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-building me-2"></i>Campis
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.campi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="institutionTable">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Criado em</th>
                            <th width="150">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $inst)
                            <tr>
                                <td>
                                    <strong>{{ $inst->name }}</strong>
                                </td>
                                <td>{{ $inst->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.campi.edit', encrypt($inst->id)) }}"
                                           class="btn btn-sm btn-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.campi.destroy', encrypt($inst->id)) }}"
                                              method="POST"
                                              style="display: inline;"
                                              onsubmit="return confirm('Tem certeza que deseja excluir este campus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="fas fa-info-circle me-2"></i>Nenhum campus cadastrado
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
                $('#institutionTable').DataTable({
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
