<x-admin>
    @section('title','Disciplinas')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-book me-2"></i>Disciplinas
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.disciplina.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Nova
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="disciplinaTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Professor</th>
                        <th>Oferta</th>
                        <th>Período</th>
                        <th>Carga Horária (h/aula)</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($disciplinas as $disciplina)
                        <tr>
                            <td>
                                <strong>{{ $disciplina->nome }}</strong>
                            </td>
                            <td>{{ $disciplina->professor->name ?? 'N/A' }}</td>
                            <td>{{ $disciplina->oferta->name ?? 'N/A' }}</td>
                            <td>{{ $disciplina->periodo }}</td>
                            <td>{{ $disciplina->carga_horaria_total }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.disciplina.show', encrypt($disciplina->id)) }}"
                                       class="btn btn-sm btn-info" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.disciplina.edit', encrypt($disciplina->id)) }}"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.disciplina.destroy', encrypt($disciplina->id)) }}"
                                          method="POST"
                                          style="display: inline;"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta disciplina?')">
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
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>Nenhuma disciplina cadastrada
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
                $('#disciplinaTable').DataTable({
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

