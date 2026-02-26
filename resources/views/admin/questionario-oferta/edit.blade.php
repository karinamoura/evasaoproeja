<x-admin>
    @section('title', 'Editar Questionário de Oferta')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list me-2"></i>Editar Questionário de Oferta
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.questionario-oferta.index') }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('admin.questionario-oferta.update', $questionarioOferta->id) }}" method="POST" id="questionarioOfertaForm" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="card-body">
                <!-- Informações da Oferta e Questionário Base -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Oferta</label>
                            <input type="text" class="form-control" value="{{ $questionarioOferta->oferta->name }} - {{ $questionarioOferta->oferta->institution->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Questionário Base</label>
                            <input type="text" class="form-control" value="{{ $questionarioOferta->questionario->titulo }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Personalização -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="titulo_personalizado" class="form-label">Título Personalizado</label>
                            <input type="text"
                                   name="titulo_personalizado"
                                   id="titulo_personalizado"
                                   class="form-control @error('titulo_personalizado') is-invalid @enderror"
                                   value="{{ old('titulo_personalizado', $questionarioOferta->titulo_personalizado) }}"
                                   placeholder="Deixe em branco para usar o título base">
                            <x-error>titulo_personalizado</x-error>
                            <small class="form-text text-muted">Opcional. Se não preenchido, será usado o título do questionário base.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="cor_personalizada" class="form-label">Cor Personalizada</label>
                            <input type="color"
                                   name="cor_personalizada"
                                   id="cor_personalizada"
                                   class="form-control @error('cor_personalizada') is-invalid @enderror"
                                   value="{{ old('cor_personalizada', $questionarioOferta->cor_personalizada ?? '#667eea') }}"
                                   style="height: 38px; padding: 2px;">
                            <x-error>cor_personalizada</x-error>
                            <small class="form-text text-muted">Escolha a cor principal do formulário público.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ativo" class="form-label">Status</label>
                            <select name="ativo"
                                    id="ativo"
                                    class="form-control @error('ativo') is-invalid @enderror">
                                <option value="1" {{ old('ativo', $questionarioOferta->ativo) == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ old('ativo', $questionarioOferta->ativo) == '0' ? 'selected' : '' }}>Inativo</option>
                            </select>
                            <x-error>ativo</x-error>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="descricao_personalizada" class="form-label">Descrição Personalizada</label>
                    <textarea name="descricao_personalizada"
                              id="descricao_personalizada"
                              class="form-control @error('descricao_personalizada') is-invalid @enderror"
                              rows="3"
                              placeholder="Deixe em branco para usar a descrição base">{{ old('descricao_personalizada', $questionarioOferta->descricao_personalizada) }}</textarea>
                    <x-error>descricao_personalizada</x-error>
                    <small class="form-text text-muted">Opcional. Se não preenchido, será usada a descrição do questionário base.</small>
                </div>

                <div class="form-group">
                    <label for="termo_condicao_id" class="form-label">
                        Termos e Condições
                    </label>
                    <select name="termo_condicao_id"
                            id="termo_condicao_id"
                            class="form-control @error('termo_condicao_id') is-invalid @enderror">
                        <option value="">Selecione um termo (opcional)</option>
                        @foreach($termos as $termo)
                            <option value="{{ $termo->id }}" {{ old('termo_condicao_id', $questionarioOferta->termo_condicao_id) == $termo->id ? 'selected' : '' }}>
                                {{ $termo->titulo }}
                            </option>
                        @endforeach
                    </select>
                    <x-error>termo_condicao_id</x-error>
                    <small class="form-text text-muted">
                        Selecione um termo e condições cadastrado. Se não selecionar, será usado um texto padrão na primeira seção do formulário público.
                        <a href="{{ route('admin.termo-condicao.create') }}" target="_blank" class="ml-1">
                            <i class="fas fa-plus"></i> Criar novo termo
                        </a>
                    </small>
                </div>

                <hr>
                <h5 class="text-center">
                    <i class="fas fa-layer-group me-2"></i>Seções e Perguntas Personalizadas
                </h5>

                <div id="secoes-container">
                    @php
                        // Carregar seções e perguntas com eager loading
                        $secoes = $questionarioOferta->secoes;
                    @endphp

                    @foreach($secoes as $secao)
                                @php
                                    $secaoNumero = $loop->iteration;
                                    // Buscar perguntas da seção base no questionário original
                                    $perguntas = $secao->perguntas;
                                @endphp
                                <div class="card mb-3 secao-item" id="secao-{{ $secaoNumero }}" data-secao="{{ $secaoNumero }}" data-secao-original="{{ $secao->id }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                            <input type="text" name="secoes[{{ $secaoNumero }}][titulo]" class="form-control form-control-sm"
                                                   value="{{ $secao->titulo }}" style="max-width: 320px;" required>
                                        </div>
                                        <div class="ml-auto">
                                            <i class="fas fa-trash remove-secao" onclick="removerSecao({{ $secaoNumero }})" title="Remover Seção"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <textarea name="secoes[{{ $secaoNumero }}][descricao]" class="form-control form-control-sm" rows="2"
                                                       placeholder="Descrição da seção (opcional)">{{ $secao->descricao }}</textarea>
                                            <input type="hidden" name="secoes[{{ $secaoNumero }}][id]" value="{{ $secao->id }}">
                                            <input type="hidden" name="secoes[{{ $secaoNumero }}][indice]" value="{{ $secaoNumero }}">
                                        </div>
                                        <div class="lista-perguntas" id="lista-perguntas-{{ $secaoNumero }}">
                                            @foreach($secao->perguntas as $index => $pergunta)
                                                <div class="pergunta-item {{ $pergunta->personalizada ? 'pergunta-personalizada' : 'pergunta-base' }}"
                                                     id="pergunta-{{ $index + 1 }}" data-pergunta="{{ $index + 1 }}">
                                                    <div class="pergunta-header d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center" style="gap: 10px;">
                                                            <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                                            <h6 class="mb-0">
                                                                Pergunta {{ $index + 1 }}
                                                                <span class="pergunta-tipo badge badge-{{ $pergunta->personalizada ? 'warning' : 'info' }}">
                                                                    {{ $pergunta->personalizada ? 'Personalizada' : 'Base' }}
                                                                </span>
                                                            </h6>
                                                        </div>
                                                        <div>
                                                            @if($pergunta->personalizada)
                                                                <i class="fas fa-trash remove-pergunta" onclick="removerPergunta(this)" title="Remover" style="color: #dc3545; cursor: pointer;"></i>
                                                            @else
                                                                <i class="fas fa-eye ocultar-pergunta" onclick="ocultarPergunta({{ $index + 1 }})" title="Ocultar Pergunta" style="color: #6c757d; cursor: pointer;"></i>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @if($pergunta->personalizada)
                                                        <!-- Pergunta personalizada - editável -->
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label>Pergunta *</label>
                                                                    <input type="text" name="perguntas[{{ $index + 1 }}][pergunta]"
                                                                           class="form-control" value="{{ $pergunta->pergunta }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Tipo *</label>
                                                                    <select name="perguntas[{{ $index + 1 }}][tipo]" class="form-control tipo-pergunta"
                                                                            onchange="toggleOpcoes({{ $index + 1 }})" required>
                                                                        <option value="">Selecione...</option>
                                                                        <option value="texto_simples" {{ $pergunta->tipo == 'texto_simples' ? 'selected' : '' }}>Texto Simples</option>
                                                                        <option value="texto_longo" {{ $pergunta->tipo == 'texto_longo' ? 'selected' : '' }}>Texto Longo</option>
                                                                        <option value="radio" {{ $pergunta->tipo == 'radio' ? 'selected' : '' }}>Única Escolha (Radio)</option>
                                                                        <option value="checkbox" {{ $pergunta->tipo == 'checkbox' ? 'selected' : '' }}>Múltipla Escolha (Checkbox)</option>
                                                                        <option value="select" {{ $pergunta->tipo == 'select' ? 'selected' : '' }}>Seleção (Select)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="checkbox" name="perguntas[{{ $index + 1 }}][obrigatoria]" value="1"
                                                                               {{ $pergunta->obrigatoria ? 'checked' : '' }}>
                                                                        Pergunta obrigatória
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group formato-validacao-container" id="formato-{{ $index + 1 }}"
                                                                     style="display: {{ $pergunta->tipo == 'texto_simples' ? 'block' : 'none' }};">
                                                                    <label>Formato de Validação</label>
                                                                    <select name="perguntas[{{ $index + 1 }}][formato_validacao]" class="form-control">
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

                                                        <div class="opcoes-container" id="opcoes-{{ $index + 1 }}"
                                                             style="display: {{ in_array($pergunta->tipo, ['radio', 'checkbox', 'select']) ? 'block' : 'none' }};">
                                                            <label>Opções de Resposta *</label>
                                                            <div id="opcoes-lista-{{ $index + 1 }}">
                                                                @if($pergunta->opcoesResposta && count($pergunta->opcoesResposta) > 0)
                                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                                        <div class="opcao-item">
                                                                            <input type="text" name="perguntas[{{ $index + 1 }}][opcoes][]"
                                                                                   class="form-control" style="width: 80%;"
                                                                                   value="{{ $opcao->opcao }}" placeholder="Digite a opção" required>
                                                                            <i class="fas fa-times remove-opcao" onclick="this.parentElement.remove()"></i>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <button type="button" class="btn btn-info" onclick="adicionarOpcao({{ $index + 1 }})">
                                                                <i class="fas fa-plus"></i> Adicionar Opção
                                                            </button>
                                                        </div>

                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][id]" value="{{ $pergunta->id }}">
                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][personalizada]" value="1">
                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][secao_id]" value="{{ $secao->id }}">
                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][ordem]" class="input-ordem" value="{{ $pergunta->ordem ?? $index + 1 }}">
                                                    @else
                                                        <!-- Pergunta base - apenas exibição -->
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label>Pergunta</label>
                                                                    <input type="text" class="form-control" value="{{ $pergunta->pergunta }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Tipo</label>
                                                                    <input type="text" class="form-control" value="{{ $pergunta->tipo }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="checkbox" {{ $pergunta->obrigatoria ? 'checked' : '' }} disabled>
                                                                        Pergunta obrigatória
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Formato de Validação</label>
                                                                    <input type="text" class="form-control"
                                                                           value="{{ $pergunta->formato_validacao ? str_replace('_', ' ', ucwords($pergunta->formato_validacao)) : 'Texto Comum' }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="radio" name="pergunta_identificadora" value="{{ $pergunta->id }}"
                                                                               {{ old('pergunta_identificadora', $questionarioOferta->pergunta_identificadora_id) == $pergunta->id ? 'checked' : '' }}>
                                                                        <span class="text-primary"><i class="fas fa-user"></i> Usar como identificador</span>
                                                                    </label>
                                                                    <small class="form-text text-muted">Marque para usar esta pergunta como identificador do respondente.</small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="opcoes-container" id="opcoes-{{ $index + 1 }}"
                                                             style="display: {{ in_array($pergunta->tipo, ['radio', 'checkbox', 'select']) ? 'block' : 'none' }};">
                                                            <label>Opções de Resposta</label>
                                                            <div id="opcoes-lista-{{ $index + 1 }}">
                                                                @if($pergunta->opcoesResposta && count($pergunta->opcoesResposta) > 0)
                                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                                        <div class="opcao-item">
                                                                            <span class="form-control-plaintext">{{ $opcao->opcao }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="opcao-item">
                                                                        <span class="form-control-plaintext text-muted">Nenhuma opção definida</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][secao_id]" value="{{ $secaoNumero }}">
                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][ordem]" class="input-ordem" value="{{ $pergunta->ordem ?? $index + 1 }}">
                                                        <input type="hidden" name="perguntas[{{ $index + 1 }}][oculta]" value="0" class="input-oculta">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-start mt-3">
                                            <button type="button" class="btn btn-success" onclick="adicionarPergunta({{ $secaoNumero }})">
                                                <i class="fas fa-plus"></i> Adicionar Pergunta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Seção para perguntas sem seção (se houver) -->
                            @php
                                // Buscar perguntas sem seção no questionário oferta
                                $perguntasSemSecao = $questionarioOferta->perguntas->whereNull('secao_oferta_id');
                            @endphp

                            @if($perguntasSemSecao->count() > 0)
                                @php
                                    $secaoNumero = $questionarioOferta->secoes->count() + 1;
                                @endphp
                                <div class="card mb-3 secao-item" id="secao-{{ $secaoNumero }}" data-secao="{{ $secaoNumero }}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                            <input type="text" name="secoes[{{ $secaoNumero }}][titulo]" class="form-control form-control-sm"
                                                   value="Perguntas Gerais" style="max-width: 320px;" required>
                                        </div>
                                        <div class="ml-auto">
                                            <i class="fas fa-trash remove-secao" onclick="removerSecao({{ $secaoNumero }})" title="Remover Seção"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <textarea name="secoes[{{ $secaoNumero }}][descricao]" class="form-control form-control-sm" rows="2"
                                                       placeholder="Descrição da seção (opcional)"></textarea>
                                            <input type="hidden" name="secoes[{{ $secaoNumero }}][indice]" value="{{ $secaoNumero }}">
                                        </div>
                                        <div class="lista-perguntas" id="lista-perguntas-{{ $secaoNumero }}">
                                            @foreach($perguntasSemSecao as $index => $pergunta)
                                                <div class="pergunta-item {{ $pergunta->personalizada ? 'pergunta-personalizada' : 'pergunta-base' }}"
                                                     id="pergunta-sem-secao-{{ $index + 1 }}" data-pergunta="{{ $index + 1 }}">
                                                    <div class="pergunta-header d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center" style="gap: 10px;">
                                                            <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                                            <h6 class="mb-0">
                                                                Pergunta {{ $index + 1 }}
                                                                <span class="pergunta-tipo badge badge-{{ $pergunta->personalizada ? 'warning' : 'info' }}">
                                                                    {{ $pergunta->personalizada ? 'Personalizada' : 'Base' }}
                                                                </span>
                                                            </h6>
                                                        </div>
                                                        <div>
                                                            @if($pergunta->personalizada)
                                                                <i class="fas fa-trash remove-pergunta" onclick="removerPergunta(this)" title="Remover" style="color: #dc3545; cursor: pointer;"></i>
                                                            @else
                                                                <i class="fas fa-eye ocultar-pergunta" onclick="ocultarPergunta('sem-secao-{{ $index + 1 }}')" title="Ocultar Pergunta" style="color: #6c757d; cursor: pointer;"></i>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    @if($pergunta->personalizada)
                                                        <!-- Pergunta personalizada - editável -->
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label>Pergunta *</label>
                                                                    <input type="text" name="perguntas[sem-secao-{{ $index + 1 }}][pergunta]"
                                                                           class="form-control" value="{{ $pergunta->pergunta }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Tipo *</label>
                                                                    <select name="perguntas[sem-secao-{{ $index + 1 }}][tipo]" class="form-control tipo-pergunta"
                                                                            onchange="toggleOpcoes('sem-secao-{{ $index + 1 }}')" required>
                                                                        <option value="">Selecione...</option>
                                                                        <option value="texto_simples" {{ $pergunta->tipo == 'texto_simples' ? 'selected' : '' }}>Texto Simples</option>
                                                                        <option value="texto_longo" {{ $pergunta->tipo == 'texto_longo' ? 'selected' : '' }}>Texto Longo</option>
                                                                        <option value="radio" {{ $pergunta->tipo == 'radio' ? 'selected' : '' }}>Única Escolha (Radio)</option>
                                                                        <option value="checkbox" {{ $pergunta->tipo == 'checkbox' ? 'selected' : '' }}>Múltipla Escolha (Checkbox)</option>
                                                                        <option value="select" {{ $pergunta->tipo == 'select' ? 'selected' : '' }}>Seleção (Select)</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="checkbox" name="perguntas[sem-secao-{{ $index + 1 }}][obrigatoria]" value="1"
                                                                               {{ $pergunta->obrigatoria ? 'checked' : '' }}>
                                                                        Pergunta obrigatória
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group formato-validacao-container" id="formato-sem-secao-{{ $index + 1 }}"
                                                                     style="display: {{ $pergunta->tipo == 'texto_simples' ? 'block' : 'none' }};">
                                                                    <label>Formato de Validação</label>
                                                                    <select name="perguntas[sem-secao-{{ $index + 1 }}][formato_validacao]" class="form-control">
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

                                                        <div class="opcoes-container" id="opcoes-sem-secao-{{ $index + 1 }}"
                                                             style="display: {{ in_array($pergunta->tipo, ['radio', 'checkbox', 'select']) ? 'block' : 'none' }};">
                                                            <label>Opções de Resposta *</label>
                                                            <div id="opcoes-lista-sem-secao-{{ $index + 1 }}">
                                                                @if($pergunta->opcoesResposta && count($pergunta->opcoesResposta) > 0)
                                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                                        <div class="opcao-item">
                                                                            <input type="text" name="perguntas[sem-secao-{{ $index + 1 }}][opcoes][]"
                                                                                   class="form-control" style="width: 80%;"
                                                                                   value="{{ $opcao->opcao }}" placeholder="Digite a opção" required>
                                                                            <i class="fas fa-times remove-opcao" onclick="this.parentElement.remove()"></i>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-info" onclick="adicionarOpcao('sem-secao-{{ $index + 1 }}')">
                                                                <i class="fas fa-plus"></i> Adicionar Opção
                                                            </button>
                                                        </div>

                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][id]" value="{{ $pergunta->id }}">
                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][personalizada]" value="1">
                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][secao_id]" value="{{ $secaoNumero }}">
                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][ordem]" class="input-ordem" value="{{ $pergunta->ordem ?? $index + 1 }}">
                                                    @else
                                                        <!-- Pergunta base - apenas exibição -->
                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label>Pergunta</label>
                                                                    <input type="text" class="form-control" value="{{ $pergunta->pergunta }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Tipo</label>
                                                                    <input type="text" class="form-control" value="{{ $pergunta->tipo }}" readonly>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="checkbox" {{ $pergunta->obrigatoria ? 'checked' : '' }} disabled>
                                                                        Pergunta obrigatória
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Formato de Validação</label>
                                                                    <input type="text" class="form-control"
                                                                           value="{{ $pergunta->formato_validacao ? str_replace('_', ' ', ucwords($pergunta->formato_validacao)) : 'Texto Comum' }}" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>
                                                                        <input type="radio" name="pergunta_identificadora" value="{{ $pergunta->id }}"
                                                                               {{ old('pergunta_identificadora', $questionarioOferta->pergunta_identificadora_id) == $pergunta->id ? 'checked' : '' }}>
                                                                        <span class="text-primary"><i class="fas fa-user"></i> Usar como identificador</span>
                                                                    </label>
                                                                    <small class="form-text text-muted">Marque para usar esta pergunta como identificador do respondente.</small>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="opcoes-container" id="opcoes-sem-secao-{{ $index + 1 }}"
                                                             style="display: {{ in_array($pergunta->tipo, ['radio', 'checkbox', 'select']) ? 'block' : 'none' }};">
                                                            <label>Opções de Resposta</label>
                                                            <div id="opcoes-lista-sem-secao-{{ $index + 1 }}">
                                                                @if($pergunta->opcoesResposta && count($pergunta->opcoesResposta) > 0)
                                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                                        <div class="opcao-item">
                                                                            <span class="form-control-plaintext">{{ $opcao->opcao }}</span>
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <div class="opcao-item">
                                                                        <span class="form-control-plaintext text-muted">Nenhuma opção definida</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][secao_id]" value="{{ $secaoNumero }}">
                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][ordem]" class="input-ordem" value="{{ $pergunta->ordem ?? $index + 1 }}">
                                                        <input type="hidden" name="perguntas[sem-secao-{{ $index + 1 }}][oculta]" value="0" class="input-oculta">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="d-flex justify-content-start mt-3">
                                            <button type="button" class="btn btn-success" onclick="adicionarPergunta({{ $secaoNumero }})">
                                                <i class="fas fa-plus"></i> Adicionar Pergunta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                <div class="text-center mb-3">
                    <button type="button" class="btn btn-success" onclick="adicionarSecao()">
                        <i class="fas fa-layer-group me-1"></i>Adicionar Seção
                    </button>
                </div>

            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.questionario-oferta.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Atualizar Questionário de Oferta
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @section('css')
        <style>
            .pergunta-item {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 15px;
                background-color: #f9f9f9;
            }
            .pergunta-base {
                background-color: #e3f2fd;
                border-color: #2196f3;
            }
            .pergunta-personalizada {
                background-color: #fff3e0;
                border-color: #ff9800;
            }
            .pergunta-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                cursor: move;
            }
            .opcoes-container {
                margin-top: 10px;
                padding-left: 20px;
            }
            .opcao-item {
                display: flex;
                align-items: center;
                margin-bottom: 8px;
            }
            .remove-opcao {
                margin-left: 10px;
                color: #dc3545;
                cursor: pointer;
            }
            .remove-pergunta {
                color: #dc3545;
                cursor: pointer;
                font-size: 18px;
            }
            .pergunta-tipo {
                font-size: 0.8em;
                padding: 2px 8px;
                border-radius: 12px;
            }
        </style>
    @endsection

    @section('js')
        <script>
            let perguntaCounter = {{ $questionarioOferta->perguntas->count() }};
            let secaoCounter = {{ $questionarioOferta->secoes->count() }};

            function adicionarPergunta(secaoNumero) {
                perguntaCounter++;
                const container = document.getElementById(`lista-perguntas-${secaoNumero}`);

                if (!container) {
                    console.error(`Container de perguntas para seção ${secaoNumero} não encontrado`);
                    return;
                }

                // Pegar o ID real da seção do atributo data-secao-original
                // Se não existir, é uma seção nova e usaremos null (será associada após criação da seção)
                const secaoItem = container.closest('.secao-item');
                const secaoId = secaoItem ? (secaoItem.getAttribute('data-secao-original') || null) : null;

                const perguntaVazia = {
                    pergunta: '',
                    tipo: '',
                    obrigatoria: false,
                    formato_validacao: 'texto_comum',
                    opcoes: []
                };

                // Para seções novas, usar o índice temporário como referência
                // O backend vai processar as seções primeiro e depois associar as perguntas
                const secaoIdOuIndice = secaoId || `new-${secaoNumero}`;

                const perguntaHtml = criarHtmlPergunta(perguntaVazia, perguntaCounter, true, secaoIdOuIndice);
                container.insertAdjacentHTML('beforeend', perguntaHtml);
                atualizarOrdenacao();
            }

            function removerPergunta(elemento) {
                // Se recebeu um número (compatibilidade com código antigo)
                if (typeof elemento === 'number' || (typeof elemento === 'string' && !elemento.nodeType)) {
                    const numero = elemento;
                    let pergunta = null;

                    // Tentar encontrar pelo ID exato primeiro
                    if (typeof numero === 'string' && numero.includes('sem-secao-')) {
                        const num = numero.replace('sem-secao-', '');
                        pergunta = document.getElementById(`pergunta-sem-secao-${num}`);
                    } else {
                        pergunta = document.getElementById(`pergunta-${numero}`);
                    }

                    // Se não encontrou, tentar encontrar pelo atributo data-pergunta
                    if (!pergunta) {
                        const todasPerguntas = document.querySelectorAll('.pergunta-item');
                        todasPerguntas.forEach(p => {
                            const dataPergunta = p.getAttribute('data-pergunta');
                            const idPergunta = p.id;

                            if (dataPergunta == numero ||
                                idPergunta === `pergunta-${numero}` ||
                                idPergunta === `pergunta-sem-secao-${numero}`) {
                                pergunta = p;
                            }
                        });
                    }

                    if (pergunta) {
                        pergunta.remove();
                        atualizarOrdenacao();
                    }
                } else {
                    // Se recebeu o elemento diretamente (recomendado)
                    const botao = elemento;
                    const perguntaItem = botao.closest('.pergunta-item');
                    if (perguntaItem) {
                        perguntaItem.remove();
                        atualizarOrdenacao();
                    } else {
                        console.error('Elemento pergunta-item não encontrado');
                    }
                }
            }

            function ocultarPergunta(numero) {
                // Tratar diferentes formatos de ID
                let pergunta = document.getElementById(`pergunta-${numero}`);
                if (!pergunta) {
                    pergunta = document.getElementById(`pergunta-sem-secao-${numero}`);
                }

                if (pergunta) {
                    const inputOculta = pergunta.querySelector('.input-oculta');
                    const iconOcultar = pergunta.querySelector('.ocultar-pergunta');

                    if (inputOculta && iconOcultar) {
                        if (inputOculta.value === '0') {
                            // Ocultar pergunta
                            inputOculta.value = '1';
                            pergunta.style.opacity = '0.5';
                            pergunta.style.backgroundColor = '#f8f9fa';
                            iconOcultar.className = 'fas fa-eye-slash ocultar-pergunta';
                            iconOcultar.title = 'Mostrar Pergunta';
                            iconOcultar.style.color = '#28a745';
                        } else {
                            // Mostrar pergunta
                            inputOculta.value = '0';
                            pergunta.style.opacity = '1';
                            pergunta.style.backgroundColor = '';
                            iconOcultar.className = 'fas fa-eye ocultar-pergunta';
                            iconOcultar.title = 'Ocultar Pergunta';
                            iconOcultar.style.color = '#6c757d';
                        }
                    }
                } else {
                    console.error(`Pergunta com ID pergunta-${numero} não encontrada`);
                }
            }

            function toggleOpcoes(numero) {
                const tipo = document.querySelector(`#pergunta-${numero} .tipo-pergunta`).value;
                const opcoesContainer = document.getElementById(`opcoes-${numero}`);
                const opcoesLista = document.getElementById(`opcoes-lista-${numero}`);
                const formatoContainer = document.getElementById(`formato-${numero}`);

                if (['radio', 'checkbox', 'select'].includes(tipo)) {
                    opcoesContainer.style.display = 'block';
                    if (formatoContainer) formatoContainer.style.display = 'none';
                    if (opcoesLista.children.length === 0) {
                        adicionarOpcao(numero);
                        adicionarOpcao(numero);
                    }
                } else if (tipo === 'texto_simples') {
                    opcoesContainer.style.display = 'none';
                    if (formatoContainer) formatoContainer.style.display = 'block';
                    opcoesLista.innerHTML = '';
                } else {
                    opcoesContainer.style.display = 'none';
                    if (formatoContainer) formatoContainer.style.display = 'none';
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

            function criarHtmlPergunta(pergunta, numero, personalizada, secaoId) {
                const tipoClasse = personalizada ? 'pergunta-personalizada' : 'pergunta-base';
                const tipoLabel = personalizada ? 'Personalizada' : 'Base';

                // Para perguntas base, não criar campos de formulário (apenas exibição)
                if (!personalizada) {
                    let opcoesHtml = '';
                    if (pergunta.opcoes && pergunta.opcoes.length > 0) {
                        pergunta.opcoes.forEach(opcao => {
                            opcoesHtml += `
                                <div class="opcao-item">
                                    <span class="form-control-plaintext">${opcao.opcao}</span>
                                </div>
                            `;
                        });
                    }

                    return `
                        <div class="pergunta-item ${tipoClasse}" id="pergunta-${numero}" data-pergunta="${numero}">
                            <div class="pergunta-header d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center" style="gap: 10px;">
                                    <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                    <h6 class="mb-0">
                                        Pergunta ${numero}
                                        <span class="pergunta-tipo badge badge-${personalizada ? 'warning' : 'info'}">${tipoLabel}</span>
                                    </h6>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Pergunta</label>
                                        <input type="text" class="form-control" value="${pergunta.pergunta}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo</label>
                                        <input type="text" class="form-control" value="${pergunta.tipo}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" ${pergunta.obrigatoria ? 'checked' : ''} disabled>
                                            Pergunta obrigatória
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Formato de Validação</label>
                                        <input type="text" class="form-control"
                                               value="${pergunta.formato_validacao ? pergunta.formato_validacao.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Texto Comum'}" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="opcoes-container" id="opcoes-${numero}"
                                 style="display: ${['radio', 'checkbox', 'select'].includes(pergunta.tipo) ? 'block' : 'none'};">
                                <label>Opções de Resposta</label>
                                <div id="opcoes-lista-${numero}">
                                    ${opcoesHtml}
                                </div>
                            </div>

                            <input type="hidden" name="perguntas[${numero}][secao_id]" value="${secaoNumero}">
                            <input type="hidden" name="perguntas[${numero}][ordem]" class="input-ordem" value="0">
                        </div>
                    `;
                }

                // Para perguntas personalizadas, criar campos de formulário editáveis
                let opcoesHtml = '';
                if (pergunta.opcoes && pergunta.opcoes.length > 0) {
                    pergunta.opcoes.forEach(opcao => {
                        opcoesHtml += `
                            <div class="opcao-item">
                                <input type="text" name="perguntas[${numero}][opcoes][]"
                                       class="form-control" style="width: 80%;"
                                       value="${opcao.opcao}" placeholder="Digite a opção" required>
                                <i class="fas fa-times remove-opcao" onclick="this.parentElement.remove()"></i>
                            </div>
                        `;
                    });
                }

                return `
                    <div class="pergunta-item ${tipoClasse}" id="pergunta-${numero}" data-pergunta="${numero}">
                        <div class="pergunta-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center" style="gap: 10px;">
                                <i class="fas fa-grip-vertical drag-hint" title="Arraste para mover"></i>
                                <h6 class="mb-0">
                                    Pergunta ${numero}
                                    <span class="pergunta-tipo badge badge-${personalizada ? 'warning' : 'info'}">${tipoLabel}</span>
                                </h6>
                                <div>
                                    <i class="fas fa-trash remove-pergunta" onclick="removerPergunta(this)" title="Remover" style="color: #dc3545; cursor: pointer;"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Pergunta *</label>
                                    <input type="text" name="perguntas[${numero}][pergunta]"
                                           class="form-control" value="${pergunta.pergunta}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo *</label>
                                    <select name="perguntas[${numero}][tipo]" class="form-control tipo-pergunta"
                                            onchange="toggleOpcoes(${numero})" required>
                                        <option value="">Selecione...</option>
                                        <option value="texto_simples" ${pergunta.tipo == 'texto_simples' ? 'selected' : ''}>Texto Simples</option>
                                        <option value="texto_longo" ${pergunta.tipo == 'texto_longo' ? 'selected' : ''}>Texto Longo</option>
                                        <option value="radio" ${pergunta.tipo == 'radio' ? 'selected' : ''}>Única Escolha (Radio)</option>
                                        <option value="checkbox" ${pergunta.tipo == 'checkbox' ? 'selected' : ''}>Múltipla Escolha (Checkbox)</option>
                                        <option value="select" ${pergunta.tipo == 'select' ? 'selected' : ''}>Seleção (Select)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" name="perguntas[${numero}][obrigatoria]" value="1"
                                               ${pergunta.obrigatoria ? 'checked' : ''}>
                                        Pergunta obrigatória
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group formato-validacao-container" id="formato-${numero}" style="display: none;">
                                    <label>Formato de Validação</label>
                                    <select name="perguntas[${numero}][formato_validacao]" class="form-control">
                                        <option value="texto_comum" ${pergunta.formato_validacao == 'texto_comum' ? 'selected' : ''}>Texto Comum</option>
                                        <option value="data" ${pergunta.formato_validacao == 'data' ? 'selected' : ''}>Data</option>
                                        <option value="cpf" ${pergunta.formato_validacao == 'cpf' ? 'selected' : ''}>CPF</option>
                                        <option value="telefone" ${pergunta.formato_validacao == 'telefone' ? 'selected' : ''}>Telefone</option>
                                        <option value="email" ${pergunta.formato_validacao == 'email' ? 'selected' : ''}>E-mail</option>
                                        <option value="numero" ${pergunta.formato_validacao == 'numero' ? 'selected' : ''}>Número</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="opcoes-container" id="opcoes-${numero}"
                             style="display: ${['radio', 'checkbox', 'select'].includes(pergunta.tipo) ? 'block' : 'none'};">
                            <label>Opções de Resposta *</label>
                            <div id="opcoes-lista-${numero}">
                                ${opcoesHtml}
                            </div>
                            <button type="button" class="btn btn-info" onclick="adicionarOpcao(${numero})">
                                <i class="fas fa-plus"></i> Adicionar Opção
                            </button>
                        </div>

                        <input type="hidden" name="perguntas[${numero}][personalizada]" value="1">
                        <input type="hidden" name="perguntas[${numero}][secao_id]" value="${secaoId}">
                        <input type="hidden" name="perguntas[${numero}][ordem]" class="input-ordem" value="0">
                    </div>
                `;
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
                                // Pegar o ID real da seção do atributo data-secao-original
                                const secaoId = secaoWrapper.getAttribute('data-secao-original');
                                const hiddenSecao = perguntaEl.querySelector('input[name^="perguntas"][name$="[secao_id]"]');
                                if (hiddenSecao && secaoId) {
                                    hiddenSecao.value = secaoId;
                                }
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
                                <input type="hidden" name="secoes[${secaoCounter}][indice]" value="${secaoCounter}">
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
