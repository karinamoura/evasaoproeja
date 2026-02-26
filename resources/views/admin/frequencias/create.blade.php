<x-admin>
    @section('title', 'Registrar Frequência')

    <div class="card">
        <div class="card-header bg-success">
            <h3 class="card-title text-white">
                <i class="fas fa-clipboard-check me-2"></i>Registrar Frequência - {{ $disciplina->nome }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.frequencia.index') }}" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body">
            <!-- Informações da Disciplina -->
            <div class="alert alert-info mb-4">
                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i> Informações da Disciplina</h5>
                <div class="row">
                    <div class="col-md-6 col-12 mb-2">
                        <strong>Oferta:</strong> {{ $disciplina->oferta->name ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <strong>Período:</strong> {{ $disciplina->periodo }}
                    </div>
                    <div class="col-md-6 col-12 mb-2">
                        <strong>Carga Horária Total:</strong> {{ $disciplina->carga_horaria_total }} h/aula
                    </div>
                    @if($disciplina->data_inicio && $disciplina->data_fim)
                        <div class="col-md-6 col-12 mb-2">
                            <strong>Data de Início:</strong> {{ $disciplina->data_inicio->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6 col-12 mb-0">
                            <strong>Data de Término:</strong> {{ $disciplina->data_fim->format('d/m/Y') }}
                        </div>
                    @else
                        <div class="col-12 mb-0">
                            <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> Datas de início e término não definidas</span>
                        </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.frequencia.store') }}" method="POST" id="frequenciaForm">
                @csrf
                <input type="hidden" name="disciplina_id" value="{{ $disciplina->id }}">

                <!-- Campos Gerais -->
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-default text-white">
                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i> Dados da Aula</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <label for="data_aula_global" class="form-label">
                                    <i class="fas fa-calendar me-1"></i> Data da Aula <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="data_aula_global"
                                       id="data_aula_global"
                                       class="form-control form-control-lg"
                                       value="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <label for="carga_horaria_geral" class="form-label">
                                    <i class="fas fa-clock me-1"></i> Carga Horária do Dia (h/aula) <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="carga_horaria_geral"
                                       id="carga_horaria_geral"
                                       class="form-control form-control-lg"
                                       min="0"
                                       step="0.5"
                                       value="2"
                                       placeholder="Ex: 2"
                                       required>
                                <small class="text-muted">Esta carga horária será aplicada a todos os estudantes presentes</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Estudantes -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-3"><i class="fas fa-users me-2"></i> Estudantes</h5>
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <button type="button" class="btn btn-sm btn-success mr-1" id="selecionarTodos">
                                <i class="fas fa-check-square me-1"></i>Marcar Todos
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" id="desmarcarTodos">
                                <i class="fas fa-square me-1"></i>Desmarcar Todos
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush" id="listaEstudantes">
                            @foreach($estudantesComFrequencia as $index => $item)
                                <div class="list-group-item estudante-item {{ $item['status'] == 'risco' ? 'border-left-danger' : ($item['status'] == 'atencao' ? 'border-left-warning' : 'border-left-success') }}"
                                     data-estudante-id="{{ $item['estudante']->id }}">
                                    <div class="d-flex align-items-start">
                                        <!-- Checkbox -->
                                        <div class="form-check">
                                            <input class="form-check-input estudante-checkbox"
                                                   type="checkbox"
                                                   id="estudante_{{ $item['estudante']->id }}"
                                                   checked
                                                   data-estudante-index="{{ $index }}">
                                            <input type="hidden" name="frequencias[{{ $index }}][estudante_id]" value="{{ $item['estudante']->id }}">
                                        </div>

                                        <!-- Informações do Estudante -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap">
                                                <div class="mb-2 mb-md-0 flex-grow-1">
                                                    <h6 class="mb-1">
                                                        <strong>{{ $item['estudante']->nome }}</strong>
                                                        @if($item['status'] == 'risco')
                                                            <span class="badge bg-danger ms-2">Risco de Evasão</span>
                                                        @elseif($item['status'] == 'atencao')
                                                            <span class="badge bg-warning ms-2">Atenção</span>
                                                        @endif
                                                    </h6>
                                                    <div class="frequencia-info" data-estudante-id="{{ $item['estudante']->id }}" data-frequencia-atual="{{ $item['frequencia_total'] }}" data-carga-total="{{ $item['carga_horaria_total'] }}">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="fas fa-chart-line me-1"></i>
                                                            <span class="frequencia-texto">
                                                                Frequência atual: <strong>{{ $item['frequencia_total'] }}</strong>
                                                                / {{ $item['carga_horaria_total'] }} h/aula
                                                                (<span class="percentual-atual">{{ $item['percentual'] }}</span>%)
                                                            </span>
                                                        </small>
                                                        <small class="text-muted d-block frequencia-futura" style="display: none !important;">
                                                            <i class="fas fa-arrow-right me-1 text-success"></i>
                                                            Após registro: <strong class="frequencia-total-futura">{{ $item['frequencia_total'] }}</strong> h/aula
                                                            (<span class="percentual-futuro">{{ $item['percentual'] }}</span>%)
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="ms-2">
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-info btn-observacoes"
                                                            data-estudante-id="{{ $item['estudante']->id }}"
                                                            data-estudante-nome="{{ $item['estudante']->nome }}"
                                                            data-estudante-index="{{ $index }}"
                                                            data-toggle="modal"
                                                            data-target="#modalObservacoes">
                                                        <i class="fas fa-comment me-1"></i>Observações
                                                    </button>
                                                    <input type="hidden"
                                                           name="frequencias[{{ $index }}][observacoes]"
                                                           id="observacoes_hidden_{{ $item['estudante']->id }}"
                                                           value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-md-row justify-content-end gap-2">
                                <a href="{{ route('admin.frequencia.index') }}" class="btn btn-secondary mr-1">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Registrar Frequência
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Observações -->
    <div class="modal fade" id="modalObservacoes" tabindex="-1" aria-labelledby="modalObservacoesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="modalObservacoesLabel">
                        <i class="fas fa-comment me-2"></i>Observações
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        <strong>Estudante:</strong> <span id="modalEstudanteNome"></span>
                    </p>
                    <div class="mb-3">
                        <label for="modalObservacoesTexto" class="form-label">
                            <i class="fas fa-edit me-1"></i>Adicione uma observação sobre este estudante:
                        </label>
                        <textarea class="form-control"
                                  id="modalObservacoesTexto"
                                  rows="4"
                                  placeholder="Digite suas observações aqui..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success w-100 w-md-auto" id="salvarObservacoes">
                        <i class="fas fa-save me-1"></i>Salvar Observações
                    </button>
                </div>
            </div>
        </div>
    </div>

    @section('css')
    <style>
        /* Estilos para mobile-first */
        .estudante-item {
            border-left: 4px solid #10b981 !important;
            transition: all 0.3s ease;
            padding: 1rem !important;
        }

        .estudante-item.border-left-danger {
            border-left-color: #ef4444 !important;
        }

        .estudante-item.border-left-warning {
            border-left-color: #f59e0b !important;
        }

        .estudante-item.border-left-success {
            border-left-color: #10b981 !important;
        }

        .estudante-item:hover {
            background-color: #f8fafc;
            transform: translateX(2px);
        }

        .estudante-item:not(:last-child) {
            border-bottom: 1px solid #e2e8f0;
        }

        .estudante-item .d-flex {
            align-items: flex-start !important;
            gap: 0.75rem;
        }

        .form-check {
            flex-shrink: 0;
            padding-left: 0;
            margin: 0;
            display: flex;
            align-items: center;
            min-width: 24px;
        }

        .form-check-input {
            width: 24px !important;
            height: 24px !important;
            cursor: pointer;
            margin: 0 !important;
            flex-shrink: 0;
            position: relative;
            float: none;
        }

        .estudante-item .flex-grow-1 {
            min-width: 0;
            flex: 1 1 auto;
            overflow: hidden;
        }

        .frequencia-info {
            line-height: 1.6;
        }

        .registrando-info {
            font-weight: 500;
        }

        .registrando-info strong {
            color: #10b981;
            font-size: 1.05em;
        }

        .frequencia-futura {
            margin-top: 0.25rem;
            padding-top: 0.25rem;
            border-top: 1px dashed #e2e8f0;
        }

        .frequencia-futura strong {
            color: #10b981;
        }

        .frequencia-futura.hidden {
            display: none !important;
        }

        .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }

        .form-check-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
        }

        .list-group-item {
            border: none;
            border-radius: 0;
        }

        .card-header.bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        .card-header.bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .card-header h5 {
                font-size: 1rem;
            }

            .btn-lg {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .estudante-item {
                padding: 0.75rem !important;
            }

            .estudante-item .d-flex.justify-content-between {
                flex-direction: column;
                gap: 0.5rem;
            }

            .estudante-item .btn-observacoes {
                width: 100%;
                margin-top: 0.5rem;
            }

            .form-check {
                margin-right: 0.75rem;
            }

            .badge {
                font-size: 0.7rem;
                margin-top: 0.25rem;
            }

            #selecionarTodos,
            #desmarcarTodos {
                width: 100%;
            }

            .modal-footer {
                flex-direction: column;
            }

            .modal-footer .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .modal-footer .btn:last-child {
                margin-bottom: 0;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1rem;
            }

            .alert {
                padding: 0.75rem;
            }

            .form-control-lg {
                font-size: 0.9rem;
            }
        }
    </style>
    @endsection

    @section('js')
    <script>
        $(document).ready(function() {
            const cargaHorariaGeral = $('#carga_horaria_geral');
            const checkboxes = $('.estudante-checkbox');

            // Selecionar todos
            $('#selecionarTodos').on('click', function() {
                checkboxes.prop('checked', true);
                atualizarFrequenciaInfo();
            });

            // Desmarcar todos
            $('#desmarcarTodos').on('click', function() {
                checkboxes.prop('checked', false);
                atualizarFrequenciaInfo();
            });

            // Gerenciar modal de observações
            let estudanteAtualIndex = null;
            let estudanteAtualId = null;

            $('.btn-observacoes').on('click', function() {
                estudanteAtualIndex = $(this).data('estudante-index');
                estudanteAtualId = $(this).data('estudante-id');
                const estudanteNome = $(this).data('estudante-nome');

                $('#modalEstudanteNome').text(estudanteNome);

                // Carregar observações existentes se houver
                const observacoesExistentes = $(`#observacoes_hidden_${estudanteAtualId}`).val();
                $('#modalObservacoesTexto').val(observacoesExistentes || '');
            });

            // Salvar observações no modal
            $('#salvarObservacoes').on('click', function() {
                const observacoesTexto = $('#modalObservacoesTexto').val();

                if (estudanteAtualId) {
                    $(`#observacoes_hidden_${estudanteAtualId}`).val(observacoesTexto);

                    // Atualizar botão para indicar que há observações
                    const btnObservacoes = $(`.btn-observacoes[data-estudante-id="${estudanteAtualId}"]`);
                    if (observacoesTexto.trim()) {
                        btnObservacoes.removeClass('btn-outline-info').addClass('btn-info');
                        btnObservacoes.html('<i class="fas fa-comment-dots me-1"></i>Observações <span class="badge bg-light text-dark">✓</span>');
                    } else {
                        btnObservacoes.removeClass('btn-info').addClass('btn-outline-info');
                        btnObservacoes.html('<i class="fas fa-comment me-1"></i>Observações');
                    }
                }

                // Fechar modal
                $('#modalObservacoes').modal('hide');
            });

            // Limpar dados quando modal for fechado (Bootstrap 4)
            $('#modalObservacoes').on('hidden.bs.modal', function() {
                estudanteAtualIndex = null;
                estudanteAtualId = null;
                $('#modalObservacoesTexto').val('');
            });

            // Preparar dados antes de enviar
            $('#frequenciaForm').on('submit', function(e) {
                const dataAula = $('#data_aula_global').val();
                const cargaHoraria = parseFloat(cargaHorariaGeral.val());
                const peloMenosUmMarcado = checkboxes.filter(':checked').length > 0;

                if (!dataAula) {
                    e.preventDefault();
                    alert('Por favor, selecione a data da aula.');
                    return false;
                }

                if (!cargaHoraria || cargaHoraria <= 0) {
                    e.preventDefault();
                    alert('Por favor, informe a carga horária do dia (deve ser maior que 0).');
                    cargaHorariaGeral.focus();
                    return false;
                }

                // Preparar dados para envio - processar TODOS os estudantes
                checkboxes.each(function() {
                    const checkbox = $(this);
                    const index = checkbox.data('estudante-index');
                    const estudanteItem = checkbox.closest('.estudante-item');
                    const estudanteId = estudanteItem.data('estudante-id');

                    // Remover campos hora_aula existentes
                    estudanteItem.find('input[name*="[hora_aula]"]').remove();

                    // Adicionar campo hidden com a carga horária correta
                    let horasAula = 0;
                    if (checkbox.is(':checked')) {
                        horasAula = cargaHoraria;
                    }

                    // Criar input hidden para hora_aula
                    const horaAulaInput = $('<input>', {
                        type: 'hidden',
                        name: `frequencias[${index}][hora_aula]`,
                        value: horasAula
                    });

                    estudanteItem.append(horaAulaInput);

                    // Garantir que as observações sejam enviadas
                    const observacoes = $(`#observacoes_hidden_${estudanteId}`).val();
                    $(`input[name="frequencias[${index}][observacoes]"]`).val(observacoes || '');
                });
            });

            // Função para atualizar informações de frequência
            function atualizarFrequenciaInfo() {
                const cargaHoraria = parseFloat(cargaHorariaGeral.val()) || 0;

                $('.estudante-checkbox').each(function() {
                    const checkbox = $(this);
                    const estudanteItem = checkbox.closest('.estudante-item');
                    const frequenciaInfo = estudanteItem.find('.frequencia-info');

                    if (frequenciaInfo.length === 0) return;

                    const frequenciaAtual = parseFloat(frequenciaInfo.data('frequencia-atual')) || 0;
                    const cargaTotal = parseFloat(frequenciaInfo.data('carga-total')) || 1;

                    const frequenciaFutura = frequenciaInfo.find('.frequencia-futura');

                    if (frequenciaFutura.length === 0) return;

                    const frequenciaTotalFutura = frequenciaInfo.find('.frequencia-total-futura');
                    const percentualAtual = frequenciaInfo.find('.percentual-atual');
                    const percentualFuturo = frequenciaInfo.find('.percentual-futuro');

                    // Calcular percentual atual
                    const percentualAtualCalc = cargaTotal > 0 ? ((frequenciaAtual / cargaTotal) * 100).toFixed(1) : '0.0';
                    if (percentualAtual.length > 0) {
                        percentualAtual.text(percentualAtualCalc);
                    }

                    // Verificar se checkbox está marcado E se há carga horária
                    const estaMarcado = checkbox.is(':checked');
                    const temCargaHoraria = cargaHoraria > 0;

                    if (estaMarcado && temCargaHoraria) {
                        const novaFrequencia = frequenciaAtual + cargaHoraria;
                        const novoPercentual = cargaTotal > 0 ? ((novaFrequencia / cargaTotal) * 100).toFixed(1) : '0.0';

                        if (frequenciaTotalFutura.length > 0) {
                            frequenciaTotalFutura.text(novaFrequencia);
                        }
                        if (percentualFuturo.length > 0) {
                            percentualFuturo.text(novoPercentual);
                        }
                        frequenciaFutura.removeClass('hidden');
                        frequenciaFutura.attr('style', 'display: block !important;');
                    } else {
                        frequenciaFutura.addClass('hidden');
                        frequenciaFutura.attr('style', 'display: none !important;');
                    }
                });
            }

            // Atualizar quando checkbox mudar
            $(document).on('change', '.estudante-checkbox', function() {
                const checkbox = $(this);
                const estudanteItem = checkbox.closest('.estudante-item');
                const frequenciaInfo = estudanteItem.find('.frequencia-info');
                const frequenciaFutura = frequenciaInfo.find('.frequencia-futura');
                const cargaHoraria = parseFloat(cargaHorariaGeral.val()) || 0;

                if (frequenciaFutura.length > 0) {
                    if (checkbox.is(':checked') && cargaHoraria > 0) {
                        frequenciaFutura.removeClass('hidden');
                        frequenciaFutura.attr('style', 'display: block !important;');
                    } else {
                        frequenciaFutura.addClass('hidden');
                        frequenciaFutura.attr('style', 'display: none !important;');
                    }
                }

                atualizarFrequenciaInfo();
            });

            // Atualizar quando a carga horária mudar
            cargaHorariaGeral.on('change input', function() {
                const valor = parseFloat($(this).val()) || 0;
                if (valor <= 0) {
                    $(this).val(2);
                }
                atualizarFrequenciaInfo();
            });

            // Atualizar ao carregar a página
            atualizarFrequenciaInfo();
        });
    </script>
    @endsection
</x-admin>
