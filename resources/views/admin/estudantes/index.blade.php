<x-admin>
    @section('title','Estudantes')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-graduate me-2"></i>Estudantes
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.estudante.upload') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-upload me-1"></i>Importar Planilha
                </a>
                <a href="{{ route('admin.estudante.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="estudanteTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Matrícula</th>
                        <th>Oferta</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($estudantes as $estudante)
                        <tr>
                            <td>
                                <strong>{{ $estudante->nome }}</strong>
                            </td>
                            <td>{{ $estudante->cpf }}</td>
                            <td>{{ $estudante->matricula ?? 'N/A' }}</td>
                            <td>{{ $estudante->oferta->name ?? 'N/A' }}</td>
                            <td>{{ $estudante->email ?? 'N/A' }}</td>
                            <td>{{ $estudante->telefone ?? 'N/A' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.estudante.show', encrypt($estudante->id)) }}"
                                       class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.estudante.edit', encrypt($estudante->id)) }}"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.estudante.destroy', encrypt($estudante->id)) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Tem certeza que deseja excluir este estudante?')">
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
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>Nenhum estudante cadastrado
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
                $('#estudanteTable').DataTable({
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

