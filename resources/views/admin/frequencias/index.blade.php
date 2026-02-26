<x-admin>
    @section('title','Registro de Frequência')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-check me-2"></i>Disciplinas para Registro de Frequência
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="frequenciaTable">
                <thead>
                    <tr>
                        <th>Disciplina</th>
                        <th>Professor</th>
                        <th>Oferta</th>
                        <th>Período</th>
                        <th>Carga Horária</th>
                        <th>Estudantes</th>
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
                            <td>{{ $disciplina->carga_horaria_total }} h/aula</td>
                            <td>{{ $disciplina->estudantes->count() }} estudante(s)</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.frequencia.show', encrypt($disciplina->id)) }}"
                                       class="btn btn-sm btn-info" title="Ver Frequências">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.frequencia.create', encrypt($disciplina->id)) }}"
                                       class="btn btn-sm btn-primary" title="Registrar Frequência">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle me-2"></i>Nenhuma disciplina disponível
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
                $('#frequenciaTable').DataTable({
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

