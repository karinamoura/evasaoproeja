<x-admin>
    @section('title', 'Questionários de Ofertas')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list me-2"></i>Questionários de Ofertas
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.questionario-oferta.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Adicionar Novo
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover" id="questionarioOfertaTable">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Oferta</th>
                        <th>Questionário Base</th>
                        <th>Título Personalizado</th>
                        <th>Perguntas</th>
                        <th>Status</th>
                        <th>URL Pública</th>
                        <th>Respostas</th>
                        <th width="150">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questionarioOfertas as $questionarioOferta)
                        <tr>
                            <td>{{ $questionarioOferta->id }}</td>
                            <td>
                                <strong>{{ $questionarioOferta->oferta->name }}</strong><br>
                                <small class="text-muted">{{ $questionarioOferta->oferta->institution->name }}</small>
                            </td>
                            <td>{{ $questionarioOferta->questionario->titulo }}</td>
                            <td>
                                @if($questionarioOferta->titulo_personalizado)
                                    <span class="text-primary">{{ $questionarioOferta->titulo_personalizado }}</span>
                                @else
                                    <span class="text-muted">Usando título base</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $questionarioOferta->perguntas->count() }} perguntas</span>
                            </td>
                            <td>
                                @if($questionarioOferta->ativo)
                                    <span class="badge badge-success">Ativo</span>
                                @else
                                    <span class="badge badge-danger">Inativo</span>
                                @endif
                            </td>
                            <td>
                                @if($questionarioOferta->url_publica)
                                    <a href="{{ route('questionario.publico', $questionarioOferta->url_publica) }}"
                                       target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Ver
                                    </a>
                                @else
                                    <span class="text-muted">Não configurado</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-secondary">{{ $questionarioOferta->respostas->count() }} respostas</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.questionario-oferta.show', $questionarioOferta->id) }}"
                                       class="btn btn-sm btn-info" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.questionario-oferta.edit', $questionarioOferta->id) }}"
                                       class="btn btn-sm btn-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.questionario-oferta.respostas', $questionarioOferta->id) }}"
                                       class="btn btn-sm btn-success" title="Ver Respostas">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                    <form action="{{ route('admin.questionario-oferta.destroy', $questionarioOferta->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir este questionário de oferta?')"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.questionario-oferta.toggle-status', $questionarioOferta->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $questionarioOferta->ativo ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $questionarioOferta->ativo ? 'Desativar' : 'Ativar' }}">
                                            <i class="fas {{ $questionarioOferta->ativo ? 'fa-pause' : 'fa-play' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
            </table>
        </div>
    </div>

    @section('js')
        <script>
            $(function() {
                $('#questionarioOfertaTable').DataTable({
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
