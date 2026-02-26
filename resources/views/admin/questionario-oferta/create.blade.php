<x-admin>
    @section('title', 'Criar Questionário de Oferta')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-list me-2"></i>Criar Novo Questionário de Oferta
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

        <form action="{{ route('admin.questionario-oferta.store') }}" method="POST" id="questionarioOfertaForm" class="needs-validation" novalidate>
            @csrf

            <div class="card-body">
                <!-- Seleção da Oferta e Questionário Base -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="oferta_id" class="form-label">Oferta <span class="text-danger">*</span></label>
                            <select name="oferta_id"
                                    id="oferta_id"
                                    class="form-control @error('oferta_id') is-invalid @enderror"
                                    required>
                                <option value="">Selecione uma oferta</option>
                                @foreach($ofertas as $oferta)
                                    <option value="{{ $oferta->id }}" {{ old('oferta_id') == $oferta->id ? 'selected' : '' }}>
                                        {{ $oferta->name }} - {{ $oferta->institution->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>oferta_id</x-error>
                            <div class="invalid-feedback">A oferta é obrigatória.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="questionario_id" class="form-label">Questionário Base <span class="text-danger">*</span></label>
                            <select name="questionario_id"
                                    id="questionario_id"
                                    class="form-control @error('questionario_id') is-invalid @enderror"
                                    required>
                                <option value="">Selecione um questionário</option>
                                @foreach($questionarios as $questionario)
                                    <option value="{{ $questionario->id }}" {{ old('questionario_id') == $questionario->id ? 'selected' : '' }}>
                                        {{ $questionario->titulo }} ({{ $questionario->perguntas->count() }} perguntas)
                                    </option>
                                @endforeach
                            </select>
                            <x-error>questionario_id</x-error>
                            <div class="invalid-feedback">O questionário base é obrigatório.</div>
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
                                   value="{{ old('titulo_personalizado') }}"
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
                                   value="{{ old('cor_personalizada', '#667eea') }}"
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
                                <option value="1" {{ old('ativo', '1') == '1' ? 'selected' : '' }}>Ativo</option>
                                <option value="0" {{ old('ativo') == '0' ? 'selected' : '' }}>Inativo</option>
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
                              placeholder="Deixe em branco para usar a descrição base">{{ old('descricao_personalizada') }}</textarea>
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
                            <option value="{{ $termo->id }}" {{ old('termo_condicao_id') == $termo->id ? 'selected' : '' }}>
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
                    <!-- As seções serão adicionadas aqui dinamicamente -->
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
                            <i class="fas fa-save me-1"></i>Salvar Questionário de Oferta
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
                align-items: center;
                gap: 8px;
                margin-bottom: 15px;
                cursor: move;
            }
            .drag-hint { color: #6c757d; font-size: 18px; }
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
            .secao-item {
                border: 1px solid #eee;
                border-radius: 8px;
                margin-bottom: 15px;
                overflow: hidden;
            }
            .secao-item .card-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #eee;
                padding: 10px 15px;
            }
            .secao-item .card-body {
                padding: 15px;
            }
            .secao-item .lista-perguntas {
                margin-top: 10px;
            }
        </style>
    @endsection

    @section('js')
        <script>
            let perguntaCounter = 0;
            let secaoCounter = 0;

            // Carregar perguntas quando um questionário base for selecionado
            document.addEventListener('DOMContentLoaded', function() {
                const questionarioSelect = document.getElementById('questionario_id');
                if (questionarioSelect) {
                    questionarioSelect.addEventListener('change', function() {
                        const questionarioId = this.value;
                        console.log('Questionário selecionado:', questionarioId);
                        if (questionarioId) {
                            carregarPerguntasBase(questionarioId);
                        } else {
                            document.getElementById('secoes-container').innerHTML = '';
                        }
                    });
                } else {
                    console.error('Elemento questionario_id não encontrado');
                }
            });

            function carregarPerguntasBase(questionarioId) {
                console.log('Carregando perguntas para questionário:', questionarioId);

                // Construir URL correta
                const url = `/admin/questionario/${questionarioId}/perguntas`;
                console.log('URL da requisição:', url);

                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                                        .then(data => {
                            console.log('Dados recebidos:', data);
                            console.log('Total de perguntas:', data.perguntas ? data.perguntas.length : 0);
                            if (data.success && data.perguntas) {
                        const container = document.getElementById('secoes-container');
                        container.innerHTML = '';

                                                // Primeiro, buscar as seções do questionário base
                        fetch(`/admin/questionario/${questionarioId}/secoes`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(secoesData => {
                            if (secoesData.success && secoesData.secoes) {
                                // Criar seções primeiro
                                const secoes = secoesData.secoes;
                                const perguntasPorSecao = {};
                                console.log('Seções encontradas:', secoes);
                                console.log('Perguntas recebidas:', data.perguntas);

                                // Agrupar perguntas por seção
                                data.perguntas.forEach(pergunta => {
                                    const secaoId = pergunta.secao_id;
                                    console.log('Processando pergunta:', pergunta.pergunta, 'secao_id:', secaoId);
                                    if (!perguntasPorSecao[secaoId]) {
                                        perguntasPorSecao[secaoId] = [];
                                    }
                                    perguntasPorSecao[secaoId].push(pergunta);
                                });
                                console.log('Perguntas agrupadas por seção:', perguntasPorSecao);

                                // Criar seções com títulos e descrições originais
                                secoes.forEach((secao, index) => {
                                    secaoCounter++;
                                    const perguntas = perguntasPorSecao[secao.id] || [];
                                    console.log('Criando seção:', secao.titulo, 'com', perguntas.length, 'perguntas');

                                    const secaoHtml = `
                                        <div class="card mb-3 secao-item" id="secao-${secaoCounter}" data-secao="${secaoCounter}" data-secao-original="${secao.id}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                                    <i class="fas fa-grip-vertical text-muted"></i>
                                                    <input type="text" name="secoes[${secaoCounter}][titulo]" class="form-control form-control-sm"
                                                           value="${secao.titulo}" style="max-width: 320px;" required>
                                                </div>
                                                <div class="ml-auto">
                                                    <i class="fas fa-trash remove-secao" onclick="removerSecao(${secaoCounter})" title="Remover Seção"></i>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea name="secoes[${secaoCounter}][descricao]" class="form-control form-control-sm" rows="2"
                                                              placeholder="Descrição da seção (opcional)">${secao.descricao || ''}</textarea>
                                                </div>
                                                <div class="lista-perguntas" id="lista-perguntas-${secaoCounter}">
                                                    ${perguntas.map(pergunta => {
                                                        perguntaCounter++;
                                                        console.log('Criando pergunta na seção:', secao.titulo, 'pergunta:', pergunta.pergunta);
                                                        return criarHtmlPergunta(pergunta, perguntaCounter, false, secaoCounter);
                                                    }).join('')}
                                                </div>
                                                <div class="d-flex justify-content-start mt-3">
                                                    <button type="button" class="btn btn-success" onclick="adicionarPergunta(${secaoCounter})">
                                                        <i class="fas fa-plus"></i> Adicionar Pergunta
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                    container.insertAdjacentHTML('beforeend', secaoHtml);
                                    inicializarSortablePerguntas(`lista-perguntas-${secaoCounter}`);
                                });

                                // Criar seção para perguntas sem seção (se houver)
                                const perguntasSemSecao = perguntasPorSecao[null] || perguntasPorSecao[undefined] || [];
                                if (perguntasSemSecao.length > 0) {
                                    secaoCounter++;
                                    const secaoHtml = `
                                        <div class="card mb-3 secao-item" id="secao-${secaoCounter}" data-secao="${secaoCounter}">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                                    <i class="fas fa-grip-vertical text-muted"></i>
                                                    <input type="text" name="secoes[${secaoCounter}][titulo]" class="form-control form-control-sm"
                                                           value="Perguntas Gerais" style="max-width: 320px;" required>
                                                </div>
                                                <div class="ml-auto">
                                                    <i class="fas fa-trash remove-secao" onclick="removerSecao(${secaoCounter})" title="Remover Seção"></i>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <textarea name="secoes[${secaoCounter}][descricao]" class="form-control form-control-sm" rows="2"
                                                              placeholder="Descrição da seção (opcional)"></textarea>
                                                </div>
                                                <div class="lista-perguntas" id="lista-perguntas-${secaoCounter}">
                                                    ${perguntasSemSecao.map(pergunta => {
                                                        perguntaCounter++;
                                                        return criarHtmlPergunta(pergunta, perguntaCounter, false, secaoCounter);
                                                    }).join('')}
                                                </div>
                                                <div class="d-flex justify-content-start mt-3">
                                                    <button type="button" class="btn btn-success" onclick="adicionarPergunta(${secaoCounter})">
                                                        <i class="fas fa-plus"></i> Adicionar Pergunta
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `;

                                    container.insertAdjacentHTML('beforeend', secaoHtml);
                                    inicializarSortablePerguntas(`lista-perguntas-${secaoCounter}`);
                                }

                                atualizarOrdenacao();
                            } else {
                                throw new Error('Erro ao carregar seções do questionário base');
                            }
                        })
                        .catch(error => {
                            console.error('Erro ao carregar seções:', error);
                            // Fallback: criar seção única com todas as perguntas
                            secaoCounter++;
                            const secaoHtml = `
                                <div class="card mb-3 secao-item" id="secao-${secaoCounter}" data-secao="${secaoCounter}">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center flex-grow-1" style="gap: 10px;">
                                            <i class="fas fa-grip-vertical text-muted"></i>
                                            <input type="text" name="secoes[${secaoCounter}][titulo]" class="form-control form-control-sm"
                                                   value="Perguntas do Questionário" style="max-width: 320px;" required>
                                        </div>
                                        <div class="ml-auto">
                                            <i class="fas fa-trash remove-secao" onclick="removerSecao(${secaoCounter})" title="Remover Seção"></i>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <textarea name="secoes[${secaoCounter}][descricao]" class="form-control form-control-sm" rows="2"
                                                      placeholder="Descrição da seção (opcional)"></textarea>
                                        </div>
                                        <div class="lista-perguntas" id="lista-perguntas-${secaoCounter}">
                                            ${data.perguntas.map(pergunta => {
                                                perguntaCounter++;
                                                return criarHtmlPergunta(pergunta, perguntaCounter, false, secaoCounter);
                                            }).join('')}
                                        </div>
                                        <div class="d-flex justify-content-start mt-3">
                                            <button type="button" class="btn btn-sm btn-success" onclick="adicionarPergunta(${secaoCounter})">
                                                <i class="fas fa-plus"></i> Adicionar Pergunta
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;

                            container.insertAdjacentHTML('beforeend', secaoHtml);
                            inicializarSortablePerguntas(`lista-perguntas-${secaoCounter}`);
                            atualizarOrdenacao();
                        });

                        atualizarOrdenacao();
                    } else {
                        throw new Error('Dados inválidos recebidos do servidor');
                    }
                })
                .catch(error => {
                    console.error('Erro ao carregar perguntas:', error);
                    alert('Erro ao carregar as perguntas do questionário selecionado. Verifique o console para mais detalhes.');
                });
            }

            function criarHtmlPergunta(pergunta, numero, personalizada, secaoNumero) {
                console.log('Criando HTML para pergunta:', pergunta, 'numero:', numero, 'personalizada:', personalizada, 'secao:', secaoNumero);
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
                                <div>
                                    <i class="fas fa-eye ocultar-pergunta" onclick="ocultarPergunta(${numero})" title="Ocultar Pergunta" style="color: #6c757d; cursor: pointer;"></i>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" ${pergunta.obrigatoria ? 'checked' : ''} disabled>
                                            Pergunta obrigatória
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Formato de Validação</label>
                                        <input type="text" class="form-control"
                                               value="${pergunta.formato_validacao ? pergunta.formato_validacao.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Texto Comum'}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>
                                            <input type="radio" name="pergunta_identificadora" value="${pergunta.id}" id="identificador_${pergunta.id}">
                                            <span class="text-primary"><i class="fas fa-user"></i> Usar como identificador</span>
                                        </label>
                                        <small class="form-text text-muted">Marque para usar esta pergunta como identificador do respondente.</small>
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
                            <input type="hidden" name="perguntas[${numero}][oculta]" value="0" class="input-oculta">

                            <!-- Debug: Campo de identificador -->
                            <div class="mt-3 p-2 bg-light border rounded">
                                <small class="text-muted">Debug: Campo identificador para pergunta ID ${pergunta.id}</small>
                            </div>
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
                            </div>
                            <div>
                                <i class="fas fa-trash remove-pergunta" onclick="removerPergunta(${numero})" title="Remover" style="color: #dc3545; cursor: pointer;"></i>
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
                        <input type="hidden" name="perguntas[${numero}][secao_id]" value="${secaoNumero}">
                        <input type="hidden" name="perguntas[${numero}][ordem]" class="input-ordem" value="0">
                        <input type="hidden" name="perguntas[${numero}][oculta]" value="0" class="input-oculta">
                    </div>
                `;
            }

            function adicionarPergunta(secaoNumero) {
                perguntaCounter++;
                const container = document.getElementById(`lista-perguntas-${secaoNumero}`);

                if (!container) {
                    console.error(`Container de perguntas para seção ${secaoNumero} não encontrado`);
                    return;
                }

                const perguntaVazia = {
                    pergunta: '',
                    tipo: '',
                    obrigatoria: false,
                    formato_validacao: 'texto_comum',
                    opcoes: []
                };

                const perguntaHtml = criarHtmlPergunta(perguntaVazia, perguntaCounter, true, secaoNumero);
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

            function ocultarPergunta(numero) {
                const pergunta = document.getElementById(`pergunta-${numero}`);
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
                                <button type="button" class="btn btn-sm btn-success" onclick="adicionarPergunta(${secaoCounter})">
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
