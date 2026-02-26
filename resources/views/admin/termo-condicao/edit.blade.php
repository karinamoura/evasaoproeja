<x-admin>
    @section('title', 'Editar Termos e Condições')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-contract me-2"></i>Editar Termo e Condições
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.termo-condicao.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.termo-condicao.update', $termo->id) }}" method="POST" id="formTermoCondicao" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                   value="{{ old('titulo', $termo->titulo) }}"
                                   required
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
                                      required
                                      placeholder="Digite o conteúdo dos termos e condições. Você pode usar HTML para formatação.">{{ old('conteudo', $termo->conteudo) }}</textarea>
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
                                       {{ old('ativo', $termo->ativo) ? 'checked' : '' }}>
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
                            <i class="fas fa-save me-1"></i>Atualizar
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
                            // Garantir que o conteúdo existente seja carregado
                            var content = $('#conteudo').val();
                            if (content) {
                                $('#conteudo').summernote('code', content);
                            }
                        }
                    }
                });

                // Garantir que o conteúdo do Summernote seja enviado no formulário
                $('#formTermoCondicao').on('submit', function(e) {
                    var content = $('#conteudo').summernote('code');
                    // Remover apenas tags vazias e espaços, mas manter conteúdo real
                    var cleanContent = content.replace(/<p><br><\/p>/g, '').replace(/<p>\s*<\/p>/g, '').trim();

                    console.log('Conteúdo do Summernote:', content);
                    console.log('Conteúdo limpo:', cleanContent);

                    if (!cleanContent || cleanContent === '<p></p>' || cleanContent === '<br>') {
                        e.preventDefault();
                        alert('Por favor, preencha o conteúdo dos termos e condições.');
                        return false;
                    }

                    $('#conteudo').val(cleanContent);
                    console.log('Valor do textarea antes do submit:', $('#conteudo').val());
                });
            });
        </script>
    @endsection
</x-admin>

