<x-admin>
    @section('title', 'Cadastrar Termos e Condições')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-contract me-2"></i>Cadastrar Novo Termo e Condições
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.termo-condicao.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.termo-condicao.store') }}" method="POST" id="formTermoCondicao">
            @csrf

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Erro!</strong> Por favor, corrija os seguintes problemas:
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="titulo"
                                   id="titulo"
                                   class="form-control @error('titulo') is-invalid @enderror"
                                   value="{{ old('titulo') }}"
                                   placeholder="Ex: Termos de Participação e Uso de Dados">
                            @error('titulo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="conteudo" class="form-label">Conteúdo <span class="text-danger">*</span></label>
                            <textarea name="conteudo"
                                      id="conteudo"
                                      class="form-control @error('conteudo') is-invalid @enderror"
                                      rows="15"
                                      placeholder="Digite o conteúdo dos termos e condições. Você pode usar HTML para formatação.">{{ old('conteudo') }}</textarea>
                            @error('conteudo')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Você pode usar HTML para formatação (ex: &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;, &lt;li&gt;, etc.)
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox"
                                       name="ativo"
                                       id="ativo"
                                       class="form-check-input"
                                       {{ old('ativo', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">
                                    Termo ativo
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Apenas termos ativos podem ser selecionados nos questionários ofertas.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.termo-condicao.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Salvar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @section('js')
        <script>
            $(function() {
                // Inicializar Summernote no campo de conteúdo
                $('#conteudo').summernote({
                    height: 400,
                    lang: 'pt-BR',
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Digite o conteúdo dos termos e condições...',
                    callbacks: {
                        onInit: function() {
                            // Garantir que o conteúdo seja enviado corretamente
                            var content = $('#conteudo').val();
                            if (content) {
                                $('#conteudo').summernote('code', content);
                            }
                        }
                    }
                });

                // Garantir que o conteúdo do Summernote seja enviado no formulário
                $('#formTermoCondicao').on('submit', function(e) {
                    // Sincronizar o conteúdo do Summernote com o textarea ANTES do envio
                    var content = $('#conteudo').summernote('code');
                    $('#conteudo').val(content || '');

                    // Verificar se o textarea tem conteúdo
                    var textareaValue = $('#conteudo').val();

                    // Verificar se o título está preenchido
                    var tituloValue = $('#titulo').val();

                    // Debug - criar mensagem detalhada
                    var debugInfo = '=== DEBUG SUBMIT ===\n';
                    debugInfo += 'Título: ' + (tituloValue || '(vazio)') + '\n';
                    debugInfo += 'Conteúdo Summernote: ' + (content ? content.substring(0, 50) + '...' : '(vazio)') + '\n';
                    debugInfo += 'Conteúdo Textarea: ' + (textareaValue ? textareaValue.substring(0, 50) + '...' : '(vazio)') + '\n';
                    debugInfo += 'Conteúdo limpo: ' + (textareaValue ? textareaValue.replace(/<[^>]*>/g, '').trim() : '(vazio)');

                    console.log(debugInfo);

                    if (!tituloValue || tituloValue.trim() === '') {
                        e.preventDefault();
                        alert('Por favor, preencha o título.');
                        return false;
                    }

                    // Verificar conteúdo - remover tags vazias para verificar se há texto real
                    var cleanContent = textareaValue.replace(/<[^>]*>/g, '').trim();

                    if (!cleanContent || cleanContent === '') {
                        e.preventDefault();
                        alert('Por favor, preencha o conteúdo dos termos e condições.');
                        return false;
                    }

                    // Se chegou aqui, pode enviar
                    return true;
                });
            });
        </script>
    @endsection
</x-admin>

