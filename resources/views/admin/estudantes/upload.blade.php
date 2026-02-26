<x-admin>
    @section('title', 'Importar Estudantes via Planilha')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-upload me-2"></i>Importar Estudantes via Planilha
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.estudante.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        <form action="{{ route('admin.estudante.process-upload') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="uploadForm">
            @csrf
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle me-2"></i>Instruções para Importação</h5>
                    <p><strong>Formato da Planilha:</strong></p>
                    <ul>
                        <li>Formatos aceitos: <strong>CSV, TXT, XLS ou XLSX</strong></li>
                        <li>Primeira linha pode conter cabeçalho (será ignorada)</li>
                        <li>Colunas na ordem: <strong>Nome, CPF, Data Nascimento, Matrícula, Nome da Mãe, CEP, Telefone, Email</strong></li>
                        <li>Campos obrigatórios: <strong>Nome e CPF</strong></li>
                        <li>Data de nascimento: formato DD/MM/YYYY, YYYY-MM-DD ou DD-MM-YYYY</li>
                        <li>Para CSV: use ponto e vírgula (;) ou vírgula (,) como delimitador</li>
                    </ul>
                    <p class="mb-0"><strong>Exemplo:</strong></p>
                    <code>
                        João Silva;123.456.789-00;01/01/2000;2024001;Maria Silva;12345-678;(81) 99999-9999;joao@email.com
                    </code>
                    <div class="mt-3">
                        <a href="{{ asset('exemplos/exemplo_importacao_estudantes.csv') }}" download class="btn btn-primary btn-sm text-decoration-none">
                            <i class="fas fa-download me-2"></i>Baixar Planilha de Exemplo (CSV)
                        </a>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="oferta_id" class="form-label">Oferta *</label>
                            <select name="oferta_id" id="oferta_id" class="form-control @error('oferta_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione a Oferta</option>
                                @foreach ($ofertas as $oferta)
                                    <option {{ old('oferta_id') == $oferta->id ? 'selected' : '' }}
                                        value="{{ $oferta->id }}">{{ $oferta->name }}</option>
                                @endforeach
                            </select>
                            <x-error>oferta_id</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="disciplina_id" class="form-label">Disciplina (Opcional)</label>
                            <select name="disciplina_id" id="disciplina_id" class="form-control @error('disciplina_id') is-invalid @enderror">
                                <option value="">Selecione uma Disciplina (ou deixe em branco para todas)</option>
                            </select>
                            <small class="form-text text-muted">Se não selecionar, os estudantes serão vinculados a todas as disciplinas da oferta</small>
                            <x-error>disciplina_id</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="arquivo" class="form-label">Arquivo (CSV/TXT/XLS/XLSX) *</label>
                            <div class="custom-file">
                                <input type="file" name="arquivo" id="arquivo"
                                    class="custom-file-input @error('arquivo') is-invalid @enderror"
                                    accept=".csv,.txt,.xls,.xlsx" required>
                                <label class="custom-file-label" for="arquivo" id="arquivo-label">
                                    <i class="fas fa-file-upload me-2"></i> Escolher arquivo
                                </label>
                            </div>
                            <small class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>Tamanho máximo: 10MB
                            </small>
                            <x-error>arquivo</x-error>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.estudante.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnImportar">
                            <i class="fas fa-upload me-1"></i>Importar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="modalConfirmacao" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i> Atenção
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Você não selecionou uma disciplina específica.</p>
                    <p><strong>Os estudantes serão vinculados a TODAS as disciplinas da oferta selecionada.</strong></p>
                    <p class="mb-0">Deseja realmente continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarImportacao">
                        <i class="fas fa-check me-1"></i>Sim, continuar
                    </button>
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script>
            $(document).ready(function() {
                // Carregar disciplinas quando a oferta mudar
                $('#oferta_id').on('change', function() {
                    var ofertaId = $(this).val();
                    var disciplinaSelect = $('#disciplina_id');

                    disciplinaSelect.html('<option value="">Carregando...</option>');

                    if (ofertaId) {
                        $.ajax({
                            url: '{{ route("admin.estudante.disciplinas", ":id") }}'.replace(':id', ofertaId),
                            method: 'GET',
                            success: function(data) {
                                disciplinaSelect.html('<option value="">Selecione uma Disciplina (ou deixe em branco para todas)</option>');

                                if (data.length > 0) {
                                    $.each(data, function(index, disciplina) {
                                        disciplinaSelect.append(
                                            $('<option></option>')
                                                .attr('value', disciplina.id)
                                                .text(disciplina.nome + ' - ' + disciplina.periodo)
                                        );
                                    });
                                } else {
                                    disciplinaSelect.append(
                                        $('<option></option>')
                                            .attr('value', '')
                                            .text('Nenhuma disciplina cadastrada para esta oferta')
                                    );
                                }
                            },
                            error: function() {
                                disciplinaSelect.html('<option value="">Erro ao carregar disciplinas</option>');
                            }
                        });
                    } else {
                        disciplinaSelect.html('<option value="">Selecione uma Disciplina (ou deixe em branco para todas)</option>');
                    }
                });

                // Interceptar submit do formulário
                $('#uploadForm').on('submit', function(e) {
                    var disciplinaId = $('#disciplina_id').val();

                    // Se não houver disciplina selecionada, mostrar modal
                    if (!disciplinaId || disciplinaId === '') {
                        e.preventDefault();
                        $('#modalConfirmacao').modal('show');
                    }
                });

                // Confirmar importação
                $('#btnConfirmarImportacao').on('click', function() {
                    $('#modalConfirmacao').modal('hide');
                    $('#uploadForm').off('submit').submit();
                });

                // Se já houver oferta selecionada, carregar disciplinas
                var ofertaId = $('#oferta_id').val();
                if (ofertaId) {
                    $('#oferta_id').trigger('change');
                }

                // Atualizar label do arquivo quando selecionar
                $('#arquivo').on('change', function() {
                    var fileName = $(this).val().split('\\').pop();
                    var label = $('#arquivo-label');
                    if (fileName) {
                        // Truncar nome do arquivo se muito longo
                        if (fileName.length > 40) {
                            fileName = fileName.substring(0, 37) + '...';
                        }
                        label.html('<i class="fas fa-file me-2"></i> ' + fileName);
                        label.css('color', '#28a745');
                    } else {
                        label.html('<i class="fas fa-file-upload me-2"></i>Escolher arquivo');
                        label.css('color', '#495057');
                    }
                });
            });
        </script>
    @endsection
</x-admin>

