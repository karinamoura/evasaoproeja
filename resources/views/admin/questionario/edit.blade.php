<x-admin>
    @section('title', 'Editar Template')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-alt me-2"></i>Editar Template: {{ $questionario->titulo }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.questionario.index') }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('admin.questionario.update', $questionario->id) }}" method="POST" id="questionarioForm" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="titulo" class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="titulo"
                                   id="titulo"
                                   class="form-control @error('titulo') is-invalid @enderror"
                                   value="{{ old('titulo', $questionario->titulo) }}"
                                   required
                                   placeholder="Digite o título do questionário">
                            <x-error>titulo</x-error>
                            <div class="invalid-feedback">O título é obrigatório.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ativo" class="form-label">Status</label>
                            <select name="ativo"
                                    id="ativo"
                                    class="form-control @error('ativo') is-invalid @enderror">
                                <option value="1" {{ old('ativo', $questionario->ativo) == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ old('ativo', $questionario->ativo) == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <x-error>ativo</x-error>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição</label>
                    <textarea name="descricao"
                              id="descricao"
                              class="form-control @error('descricao') is-invalid @enderror"
                              rows="3"
                              placeholder="Digite uma descrição para o questionário (opcional)">{{ old('descricao', $questionario->descricao) }}</textarea>
                    <x-error>descricao</x-error>
                </div>

                <hr>
                <h5 class="text-center">
                    <i class="fas fa-layer-group me-2"></i>Seções e Perguntas do Questionário
                </h5>

                <div class="mb-3">
                    <button type="button" id="btn-add-secao" class="btn btn-success">
                        <i class="fas fa-layer-group me-1"></i>Adicionar Seção
                    </button>
                </div>

                <div id="secoes-container">
                    @php
                        $secoes = $questionario->secoes()->with('perguntas.opcoesResposta')->get();
                        $perguntasSemSecao = $questionario->perguntas()->whereNull('secao_id')->with('opcoesResposta')->get();
                        $secaoIndex = 0;
                    @endphp

                    @foreach($secoes as $secao)
                        @php $secaoIndex++; @endphp
                        <div class="card mb-3 secao-item" id="secao-{{ $secaoIndex }}" data-secao="{{ $secaoIndex }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                    <i class="fas fa-grip-vertical text-muted"></i>
                                    <input type="text" name="secoes[{{ $secaoIndex }}][titulo]" class="form-control form-control-sm" value="{{ $secao->titulo }}" style="max-width: 320px;" required>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-trash remove-secao" onclick="removerSecao({{ $secaoIndex }})" title="Remover Seção"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea name="secoes[{{ $secaoIndex }}][descricao]" class="form-control form-control-sm" rows="2" placeholder="Descrição da seção (opcional)">{{ $secao->descricao }}</textarea>
                                </div>
                                <div class="lista-perguntas" id="lista-perguntas-{{ $secaoIndex }}">
                                    @foreach($secao->perguntas as $indexPergunta => $pergunta)
                                        @php $pid = $loop->parent->index + 1 . '-' . ($indexPergunta + 1); @endphp
                                        <div class="pergunta-item" id="pergunta-{{ $pid }}" data-pergunta="{{ $pid }}">
                                            <div class="pergunta-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center" style="gap: 10px;">
                                                    <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                                    <h6 class="mb-0">Pergunta</h6>
                                                </div>
                                                <div>
                                                    <i class="fas fa-trash remove-pergunta" onclick="removerPergunta('{{ $pid }}')" title="Remover"></i>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Pergunta *</label>
                                                        <input type="text" name="perguntas[{{ $pid }}][pergunta]" class="form-control" value="{{ $pergunta->pergunta }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tipo *</label>
                                                        <select name="perguntas[{{ $pid }}][tipo]" class="form-control tipo-pergunta" onchange="toggleOpcoes('{{ $pid }}')" required>
                                                            <option value="">Selecione...</option>
                                                            <option value="texto_simples" {{ $pergunta->tipo == 'texto_simples' ? 'selected' : '' }}>Texto Simples</option>
                                                            <option value="texto_longo" {{ $pergunta->tipo == 'texto_longo' ? 'selected' : '' }}>Texto Longo</option>
                                                            <option value="radio" {{ $pergunta->tipo == 'radio' ? 'selected' : '' }}>Única Escolha (Radio)</option>
                                                            <option value="checkbox" {{ $pergunta->tipo == 'checkbox' ? 'selected' : '' }}>Múltipla Escolha (Checkbox)</option>
                                                            <option value="select" {{ $pergunta->tipo == 'select' ? 'selected' : '' }}>Seleção (Select)</option>
                                                        </select>
                                                        <input type="hidden" name="perguntas[{{ $pid }}][secao_id]" value="{{ $secaoIndex }}">
                                                        <input type="hidden" name="perguntas[{{ $pid }}][ordem]" class="input-ordem" value="{{ $pergunta->ordem }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="checkbox" name="perguntas[{{ $pid }}][obrigatoria]" value="1" {{ $pergunta->obrigatoria ? 'checked' : '' }}>
                                                            Pergunta obrigatória
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group formato-validacao-container" id="formato-{{ $pid }}" style="display: {{ $pergunta->tipo == 'texto_simples' ? 'block' : 'none' }};">
                                                        <label>Formato de Validação</label>
                                                        <select name="perguntas[{{ $pid }}][formato_validacao]" class="form-control">
                                                            <option value="texto_comum" {{ $pergunta->formato_validacao == 'texto_comum' ? 'selected' : '' }}>Texto Comum</option>
                                                            <option value="data" {{ $pergunta->formato_validacao == 'data' ? 'selected' : '' }}>Data</option>
                                                            <option value="cpf" {{ $pergunta->formato_validacao == 'cpf' ? 'selected' : '' }}>CPF</option>
                                                            <option value="telefone" {{ $pergunta->formato_validacao == 'telefone' ? 'selected' : '' }}>Telefone</option>
                                                            <option value="email" {{ $pergunta->formato_validacao == 'email' ? 'selected' : '' }}>E-mail</option>
                                                            <option value="numero" {{ $pergunta->formato_validacao == 'numero' ? 'selected' : '' }}>Número</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="opcoes-container" id="opcoes-{{ $pid }}" style="display: {{ in_array($pergunta->tipo, ['radio', 'checkbox', 'select']) ? 'block' : 'none' }};">
                                                <label>Opções de Resposta *</label>
                                                <div id="opcoes-lista-{{ $pid }}">
                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                        <div class="opcao-item">
                                                            <input type="text" name="perguntas[{{ $pid }}][opcoes][]" class="form-control" style="width: 80%;" value="{{ $opcao->opcao }}" placeholder="Digite a opção" required>
                                                            <i class="fas fa-times remove-opcao" onclick="this.parentElement.remove()"></i>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-info" onclick="adicionarOpcao('{{ $pid }}')">
                                                    <i class="fas fa-plus"></i> Adicionar Opção
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-start mt-3">
                                    <button type="button" class="btn btn-success" onclick="adicionarPergunta({{ $secaoIndex }})">
                                        <i class="fas fa-plus"></i> Adicionar Pergunta
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($perguntasSemSecao->count() > 0)
                        @php $secaoIndex++; @endphp
                        <div class="card mb-3 secao-item" id="secao-{{ $secaoIndex }}" data-secao="{{ $secaoIndex }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                    <i class="fas fa-grip-vertical text-muted"></i>
                                    <input type="text" name="secoes[{{ $secaoIndex }}][titulo]" class="form-control form-control-sm" value="Sem Seção" style="max-width: 320px;" required>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-trash remove-secao" onclick="removerSecao({{ $secaoIndex }})" title="Remover Seção"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea name="secoes[{{ $secaoIndex }}][descricao]" class="form-control form-control-sm" rows="2" placeholder="Descrição da seção (opcional)"></textarea>
                                </div>
                                <div class="lista-perguntas" id="lista-perguntas-{{ $secaoIndex }}">
                                    @foreach($perguntasSemSecao as $indexPergunta => $pergunta)
                                        @php $pid = $loop->parent->index + 1 . '-s' . ($indexPergunta + 1); @endphp
                                        <div class="pergunta-item" id="pergunta-{{ $pid }}" data-pergunta="{{ $pid }}">
                                            <div class="pergunta-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center" style="gap: 10px;">
                                                    <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                                    <h6 class="mb-0">Pergunta</h6>
                                                </div>
                                                <div>
                                                    <i class="fas fa-trash remove-pergunta" onclick="removerPergunta('{{ $pid }}')" title="Remover"></i>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label>Pergunta *</label>
                                                        <input type="text" name="perguntas[{{ $pid }}][pergunta]" class="form-control" value="{{ $pergunta->pergunta }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Tipo *</label>
                                                        <select name="perguntas[{{ $pid }}][tipo]" class="form-control tipo-pergunta" onchange="toggleOpcoes('{{ $pid }}')" required>
                                                            <option value="">Selecione...</option>
                                                            <option value="texto_simples" {{ $pergunta->tipo == 'texto_simples' ? 'selected' : '' }}>Texto Simples</option>
                                                            <option value="texto_longo" {{ $pergunta->tipo == 'texto_longo' ? 'selected' : '' }}>Texto Longo</option>
                                                            <option value="radio" {{ $pergunta->tipo == 'radio' ? 'selected' : '' }}>Única Escolha (Radio)</option>
                                                            <option value="checkbox" {{ $pergunta->tipo == 'checkbox' ? 'selected' : '' }}>Múltipla Escolha (Checkbox)</option>
                                                            <option value="select" {{ $pergunta->tipo == 'select' ? 'selected' : '' }}>Seleção (Select)</option>
                                                        </select>
                                                        <input type="hidden" name="perguntas[{{ $pid }}][secao_id]" value="{{ $secaoIndex }}">
                                                        <input type="hidden" name="perguntas[{{ $pid }}][ordem]" class="input-ordem" value="{{ $pergunta->ordem }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>
                                                            <input type="checkbox" name="perguntas[{{ $pid }}][obrigatoria]" value="1" {{ $pergunta->obrigatoria ? 'checked' : '' }}>
                                                            Pergunta obrigatória
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group formato-validacao-container" id="formato-{{ $pid }}" style="display: {{ $pergunta->tipo == 'texto_simples' ? 'block' : 'none' }};">
                                                        <label>Formato de Validação</label>
                                                        <select name="perguntas[{{ $pid }}][formato_validacao]" class="form-control">
                                                            <option value="texto_comum" {{ $pergunta->formato_validacao == 'texto_comum' ? 'selected' : '' }}>Texto Comum</option>
                                                            <option value="data" {{ $pergunta->formato_validacao == 'data' ? 'selected' : '' }}>Data</option>
                                                            <option value="cpf" {{ $pergunta->formato_validacao == 'cpf' ? 'selected' : '' }}>CPF</option>
                                                            <option value="telefone" {{ $pergunta->formato_validacao == 'telefone' ? 'selected' : '' }}>Telefone</option>
                                                            <option value="email" {{ $pergunta->formato_validacao == 'email' ? 'selected' : '' }}>E-mail</option>
                                                            <option value="numero" {{ $pergunta->formato_validacao == 'numero' ? 'selected' : '' }}>Número</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="opcoes-container" id="opcoes-{{ $pid }}" style="display: {{ in_array($pergunta->tipo, ['radio', 'checkbox', 'select']) ? 'block' : 'none' }};">
                                                <label>Opções de Resposta *</label>
                                                <div id="opcoes-lista-{{ $pid }}">
                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                        <div class="opcao-item">
                                                            <input type="text" name="perguntas[{{ $pid }}][opcoes][]" class="form-control" style="width: 80%;" value="{{ $opcao->opcao }}" placeholder="Digite a opção" required>
                                                            <i class="fas fa-times remove-opcao" onclick="this.parentElement.remove()"></i>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-info" onclick="adicionarOpcao('{{ $pid }}')">
                                                    <i class="fas fa-plus"></i> Adicionar Opção
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="d-flex justify-content-start mt-3">
                                    <button type="button" class="btn btn-success" onclick="adicionarPergunta({{ $secaoIndex }})">
                                        <i class="fas fa-plus"></i> Adicionar Pergunta
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-success" onclick="adicionarSecao()">
                        <i class="fas fa-plus me-1"></i>Adicionar Seção
                    </button>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.questionario.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Atualizar Template
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>



    @section('js')
        <script>
            let perguntaCounter = {{ $questionario->perguntas->count() }};
            let secaoCounter = {{ $questionario->secoes()->count() + ($questionario->perguntas()->whereNull('secao_id')->count() > 0 ? 1 : 0) }};

            function adicionarPergunta(secaoNumero) {
                perguntaCounter++;
                const container = document.getElementById(`lista-perguntas-${secaoNumero}`);

                if (!container) {
                    console.error(`Container de perguntas para seção ${secaoNumero} não encontrado`);
                    return;
                }

                const perguntaHtml = `
                    <div class="pergunta-item" id="pergunta-${perguntaCounter}" data-pergunta="${perguntaCounter}">
                        <div class="pergunta-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                <h6 class="mb-0">Pergunta ${perguntaCounter}</h6>
                            </div>
                            <div>
                                <i class="fas fa-trash remove-pergunta" onclick="removerPergunta(${perguntaCounter})" title="Remover"></i>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Pergunta *</label>
                                    <input type="text" name="perguntas[${perguntaCounter}][pergunta]"
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo *</label>
                                    <select name="perguntas[${perguntaCounter}][tipo]" class="form-control tipo-pergunta"
                                            onchange="toggleOpcoes(${perguntaCounter})" required>
                                        <option value="">Selecione...</option>
                                        <option value="texto_simples">Texto Simples</option>
                                        <option value="texto_longo">Texto Longo</option>
                                        <option value="radio">Única Escolha (Radio)</option>
                                        <option value="checkbox">Múltipla Escolha (Checkbox)</option>
                                        <option value="select">Seleção (Select)</option>
                                    </select>
                                    <input type="hidden" name="perguntas[${perguntaCounter}][secao_id]" value="${secaoNumero}">
                                    <input type="hidden" name="perguntas[${perguntaCounter}][ordem]" class="input-ordem" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="perguntas[${perguntaCounter}][obrigatoria]" value="1">
                                        Pergunta obrigatória
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group formato-validacao-container" id="formato-${perguntaCounter}" style="display: none;">
                                    <label>Formato de Validação</label>
                                    <select name="perguntas[${perguntaCounter}][formato_validacao]" class="form-control">
                                        <option value="texto_comum">Texto Comum</option>
                                        <option value="data">Data</option>
                                        <option value="cpf">CPF</option>
                                        <option value="telefone">Telefone</option>
                                        <option value="email">E-mail</option>
                                        <option value="numero">Número</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="opcoes-container" id="opcoes-${perguntaCounter}" style="display: none;">
                            <label>Opções de Resposta *</label>
                            <div id="opcoes-lista-${perguntaCounter}">
                                <!-- Opções serão adicionadas aqui -->
                            </div>
                            <button type="button" class="btn btn-info" onclick="adicionarOpcao(${perguntaCounter})">
                                <i class="fas fa-plus"></i> Adicionar Opção
                            </button>
                        </div>
                    </div>
                `;

                container.insertAdjacentHTML('beforeend', perguntaHtml);
                atualizarOrdenacao();
            }

            function removerPergunta(numero) {
                const pergunta = document.getElementById(`pergunta-${numero}`);
                if (pergunta) {
                    pergunta.remove();
                    atualizarOrdenacao();
                }
            }

            function toggleOpcoes(numero) {
                const tipo = document.querySelector(`#pergunta-${numero} .tipo-pergunta`).value;
                const opcoesContainer = document.getElementById(`opcoes-${numero}`);
                const opcoesLista = document.getElementById(`opcoes-lista-${numero}`);
                const formatoContainer = document.getElementById(`formato-${numero}`);

                if (['radio', 'checkbox', 'select'].includes(tipo)) {
                    opcoesContainer.style.display = 'block';
                    formatoContainer.style.display = 'none';
                    if (opcoesLista.children.length === 0) {
                        adicionarOpcao(numero);
                        adicionarOpcao(numero);
                    }
                } else if (tipo === 'texto_simples') {
                    opcoesContainer.style.display = 'none';
                    formatoContainer.style.display = 'block';
                    opcoesLista.innerHTML = '';
                } else {
                    opcoesContainer.style.display = 'none';
                    formatoContainer.style.display = 'none';
                    opcoesLista.innerHTML = '';
                }
            }

            function adicionarOpcao(numero) {
                const opcoesLista = document.getElementById(`opcoes-lista-${numero}`);
                const opcaoCounter = opcoesLista.children.length;

                const opcaoHtml = `
                    <div class="opcao-item">
                        <input type="text" name="perguntas[${numero}][opcoes][]"
                               class="form-control" style="width: 80%;" placeholder="Digite a opção" required>
                        <i class="fas fa-times remove-opcao" onclick="this.parentElement.remove()"></i>
                    </div>
                `;

                opcoesLista.insertAdjacentHTML('beforeend', opcaoHtml);
            }

            function inicializarSortablePerguntas(listaId) {
                if (window.Sortable) {
                    new Sortable(document.getElementById(listaId), {
                        animation: 150,
                        handle: '.pergunta-header',
                        group: 'perguntas',
                        onAdd: function (evt) {
                            const perguntaEl = evt.item;
                            const novaLista = evt.to;
                            const secaoWrapper = novaLista.closest('.secao-item');
                            if (perguntaEl && secaoWrapper) {
                                const secaoNumero = secaoWrapper.getAttribute('data-secao');
                                const hiddenSecao = perguntaEl.querySelector('input[name^="perguntas"][name$="[secao_id]"]');
                                if (hiddenSecao) hiddenSecao.value = secaoNumero;
                            }
                            atualizarOrdenacao();
                        },
                        onSort: atualizarOrdenacao
                    });
                }
            }

            function atualizarOrdenacao() {
                document.querySelectorAll('.lista-perguntas').forEach(lista => {
                    Array.from(lista.children).forEach((el, index) => {
                        const inputOrdem = el.querySelector('.input-ordem');
                        if (inputOrdem) inputOrdem.value = index + 1;
                    });
                });
            }

            function adicionarSecao() {
                secaoCounter++;
                const secoesContainer = document.getElementById('secoes-container');
                const secaoHtml = `
                    <div class="card mb-3 secao-item" id="secao-${secaoCounter}" data-secao="${secaoCounter}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                <i class="fas fa-grip-vertical text-muted"></i>
                                <input type="text" name="secoes[${secaoCounter}][titulo]" class="form-control form-control-sm" placeholder="Título da seção" style="max-width: 320px;" required>
                            </div>
                            <div class="ml-auto">
                                <i class="fas fa-trash remove-secao" onclick="removerSecao(${secaoCounter})" title="Remover Seção"></i>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea name="secoes[${secaoCounter}][descricao]" class="form-control form-control-sm" rows="2" placeholder="Descrição da seção (opcional)"></textarea>
                            </div>
                            <div class="lista-perguntas" id="lista-perguntas-${secaoCounter}"></div>
                            <div class="d-flex justify-content-start mt-3">
                                <button type="button" class="btn btn-success" onclick="adicionarPergunta(${secaoCounter})">
                                    <i class="fas fa-plus"></i> Adicionar Pergunta
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                secoesContainer.insertAdjacentHTML('beforeend', secaoHtml);
                inicializarSortablePerguntas(`lista-perguntas-${secaoCounter}`);
                atualizarOrdenacao();
            }

            function removerSecao(numero) {
                const secao = document.getElementById(`secao-${numero}`);
                if (secao) secao.remove();
                atualizarOrdenacao();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const scriptSortable = document.createElement('script');
                scriptSortable.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js';
                scriptSortable.onload = () => {
                    document.querySelectorAll('.lista-perguntas').forEach(lista => {
                        inicializarSortablePerguntas(lista.id);
                    });
                    const btnAdd = document.getElementById('btn-add-secao');
                    if (btnAdd) btnAdd.addEventListener('click', adicionarSecao);
                };
                document.body.appendChild(scriptSortable);
            });
        </script>
    @endsection
</x-admin>
