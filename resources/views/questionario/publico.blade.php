<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $questionarioOferta->titulo }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        @php
        // Definir cor principal (personalizada ou padrão)
        $corPrincipal = $questionarioOferta->cor_personalizada ?? '#667eea';

        // Gerar cor secundária baseada na principal
        if ($questionarioOferta->cor_personalizada) {
            $hex = str_replace('#', '', $questionarioOferta->cor_personalizada);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));

            // Escurecer a cor para criar a secundária
            $r = max(0, $r - 30);
            $g = max(0, $g - 30);
            $b = max(0, $b - 30);

            $corSecundaria = sprintf('#%02x%02x%02x', $r, $g, $b);
        } else {
            $corSecundaria = '#764ba2';
            $hex = '667eea';
            $r = 102;
            $g = 126;
            $b = 234;
        }

        // Se não tiver calculado ainda, calcular RGB da cor principal
        if (!isset($r)) {
            $hex = str_replace('#', '', $corPrincipal);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
    @endphp

    <style>
        :root {
            --cor-principal: {{ $corPrincipal }};
            --cor-secundaria: {{ $corSecundaria }};
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.02) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba({{ $r }}, {{ $g }}, {{ $b }}, 0.015) 0%, transparent 40%),
                linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
            z-index: 0;
            pointer-events: none;
        }

        .questionario-container {
            position: relative;
            z-index: 1;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            margin: 2rem auto;
            max-width: 800px;
            overflow: hidden;
        }

        .questionario-header {
            background: white;
            color: #333;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
            border-bottom: 4px solid var(--cor-principal);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .questionario-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--cor-principal);
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .questionario-header p {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 1.5rem;
            font-weight: 400;
        }

        .questionario-header small {
            color: #6a6767;
            font-size: 0.95rem;
            display: block;
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            background: #eef0f3;
            border-radius: 25px;
        }

        .questionario-body {
            padding: 2rem;
        }

        .pergunta-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            margin-bottom: 2rem;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .pergunta-item:hover {
            border-color: var(--cor-principal);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        }

        .pergunta-numero {
            background: var(--cor-principal);
            border-radius: 50%;
            color: white;
            display: inline-block;
            font-weight: bold;
            height: 40px;
            line-height: 40px;
            margin-right: 1rem;
            text-align: center;
            width: 40px;
        }

        .pergunta-texto {
            font-size: 1.2rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .pergunta-obrigatoria {
            color: #dc3545;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .opcoes-container {
            margin-top: 1rem;
        }

        .opcao-item {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .opcao-item:hover {
            border-color: var(--cor-principal);
            background: #f8f9ff;
        }

        .opcao-item input[type="radio"],
        .opcao-item input[type="checkbox"] {
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .opcao-item label {
            cursor: pointer;
            font-weight: 500;
            margin: 0;
            flex: 1;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--cor-principal);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .campo-validacao.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .campo-validacao.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .text-danger {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-enviar {
            background: linear-gradient(135deg, var(--cor-principal) 0%, var(--cor-secundaria) 100%);
            border: none;
            border-radius: 25px;
            color: white;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 1rem 3rem;
            transition: all 0.3s ease;
        }

        .btn-enviar:hover {
            background: linear-gradient(135deg, var(--cor-principal) 0%, var(--cor-secundaria) 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .alert {
            border-radius: 15px;
            border: none;
        }

        .progress-bar {
            background: linear-gradient(135deg, var(--cor-principal) 0%, var(--cor-secundaria) 100%);
            border-radius: 10px;
        }

        .progress {
            background: #e9ecef;
            border-radius: 10px;
            height: 10px;
            margin-bottom: 2rem;
        }

        .footer-info {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 0.9rem;
            padding: 1rem 2rem;
            text-align: center;
        }

        /* Estilos para seções */
        .secao-container {
            display: none;
            animation: fadeIn 0.5s ease-in-out;
        }

        .secao-container.ativa {
            display: block;
        }

        .secao-header {
            background: linear-gradient(135deg, var(--cor-principal) 0%, var(--cor-secundaria) 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }

        .secao-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 500;
        }

        .secao-header .secao-descricao {
            margin-top: 0.5rem;
            opacity: 0.9;
            font-size: 1rem;
        }

        .secao-indicador {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* Navegação entre seções */
        .navegacao-secoes {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0;
            padding: 1rem 0;
            border-top: 2px solid #e9ecef;
            border-bottom: 2px solid #e9ecef;
        }

        .btn-navegacao {
            background: linear-gradient(135deg, var(--cor-principal) 0%, var(--cor-secundaria) 100%);
            border: none;
            border-radius: 25px;
            color: white;
            font-weight: 500;
            padding: 0.75rem 2rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-navegacao:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-navegacao:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-navegacao.anterior {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .btn-navegacao.anterior:hover {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .indicador-secao {
            background: var(--cor-principal);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        @keyframes slideOut {
            from { transform: translateX(0); }
            to { transform: translateX(-100%); }
        }

        /* Indicadores de seção no topo */
        .indicadores-secoes-topo {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .indicador-secao-topo {
            background: #e9ecef;
            color: #6c757d;
            border: 2px solid transparent;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicador-secao-topo.ativa {
            background: var(--cor-principal);
            color: white;
            border-color: var(--cor-principal);
        }

        .indicador-secao-topo.completa {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .indicador-secao-topo.desabilitada {
            background: #e9ecef;
            color: #6c757d;
            border-color: #dee2e6;
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Estilos para o termo de aceite */
        .termo-aceite-container {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .termo-texto {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #495057;
        }

        .termo-texto h5 {
            color: var(--cor-principal);
            font-weight: 600;
        }

        .termo-texto p {
            margin-bottom: 0.75rem;
        }

        .termo-texto ul {
            margin-left: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .termo-texto li {
            margin-bottom: 0.25rem;
        }

        .aceite-container {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
        }

        .form-check-input:checked {
            background-color: var(--cor-principal);
            border-color: var(--cor-principal);
        }

        .form-check-label {
            font-size: 1rem;
            color: #495057;
            cursor: pointer;
        }

        .btn-navegacao:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 768px) {
            .questionario-container {
                margin: 1rem;
                border-radius: 15px;
            }

            .questionario-header {
                padding: 1.5rem;
            }

            .questionario-header h1 {
                font-size: 2rem;
            }

            .questionario-body {
                padding: 1.5rem;
            }

            .pergunta-item {
                padding: 1rem;
            }

            .navegacao-secoes {
                flex-direction: column;
                gap: 1rem;
            }

            .indicadores-secoes-topo {
                gap: 0.25rem;
            }

            .indicador-secao-topo {
                font-size: 0.7rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Estilos para o modal de sucesso */
        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header.bg-success {
            border-radius: 15px 15px 0 0;
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            border-top: none;
            padding: 1rem 2rem 2rem;
        }

        .btn-enviar {
            background: linear-gradient(135deg, var(--cor-principal) 0%, var(--cor-secundaria) 100%);
            border: none;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-enviar:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn-enviar:disabled {
            opacity: 0.7;
            transform: none;
        }

        /* Garantir que backdrops extras sejam removidos */
        .modal-backdrop {
            z-index: 1040;
        }

        .modal-backdrop.show {
            opacity: 0.5;
        }

        /* Remover backdrops duplicados */
        .modal-backdrop:not(:first-of-type) {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="questionario-container">
            <!-- Header do Questionário -->
            <div class="questionario-header">
                <h1><i class="fas fa-clipboard-list me-3" style="color: var(--cor-secundaria);"></i>{{ $questionarioOferta->titulo }}</h1>
                @if($questionarioOferta->descricao)
                    <p>{{ $questionarioOferta->descricao }}</p>
                @endif
                <div class="mt-3">
                    <small>
                        <i class="fas fa-building me-2" style="color: var(--cor-principal);"></i>{{ $questionarioOferta->oferta->institution->name }} -
                        <i class="fas fa-graduation-cap me-2" style="color: var(--cor-principal);"></i>{{ $questionarioOferta->oferta->name }}
                    </small>
                </div>
            </div>

            <!-- Corpo do Questionário -->
            <div class="questionario-body">
                <!-- Mensagem de sucesso (oculta inicialmente) -->
                <div id="mensagemSucesso" style="display: none;">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="text-success mb-3">Questionário Enviado com Sucesso!</h2>
                        <p class="text-muted mb-4" style="font-size: 1.1rem;">
                            Sua resposta foi registrada com sucesso.<br>
                            Obrigado por participar!
                        </p>
                        <div class="alert alert-info d-inline-block">
                            <i class="fas fa-info-circle me-2"></i>
                            Você não precisa preencher o formulário novamente.
                        </div>
                    </div>
                </div>

                <!-- Formulário (visível inicialmente) -->
                <div id="formularioContainer">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Erro!</strong> Por favor, verifique os campos obrigatórios.
                    </div>
                @endif

                <!-- Debug Info -->
                @if(false)
                    <div class="alert alert-info">
                        <strong>Debug:</strong>
                        Total de perguntas: {{ $questionarioOferta->perguntasOrdenadas->count() }}<br>
                        Perguntas IDs: {{ $questionarioOferta->perguntasOrdenadas->pluck('id')->implode(', ') }}<br>
                        <strong>Detalhes das perguntas:</strong><br>
                        @foreach($questionarioOferta->perguntasOrdenadas as $p)
                            - ID: {{ $p->id }}, Ordem: {{ $p->ordem }}, Pergunta: {{ Str::limit($p->pergunta, 50) }}, Tipo: {{ $p->tipo }}, Formato: {{ $p->formato_validacao ?? 'NULO' }}<br>
                        @endforeach
                        <br>
                        <strong>Verificação de duplicatas:</strong><br>
                        @php
                            $ids = $questionarioOferta->perguntasOrdenadas->pluck('id')->toArray();
                            $duplicatas = array_diff_assoc($ids, array_unique($ids));
                        @endphp
                        @if(count($duplicatas) > 0)
                            <span style="red;">DUPLICATAS ENCONTRADAS: {{ implode(', ', $duplicatas) }}</span>
                        @else
                            <span style="color: green;">Nenhuma duplicata encontrada</span>
                        @endif
                    </div>
                @endif

                <!-- Indicadores de Seções no Topo -->
                <div class="indicadores-secoes-topo" id="indicadoresSecoesTopo">
                    <!-- Serão preenchidos via JavaScript -->
                </div>

                <!-- Barra de Progresso -->
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                </div>
                <div class="text-center mb-4">
                    <small class="text-muted" id="progressText">0 de {{ $questionarioOferta->perguntasOrdenadas->count() }} perguntas respondidas</small>
                </div>

                <form action="{{ route('questionario.resposta.store', $questionarioOferta->url_publica) }}" method="POST" id="questionarioForm">
                    @csrf

                    <!-- Modal de Sucesso -->
                    <div class="modal fade" id="modalSucesso" tabindex="-1" aria-labelledby="modalSucessoLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="modalSucessoLabel">
                                        <i class="fas fa-check-circle me-2"></i>Sucesso!
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-clipboard-check text-success" style="font-size: 3rem;"></i>
                                    </div>
                                    <h4 class="text-success mb-3">Questionário Enviado!</h4>
                                    <p class="text-muted">Sua resposta foi registrada com sucesso. Obrigado por participar!</p>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="button" class="btn btn-success btn-lg px-4" data-bs-dismiss="modal">
                                        <i class="fas fa-thumbs-up me-2"></i>Entendi!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Perguntas do Questionário organizadas por seções -->
                    @php
                        $perguntaCount = 0;
                        $secoes = $questionarioOferta->secoes()->orderBy('ordem')->get();
                        $perguntasSemSecao = $questionarioOferta->perguntas()->whereNull('secao_oferta_id')->orderBy('ordem')->get();
                        $totalSecoes = 1 + $secoes->count() + ($perguntasSemSecao->count() > 0 ? 1 : 0) + 1; // Termos + Seções + Perguntas sem seção + Final
                    @endphp

                    <!-- Seção de Termo de Aceite -->
                    <div class="secao-container ativa" id="secao-0" data-secao="0">
                        <div class="secao-header">
                            <div class="secao-indicador">1</div>
                            <h3><i class="fas fa-file-contract me-2"></i>Termos e Condições</h3>
                            <div class="secao-descricao">Leia e aceite os termos para continuar com o questionário</div>
                        </div>

                                                <!-- Termo de Aceite -->
                        <div class="pergunta-item">
                            <div class="d-flex align-items-center mb-3">
                                <span class="pergunta-numero"><i class="fas fa-gavel"></i></span>
                                <div class="flex-grow-1">
                                    <div class="pergunta-texto">Termos e Condições do Questionário</div>
                                    <div class="pergunta-obrigatoria">* Obrigatório</div>
                                </div>
                            </div>

                            <!-- Área do termo de aceite -->
                            <div class="termo-aceite-container">
                                <div class="termo-texto">
                                    @if($questionarioOferta->termoCondicao)
                                        {!! $questionarioOferta->termoCondicao->conteudo !!}
                                    @else
                                        <h5 class="mb-3">Termos de Participação e Uso de Dados</h5>

                                        <p><strong>1. Objetivo do Questionário:</strong></p>
                                        <p>Este questionário tem como objetivo coletar informações para fins acadêmicos e de pesquisa, visando melhorar a qualidade dos serviços educacionais oferecidos.</p>

                                        <p><strong>2. Confidencialidade:</strong></p>
                                        <p>Todas as informações fornecidas serão tratadas com total confidencialidade e utilizadas exclusivamente para fins acadêmicos e de pesquisa. Seus dados pessoais não serão divulgados ou compartilhados com terceiros sem seu consentimento expresso.</p>

                                        <p><strong>3. Voluntariedade:</strong></p>
                                        <p>Sua participação é totalmente voluntária. Você pode recusar-se a participar ou retirar seu consentimento a qualquer momento, sem prejuízos.</p>

                                        <p><strong>4. Uso dos Dados:</strong></p>
                                        <p>Os dados coletados serão utilizados para:</p>
                                        <ul>
                                            <li>Análise acadêmica e de pesquisa</li>
                                            <li>Melhoria dos serviços educacionais</li>
                                            <li>Geração de relatórios institucionais</li>
                                            <li>Desenvolvimento de políticas educacionais</li>
                                        </ul>

                                        <p><strong>5. Armazenamento:</strong></p>
                                        <p>Os dados serão armazenados de forma segura e mantidos pelo período necessário para cumprir os objetivos da pesquisa, respeitando as diretrizes de proteção de dados.</p>

                                        <p><strong>6. Direitos do Participante:</strong></p>
                                        <p>Você tem o direito de:</p>
                                        <ul>
                                            <li>Acessar seus dados</li>
                                            <li>Corrigir informações incorretas</li>
                                            <li>Solicitar a exclusão de seus dados</li>
                                            <li>Retirar o consentimento a qualquer momento</li>
                                        </ul>

                                        <p><strong>7. Contato:</strong></p>
                                        <p>Para dúvidas sobre este questionário ou sobre o uso de seus dados, entre em contato através dos canais oficiais da instituição.</p>

                                        <p class="text-muted mt-3"><small><em>Última atualização: {{ date('d/m/Y') }}</em></small></p>
                                    @endif
                                </div>

                                <!-- Checkbox de aceite -->
                                <div class="aceite-container mt-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="aceite_termos" name="aceite_termos" value="1" required {{ old('aceite_termos') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="aceite_termos">
                                            <strong>Li e aceito os termos e condições acima</strong>
                                        </label>
                                    </div>
                                    @error('aceite_termos')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>



                        <!-- Navegação da primeira seção -->
                        <div class="navegacao-secoes">
                            <div class="indicador-secao">Seção 1 de {{ $totalSecoes }}</div>
                            <button type="button" class="btn btn-navegacao" onclick="proximaSecao()" id="btn-proximo" disabled>
                                Próximo <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    @php $secaoIndex = 1; @endphp
                    @foreach($secoes as $secao)
                        <div class="secao-container" id="secao-{{ $secaoIndex }}" data-secao="{{ $secaoIndex }}">
                            <div class="secao-header">
                                <div class="secao-indicador">{{ $secaoIndex + 1 }}</div>
                                <h3><i class="fas fa-list me-2"></i>{{ $secao->titulo }}</h3>
                                @if($secao->descricao)
                                    <div class="secao-descricao">{{ $secao->descricao }}</div>
                                @endif
                            </div>

                                                        @foreach($secao->perguntas as $pergunta)
                                @php $perguntaCount++; @endphp
                                <div class="pergunta-item" data-pergunta="{{ $perguntaCount }}">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="pergunta-numero">{{ $perguntaCount }}</span>
                                        <div class="flex-grow-1">
                                            <div class="pergunta-texto">{{ $pergunta->pergunta }}</div>
                                            @if($pergunta->obrigatoria)
                                                <div class="pergunta-obrigatoria">* Obrigatório</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="opcoes-container">
                                        @switch($pergunta->tipo)
                                        @case('texto_simples')
                                            @php
                                                $formato = $pergunta->formato_validacao ?? 'texto_comum';
                                                $inputType = 'text';
                                                $placeholder = 'Digite sua resposta';

                                                switch($formato) {
                                                    case 'email':
                                                        $inputType = 'email';
                                                        $placeholder = 'Digite seu e-mail (ex: usuario@email.com)';
                                                        break;
                                                    case 'data':
                                                        $inputType = 'date';
                                                        $placeholder = 'Selecione a data';
                                                        break;
                                                    case 'cpf':
                                                        $placeholder = 'Digite o CPF (ex: 123.456.789-00)';
                                                        break;
                                                    case 'telefone':
                                                        $placeholder = 'Digite o telefone (ex: (11) 99999-9999)';
                                                        break;
                                                    case 'numero':
                                                        $inputType = 'text';
                                                        $placeholder = 'Digite apenas números';
                                                        break;
                                                    default:
                                                        $placeholder = 'Digite sua resposta';
                                                }
                                            @endphp

                                            <input type="{{ $inputType }}"
                                                   name="{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas[{$pergunta->id}][texto]" }}"
                                                   class="form-control campo-validacao"
                                                   data-formato="{{ $formato }}"
                                                   data-pergunta-id="{{ $pergunta->id }}"
                                                   placeholder="{{ $placeholder }}"
                                                   value="{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? old('identificador_respondente') : old("respostas.{$pergunta->id}.texto") }}"
                                                   maxlength="{{ $formato == 'cpf' ? '14' : ($formato == 'telefone' ? '15' : '') }}"
                                                   {{ $pergunta->obrigatoria ? 'required' : '' }}>

                                            <div class="text-danger mt-2" id="erro-{{ $pergunta->id }}" style="display: none;"></div>
                                            @error($questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas.{$pergunta->id}.texto")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                            @break

                                        @case('texto_longo')
                                            <textarea name="{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas[{$pergunta->id}][texto]" }}"
                                                      class="form-control" rows="4"
                                                      placeholder="Digite sua resposta detalhada"
                                                      {{ $pergunta->obrigatoria ? 'required' : '' }}>{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? old('identificador_respondente') : old("respostas.{$pergunta->id}.texto") }}</textarea>
                                            @error($questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas.{$pergunta->id}.texto")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                            @break

                                        @case('radio')
                                            @foreach($pergunta->opcoesResposta as $opcao)
                                                <div class="opcao-item">
                                                    <input type="radio" name="respostas[{{ $pergunta->id }}][unica]"
                                                           id="opcao_{{ $pergunta->id }}_{{ $opcao->id }}"
                                                           value="{{ $opcao->id }}"
                                                           {{ old("respostas.{$pergunta->id}.unica") == $opcao->id ? 'checked' : '' }}
                                                           {{ $pergunta->obrigatoria ? 'required' : '' }}>
                                                    <label for="opcao_{{ $pergunta->id }}_{{ $opcao->id }}">
                                                        {{ $opcao->opcao }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error("respostas.{$pergunta->id}.unica")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                            @break

                                        @case('checkbox')
                                            @foreach($pergunta->opcoesResposta as $opcao)
                                                <div class="opcao-item">
                                                    <input type="checkbox" name="respostas[{{ $pergunta->id }}][multipla][]"
                                                           id="opcao_{{ $pergunta->id }}_{{ $opcao->id }}"
                                                           value="{{ $opcao->id }}"
                                                           {{ in_array($opcao->id, old("respostas.{$pergunta->id}.multipla", [])) ? 'checked' : '' }}
                                                           data-obrigatoria="{{ $pergunta->obrigatoria ? 'true' : 'false' }}">
                                                    <label for="opcao_{{ $pergunta->id }}_{{ $opcao->id }}">
                                                        {{ $opcao->opcao }}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error("respostas.{$pergunta->id}.multipla")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                            @break

                                        @case('select')
                                            <select name="respostas[{{ $pergunta->id }}][unica]"
                                                    class="form-control"
                                                    {{ $pergunta->obrigatoria ? 'required' : '' }}>
                                                <option value="">Selecione uma opção</option>
                                                @foreach($pergunta->opcoesResposta as $opcao)
                                                    <option value="{{ $opcao->id }}"
                                                            {{ old("respostas.{$pergunta->id}.unica") == $opcao->id ? 'selected' : '' }}>
                                                        {{ $opcao->opcao }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("respostas.{$pergunta->id}.unica")
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                            @break
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach

                            <!-- Navegação da seção -->
                            <div class="navegacao-secoes">
                                <button type="button" class="btn btn-navegacao anterior" onclick="secaoAnterior()">
                                    <i class="fas fa-arrow-left"></i> Anterior
                                </button>
                                <div class="indicador-secao">Seção {{ $secaoIndex + 1 }} de {{ $totalSecoes }}</div>
                                <button type="button" class="btn btn-navegacao" onclick="proximaSecao()">
                                    Próximo <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        @php $secaoIndex++; @endphp
                    @endforeach

                    @if($perguntasSemSecao->count() > 0)
                        <div class="secao-container" id="secao-{{ $secaoIndex }}" data-secao="{{ $secaoIndex }}">
                            <div class="secao-header">
                                <div class="secao-indicador">{{ $secaoIndex + 1 }}</div>
                                <h3><i class="fas fa-question-circle me-2"></i>Perguntas Gerais</h3>
                                <div class="secao-descricao">Perguntas adicionais do questionário</div>
                            </div>

                                                        @foreach($perguntasSemSecao as $pergunta)
                                @php $perguntaCount++; @endphp
                                <div class="pergunta-item" data-pergunta="{{ $perguntaCount }}">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="pergunta-numero">{{ $perguntaCount }}</span>
                                        <div class="flex-grow-1">
                                            <div class="pergunta-texto">{{ $pergunta->pergunta }}</div>
                                            @if($pergunta->obrigatoria)
                                                <div class="pergunta-obrigatoria">* Obrigatório</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="opcoes-container">
                                        @switch($pergunta->tipo)
                                            @case('texto_simples')
                                                @php
                                                    $formato = $pergunta->formato_validacao ?? 'texto_comum';
                                                    $inputType = 'text';
                                                    $placeholder = 'Digite sua resposta';
                                                    switch($formato) {
                                                        case 'email': $inputType = 'email'; $placeholder = 'Digite seu e-mail (ex: usuario@email.com)'; break;
                                                        case 'data': $inputType = 'date'; $placeholder = 'Selecione a data'; break;
                                                        case 'cpf': $placeholder = 'Digite o CPF (ex: 123.456.789-00)'; break;
                                                        case 'telefone': $placeholder = 'Digite o telefone (ex: (11) 99999-9999)'; break;
                                                        default: $placeholder = 'Digite sua resposta';
                                                    }
                                                @endphp
                                                <input type="{{ $inputType }}" name="{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas[{$pergunta->id}][texto]" }}" class="form-control campo-validacao" data-formato="{{ $formato }}" data-pergunta-id="{{ $pergunta->id }}" placeholder="{{ $placeholder }}" value="{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? old('identificador_respondente') : old("respostas.{$pergunta->id}.texto") }}" maxlength="{{ $formato == 'cpf' ? '14' : ($formato == 'telefone' ? '15' : '') }}" {{ $pergunta->obrigatoria ? 'required' : '' }}>
                                                <div class="text-danger mt-2" id="erro-{{ $pergunta->id }}" style="display: none;"></div>
                                                @error($questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas.{$pergunta->id}.texto")
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                @break

                                            @case('texto_longo')
                                                <textarea name="{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas[{$pergunta->id}][texto]" }}" class="form-control" rows="4" placeholder="Digite sua resposta detalhada" {{ $pergunta->obrigatoria ? 'required' : '' }}>{{ $questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? old('identificador_respondente') : old("respostas.{$pergunta->id}.texto") }}</textarea>
                                                @error($questionarioOferta->perguntaIdentificadora && $pergunta->id == $questionarioOferta->perguntaIdentificadora->id ? 'identificador_respondente' : "respostas.{$pergunta->id}.texto")
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                @break

                                            @case('radio')
                                                @foreach($pergunta->opcoesResposta as $opcao)
                                                    <div class="opcao-item">
                                                        <input type="radio" name="respostas[{{ $pergunta->id }}][unica]" id="opcao_{{ $pergunta->id }}_{{ $opcao->id }}" value="{{ $opcao->id }}" {{ old("respostas.{$pergunta->id}.unica") == $opcao->id ? 'checked' : '' }} {{ $pergunta->obrigatoria ? 'required' : '' }}>
                                                        <label for="opcao_{{ $pergunta->id }}_{{ $opcao->id }}">{{ $opcao->opcao }}</label>
                                                    </div>
                                                @endforeach
                                                @error("respostas.{$pergunta->id}.unica")
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                @break

                                            @case('checkbox')
                                                @foreach($pergunta->opcoesResposta as $opcao)
                                                    <div class="opcao-item">
                                                        <input type="checkbox" name="respostas[{{ $pergunta->id }}][multipla][]" id="opcao_{{ $pergunta->id }}_{{ $opcao->id }}" value="{{ $opcao->id }}" {{ in_array($opcao->id, old("respostas.{$pergunta->id}.multipla", [])) ? 'checked' : '' }} data-obrigatoria="{{ $pergunta->obrigatoria ? 'true' : 'false' }}">
                                                        <label for="opcao_{{ $pergunta->id }}_{{ $opcao->id }}">{{ $opcao->opcao }}</label>
                                                    </div>
                                                @endforeach
                                                @error("respostas.{$pergunta->id}.multipla")
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                @break

                                            @case('select')
                                                <select name="respostas[{{ $pergunta->id }}][unica]" class="form-control" {{ $pergunta->obrigatoria ? 'required' : '' }}>
                                                    <option value="">Selecione uma opção</option>
                                                    @foreach($pergunta->opcoesResposta as $opcao)
                                                        <option value="{{ $opcao->id }}" {{ old("respostas.{$pergunta->id}.unica") == $opcao->id ? 'selected' : '' }}>
                                                            {{ $opcao->opcao }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("respostas.{$pergunta->id}.unica")
                                                    <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                            @endforeach

                            <!-- Navegação da seção -->
                            <div class="navegacao-secoes">
                                <button type="button" class="btn btn-navegacao anterior" onclick="secaoAnterior()">
                                    <i class="fas fa-arrow-left"></i> Anterior
                                </button>
                                <div class="indicador-secao">Seção {{ $secaoIndex + 1 }} de {{ $totalSecoes }}</div>
                                <button type="button" class="btn btn-navegacao" onclick="proximaSecao()">
                                    Próximo <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Seção Final - Revisão e Envio -->
                    <div class="secao-container" id="secao-final" data-secao="{{ $totalSecoes - 1 }}">
                        <div class="secao-header">
                            <div class="secao-indicador"><i class="fas fa-check"></i></div>
                            <h3><i class="fas fa-clipboard-check me-2"></i>Revisão e Envio</h3>
                            <div class="secao-descricao">Revise suas respostas e envie o questionário</div>
                        </div>

                        <div class="text-center py-4">
                            <div class="mb-4">
                                <i class="fas fa-clipboard-list text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="text-success mb-3">Questionário Completo!</h4>
                            <p class="text-muted mb-4">Você respondeu todas as perguntas. Clique no botão abaixo para enviar suas respostas.</p>

                            <!-- Resumo das respostas -->
                            <div class="alert alert-info text-start">
                                <h6><i class="fas fa-info-circle me-2"></i>Resumo das suas respostas:</h6>
                                <div id="resumoRespostas">
                                    <!-- Será preenchido via JavaScript -->
                                </div>
                            </div>
                        </div>

                        <!-- Navegação da seção final -->
                        <div class="navegacao-secoes">
                            <button type="button" class="btn btn-navegacao anterior" onclick="secaoAnterior()">
                                <i class="fas fa-arrow-left"></i> Anterior
                            </button>
                            <div class="indicador-secao">Seção {{ $totalSecoes }} de {{ $totalSecoes }}</div>
                            <button type="submit" class="btn btn-enviar btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Questionário
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer-info">
                <i class="fas fa-shield-alt me-2"></i>
                Suas respostas são confidenciais e serão utilizadas apenas para fins acadêmicos.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Variáveis globais para navegação
        let secaoAtual = 0;
        let totalSecoes = 0;
        let timeoutProgresso = null;
        let formElement = null;

        // Inicialização
        document.addEventListener('DOMContentLoaded', function() {
            formElement = document.getElementById('questionarioForm');
            inicializarNavegacao();
            inicializarIndicadoresTopo();
            inicializarTermoAceite();
            atualizarProgresso();
            inicializarSubmitHandler();
        });

        // Inicializar navegação entre seções
        function inicializarNavegacao() {
            const secoes = document.querySelectorAll('.secao-container');
            totalSecoes = secoes.length;
            console.log('Total de seções encontradas:', totalSecoes);

            // Mostrar apenas a primeira seção
            secoes.forEach((secao, index) => {
                if (index === 0) {
                    secao.classList.add('ativa');
                } else {
                    secao.classList.remove('ativa');
                }
            });

                        // Adicionar listeners para atualizar progresso (apenas em campos de resposta)
            const inputs = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"]):not([type="button"]), textarea, select');
            inputs.forEach(input => {
                // Adicionar listener de change para todos
                input.addEventListener('change', atualizarProgresso);
                // Para campos de texto, usar input para atualização em tempo real (mas com debounce já implementado)
                if (input.type === 'text' || input.type === 'textarea' || input.tagName === 'SELECT') {
                    input.addEventListener('input', atualizarProgresso);
                }
            });
        }

                        // Inicializar indicadores no topo
        function inicializarIndicadoresTopo() {
            const container = document.getElementById('indicadoresSecoesTopo');
            const secoes = document.querySelectorAll('.secao-container');

            secoes.forEach((secao, index) => {
                const indicador = document.createElement('div');
                indicador.className = 'indicador-secao-topo';
                indicador.textContent = index + 1;

                // Desabilitar cliques em seções futuras até que as anteriores sejam respondidas
                if (index === 0) {
                    indicador.classList.add('ativa');
                    indicador.onclick = () => irParaSecao(index);
                } else {
                    indicador.classList.add('desabilitada');
                    indicador.onclick = null;
                    indicador.style.cursor = 'not-allowed';
                    indicador.style.opacity = '0.5';
                }

                container.appendChild(indicador);
            });
        }

        // Inicializar controle do termo de aceite
        function inicializarTermoAceite() {
            const checkboxAceite = document.getElementById('aceite_termos');
            const btnProximo = document.getElementById('btn-proximo');

            if (checkboxAceite && btnProximo) {
                // Verificar estado inicial
                btnProximo.disabled = !checkboxAceite.checked;

                // Adicionar listener para mudanças
                checkboxAceite.addEventListener('change', function() {
                    btnProximo.disabled = !this.checked;

                    // Adicionar feedback visual
                    if (this.checked) {
                        btnProximo.classList.add('btn-success');
                        btnProximo.classList.remove('btn-secondary');
                    } else {
                        btnProximo.classList.remove('btn-success');
                        btnProximo.classList.add('btn-secondary');
                    }
                });
            }
        }

        // Navegar para próxima seção
        function proximaSecao() {
            if (secaoAtual < totalSecoes - 1) {
                // Validar seção atual antes de avançar
                if (validarSecaoAtual()) {
                    irParaSecao(secaoAtual + 1);
                } else {
                    //alert('Por favor, responda todas as perguntas obrigatórias desta seção antes de continuar.');
                }
            }
        }

        // Navegar para seção anterior
        function secaoAnterior() {
            if (secaoAtual > 0) {
                irParaSecao(secaoAtual - 1);
            }
        }

                // Ir para seção específica
        function irParaSecao(index) {
            if (index >= 0 && index < totalSecoes) {
                // Validar seção atual antes de permitir mudança
                if (index !== secaoAtual && !validarSecaoAtual()) {
                    alert('Por favor, responda todas as perguntas obrigatórias desta seção antes de continuar.');
                    return false;
                }

                // Ocultar seção atual
                const secaoAtualElement = document.querySelector(`.secao-container[data-secao="${secaoAtual}"]`);
                if (secaoAtualElement) {
                    secaoAtualElement.classList.remove('ativa');
                }

                // Mostrar nova seção
                const novaSecao = document.querySelector(`.secao-container[data-secao="${index}"]`);
                if (novaSecao) {
                    novaSecao.classList.add('ativa');
                }

                // Atualizar indicadores
                atualizarIndicadoresSecao(index);

                // Atualizar variável global
                secaoAtual = index;

                // Scroll para o topo da seção
                window.scrollTo({ top: 0, behavior: 'smooth' });

                // Se for a última seção, gerar resumo
                if (index === totalSecoes - 1) {
                    gerarResumoRespostas();

                    // Garantir que o botão de submit esteja acessível
                    const submitBtn = formElement.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.style.display = 'inline-block';
                        submitBtn.disabled = false;
                        console.log('Botão de submit habilitado na última seção');
                    }
                }

                // Atualizar progresso
                atualizarProgresso();
            }
        }

        // Validar seção atual
        function validarSecaoAtual() {
            const secaoElement = document.querySelector(`.secao-container[data-secao="${secaoAtual}"]`);
            if (!secaoElement) return true;

            // Validação especial para a primeira seção (termo de aceite)
            if (secaoAtual === 0) {
                const checkboxAceite = document.getElementById('aceite_termos');
                if (checkboxAceite && !checkboxAceite.checked) {
                    alert('Por favor, aceite os termos e condições para continuar.');
                    return false;
                }

                // Se houver campo de identificação configurado, validar também
                const campoIdentificacao = secaoElement.querySelector('input[name="identificador_respondente"], textarea[name="identificador_respondente"]');
                if (campoIdentificacao && campoIdentificacao.required && campoIdentificacao.value.trim() === '') {
                    alert('Por favor, preencha o campo de identificação para continuar.');
                    campoIdentificacao.focus();
                    return false;
                }

                return true;
            }

            const perguntasObrigatorias = secaoElement.querySelectorAll('.pergunta-obrigatoria');
            let todasRespondidas = true;

            perguntasObrigatorias.forEach(obrigatoria => {
                const perguntaItem = obrigatoria.closest('.pergunta-item');
                const inputs = perguntaItem.querySelectorAll('input, textarea, select');
                let temResposta = false;

                // Verificar se é um grupo de checkboxes
                const checkboxes = perguntaItem.querySelectorAll('input[type="checkbox"]');
                if (checkboxes.length > 0) {
                    // Para checkboxes, verificar se pelo menos um está marcado (se for obrigatório)
                    const primeiroCheckbox = checkboxes[0];
                    const isObrigatorio = primeiroCheckbox.dataset.obrigatoria === 'true';
                    if (isObrigatorio) {
                        checkboxes.forEach(cb => {
                            if (cb.checked) temResposta = true;
                        });
                    } else {
                        // Se não for obrigatório, considerar como respondido
                        temResposta = true;
                    }
                } else {
                    // Para outros tipos de input, usar a lógica normal
                    inputs.forEach(input => {
                        if (input.type === 'radio') {
                            if (input.checked) temResposta = true;
                        } else if (input.type === 'text' || input.type === 'textarea' || input.type === 'email' || input.type === 'date') {
                            if (input.value.trim() !== '') temResposta = true;
                        } else if (input.tagName === 'SELECT') {
                            if (input.value !== '') temResposta = true;
                        }
                    });
                }

                if (!temResposta) {
                    todasRespondidas = false;
                    perguntaItem.style.borderColor = '#dc3545';
                    perguntaItem.style.backgroundColor = '#fff5f5';
                } else {
                    perguntaItem.style.borderColor = '#e9ecef';
                    perguntaItem.style.backgroundColor = '#f8f9fa';
                }
            });

            return todasRespondidas;
        }

                // Atualizar indicadores de seção
        function atualizarIndicadoresSecao(secaoIndex) {
            const indicadores = document.querySelectorAll('.indicador-secao-topo');

            indicadores.forEach((indicador, index) => {
                indicador.classList.remove('ativa', 'completa', 'desabilitada');

                if (index === secaoIndex) {
                    indicador.classList.add('ativa');
                    indicador.onclick = () => irParaSecao(index);
                    indicador.style.cursor = 'pointer';
                    indicador.style.opacity = '1';
                } else if (index < secaoIndex) {
                    indicador.classList.add('completa');
                    indicador.onclick = () => irParaSecao(index);
                    indicador.style.cursor = 'pointer';
                    indicador.style.opacity = '1';
                } else {
                    // Seções futuras ficam desabilitadas
                    indicador.classList.add('desabilitada');
                    indicador.onclick = null;
                    indicador.style.cursor = 'not-allowed';
                    indicador.style.opacity = '0.5';
                }
            });
        }

                // Atualizar barra de progresso (com debounce para evitar muitas chamadas)
        function atualizarProgresso() {
            // Limpar timeout anterior
            if (timeoutProgresso) {
                clearTimeout(timeoutProgresso);
            }

            // Agendar atualização após 300ms (debounce)
            timeoutProgresso = setTimeout(function() {
                const perguntas = document.querySelectorAll('.pergunta-item[data-pergunta]');
                const totalPerguntas = perguntas.length;

                if (totalPerguntas === 0) {
                    return; // Evitar divisão por zero
                }

                let perguntasRespondidas = 0;

                // Verificar seção de aceite (primeira seção)
                if (secaoAtual === 0) {
                    const checkboxAceite = document.getElementById('aceite_termos');
                    if (checkboxAceite && checkboxAceite.checked) {
                        perguntasRespondidas++;
                    }

                    // Verificar campo de identificação se configurado
                    const campoIdentificacao = document.querySelector('input[name="identificador_respondente"], textarea[name="identificador_respondente"]');
                    if (campoIdentificacao && campoIdentificacao.required && campoIdentificacao.value.trim() !== '') {
                        perguntasRespondidas++;
                    }
                }

                perguntas.forEach(pergunta => {
                    const inputs = pergunta.querySelectorAll('input, textarea, select');
                    let temResposta = false;

                    inputs.forEach(input => {
                        // Pular inputs hidden e botões
                        if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
                            return;
                        }

                        if (input.type === 'radio' || input.type === 'checkbox') {
                            if (input.checked) temResposta = true;
                        } else if (input.type === 'text' || input.type === 'textarea' || input.type === 'email' || input.type === 'date') {
                            if (input.value.trim() !== '') temResposta = true;
                        } else if (input.tagName === 'SELECT') {
                            if (input.value !== '') temResposta = true;
                        }
                    });

                    if (temResposta) perguntasRespondidas++;
                });

                const progresso = (perguntasRespondidas / totalPerguntas) * 100;
                const progressBar = document.getElementById('progressBar');
                const progressText = document.getElementById('progressText');

                if (progressBar) {
                    progressBar.style.width = progresso + '%';
                }

                if (progressText) {
                    progressText.textContent = `${perguntasRespondidas} de ${totalPerguntas} perguntas respondidas`;
                }
            }, 300);
        }

    // Gerar resumo das respostas para a seção final
    function gerarResumoRespostas() {
        const container = document.getElementById('resumoRespostas');
        if (!container) return;

        let resumoHTML = '';

        // Adicionar aceite dos termos
        const checkboxAceite = document.getElementById('aceite_termos');
        if (checkboxAceite && checkboxAceite.checked) {
            resumoHTML += `
                <div class="mb-2">
                    <strong>Termos e Condições</strong><br>
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Aceitos</span>
                </div>
            `;
        }

        const perguntas = document.querySelectorAll('.pergunta-item[data-pergunta]');
        perguntas.forEach(pergunta => {
            const perguntaTexto = pergunta.querySelector('.pergunta-texto').textContent;
            const inputs = pergunta.querySelectorAll('input, textarea, select');
            let resposta = '';

            inputs.forEach(input => {
                if (input.type === 'radio' && input.checked) {
                    resposta = input.nextElementSibling.textContent;
                } else if (input.type === 'checkbox' && input.checked) {
                    if (resposta) resposta += ', ';
                    resposta += input.nextElementSibling.textContent;
                } else if (input.type === 'text' || input.type === 'email' || input.type === 'date' || input.type === 'textarea') {
                    if (input.value.trim()) {
                        resposta = input.value.trim();
                    }
                } else if (input.tagName === 'SELECT' && input.value) {
                    resposta = input.options[input.selectedIndex].text;
                }
            });

            if (resposta) {
                resumoHTML += `
                    <div class="mb-2">
                        <strong>${perguntaTexto}</strong><br>
                        <span class="text-muted">${resposta}</span>
                    </div>
                `;
            }
        });

        if (resumoHTML) {
            container.innerHTML = resumoHTML;
        } else {
            container.innerHTML = '<p class="text-muted">Nenhuma resposta encontrada.</p>';
        }
    }

        // Funções de validação
        function validarCPF(cpf) {
            console.log('=== VALIDANDO CPF ===');
            console.log('CPF recebido:', cpf);
            cpf = cpf.replace(/[^\d]/g, '');
            console.log('CPF limpo:', cpf);
            console.log('Tamanho do CPF:', cpf.length);

            if (cpf.length !== 11) {
                console.log('CPF inválido: tamanho incorreto');
                return false;
            }

            // Verificar se todos os dígitos são iguais
            if (/^(\d)\1{10}$/.test(cpf)) {
                console.log('CPF inválido: todos os dígitos são iguais');
                return false;
            }

            // Validar primeiro dígito verificador
            let soma = 0;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let resto = 11 - (soma % 11);
            let dv1 = resto < 2 ? 0 : resto;
            console.log('Primeiro dígito verificador calculado:', dv1);

            // Validar segundo dígito verificador
            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = 11 - (soma % 11);
            let dv2 = resto < 2 ? 0 : resto;
            console.log('Segundo dígito verificador calculado:', dv2);

            const dv1Original = parseInt(cpf.charAt(9));
            const dv2Original = parseInt(cpf.charAt(10));
            console.log('Dígitos verificadores originais:', dv1Original, dv2Original);

            const valido = dv1Original === dv1 && dv2Original === dv2;
            console.log('CPF válido?', valido);

            return valido;
        }

        function validarTelefone(telefone) {
            const telefoneLimpo = telefone.replace(/[^\d]/g, '');
            return telefoneLimpo.length >= 10 && telefoneLimpo.length <= 11;
        }

        function validarEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validarData(data) {
            if (!data) return false;
            const dataObj = new Date(data);
            return dataObj instanceof Date && !isNaN(dataObj);
        }

        function validarNumero(numero) {
            if (!numero) return false;
            // Verificar se é um número válido (apenas dígitos)
            return /^\d+$/.test(numero);
        }

        function formatarCPF(input) {
            console.log('=== FORMATANDO CPF ===');
            console.log('Valor original:', input.value);

            // Remover todos os caracteres não numéricos
            let valor = input.value.replace(/\D/g, '');
            console.log('Valor após remover não-dígitos:', valor);

            // Limitar a 11 dígitos (CPF sem formatação)
            if (valor.length > 11) {
                valor = valor.substring(0, 11);
                console.log('Valor limitado a 11 dígitos:', valor);
            }

            // Aplicar formatação apenas se tiver dígitos suficientes
            if (valor.length > 0) {
                if (valor.length <= 3) {
                    // Apenas os primeiros dígitos
                    valor = valor;
                } else if (valor.length <= 6) {
                    // Adicionar primeiro ponto
                    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
                } else if (valor.length <= 9) {
                    // Adicionar segundo ponto
                    valor = valor.replace(/(\d{3})(\d{3})(\d)/, '$1.$2.$3');
                } else {
                    // Adicionar hífen
                    valor = valor.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
                }
            }

            console.log('Valor final formatado:', valor);
            input.value = valor;
            console.log('Valor definido no input:', input.value);
        }

        function formatarTelefone(input) {
            console.log('=== FORMATANDO TELEFONE ===');
            console.log('Valor original:', input.value);

            // Remover todos os caracteres não numéricos
            let valor = input.value.replace(/\D/g, '');
            console.log('Valor após remover não-dígitos:', valor);

            // Limitar a 11 dígitos (telefone com DDD)
            if (valor.length > 11) {
                valor = valor.substring(0, 11);
                console.log('Valor limitado a 11 dígitos:', valor);
            }

            // Aplicar formatação baseada no tamanho
            if (valor.length === 11) {
                valor = valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (valor.length === 10) {
                valor = valor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
            }

            console.log('Valor final formatado:', valor);
            input.value = valor;
        }

        function validarCampo(input) {
            console.log('=== VALIDANDO CAMPO ===');
            const formato = input.dataset.formato;
            const perguntaId = input.dataset.perguntaId;
            const valor = input.value.trim();
            const erroDiv = document.getElementById(`erro-${perguntaId}`);

            console.log('Formato:', formato);
            console.log('Pergunta ID:', perguntaId);
            console.log('Valor:', valor);
            console.log('Elemento de erro encontrado:', erroDiv);

            // Limpar erro anterior
            erroDiv.style.display = 'none';
            input.classList.remove('is-invalid');

            if (!valor) return true; // Campo vazio é válido (a menos que seja obrigatório)

            let valido = true;
            let mensagem = '';

            switch(formato) {
                case 'cpf':
                    console.log('Validando CPF:', valor);
                    if (!validarCPF(valor)) {
                        valido = false;
                        mensagem = 'CPF inválido. Use o formato: 123.456.789-00';
                        console.log('CPF inválido, mensagem:', mensagem);
                    } else {
                        console.log('CPF válido');
                    }
                    break;
                case 'telefone':
                    if (!validarTelefone(valor)) {
                        valido = false;
                        mensagem = 'Telefone inválido. Use o formato: (11) 99999-9999';
                    }
                    break;
                case 'email':
                    if (!validarEmail(valor)) {
                        valido = false;
                        mensagem = 'E-mail inválido. Use o formato: usuario@email.com';
                    }
                    break;
                case 'data':
                    if (!validarData(valor)) {
                        valido = false;
                        mensagem = 'Data inválida.';
                    }
                    break;
                case 'numero':
                    if (!validarNumero(valor)) {
                        valido = false;
                        mensagem = 'Por favor, digite apenas números.';
                    }
                    break;
            }

            if (!valido) {
                input.classList.add('is-invalid');
                erroDiv.textContent = mensagem;
                erroDiv.style.display = 'block';
            }

            return valido;
        }

        // Aplicar formatação e validação aos campos
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== INICIALIZAÇÃO DOS CAMPOS DE VALIDAÇÃO ===');
            const camposValidacao = document.querySelectorAll('.campo-validacao');
            console.log('Total de campos de validação encontrados:', camposValidacao.length);

            camposValidacao.forEach((campo, index) => {
                const formato = campo.dataset.formato;
                const perguntaId = campo.dataset.perguntaId;
                console.log(`Campo ${index + 1}: formato = ${formato}, pergunta ID = ${perguntaId}`);

                // Aplicar formatação em tempo real
                if (formato === 'cpf') {
                    console.log(`Aplicando formatação CPF ao campo ${index + 1}`);
                    campo.addEventListener('input', function() {
                        console.log(`Formatando CPF: ${this.value}`);
                        formatarCPF(this);
                    });

                    // Impedir digitação de caracteres inválidos
                    campo.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.which);
                        if (!/\d/.test(char)) {
                            e.preventDefault();
                            console.log('Caractere inválido bloqueado:', char);
                        }
                    });
                } else if (formato === 'telefone') {
                    console.log(`Aplicando formatação telefone ao campo ${index + 1}`);
                    campo.addEventListener('input', function() {
                        console.log(`Formatando telefone: ${this.value}`);
                        formatarTelefone(this);
                    });

                    // Impedir digitação de caracteres inválidos
                    campo.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.which);
                        if (!/\d/.test(char)) {
                            e.preventDefault();
                            console.log('Caractere inválido bloqueado:', char);
                        }
                    });
                } else if (formato === 'numero') {
                    console.log(`Aplicando validação de número ao campo ${index + 1}`);
                    // Permitir apenas números
                    campo.addEventListener('input', function() {
                        this.value = this.value.replace(/[^\d]/g, '');
                    });

                    // Impedir digitação de caracteres não numéricos
                    campo.addEventListener('keypress', function(e) {
                        const char = String.fromCharCode(e.which);
                        if (!/\d/.test(char)) {
                            e.preventDefault();
                            console.log('Caractere não numérico bloqueado:', char);
                        }
                    });
                }

                // Validar ao perder o foco (removido temporariamente para evitar loop)
                // campo.addEventListener('blur', function() {
                //     console.log(`Validando campo ${index + 1} ao perder foco`);
                //     validarCampo(this);
                // });

                // Validar ao pressionar Enter
                campo.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        console.log(`Validando campo ${index + 1} ao pressionar Enter`);
                        validarCampo(this);
                        // Ir para próximo campo
                        const form = this.closest('form');
                        const inputs = Array.from(form.querySelectorAll('input, textarea, select'));
                        const currentIndex = inputs.indexOf(this);
                        if (currentIndex < inputs.length - 1) {
                            inputs[currentIndex + 1].focus();
                        }
                    }
                });

                // Validação adicional para campos específicos
                if (formato === 'data') {
                    campo.addEventListener('input', function() {
                        // Garantir formato de data válido
                        const valor = this.value;
                        if (valor && !/^\d{4}-\d{2}-\d{2}$/.test(valor)) {
                            console.log('Formato de data inválido:', valor);
                        }
                    });
                }
            });
        });

                // Função para enviar formulário via AJAX
        function enviarFormulario(form) {
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            // Desabilitar botão e mostrar loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

            // Mostrar indicador de progresso
            const progressBar = document.getElementById('progressBar');
            progressBar.style.width = '100%';
            progressBar.classList.add('bg-success');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // Verificar se a resposta é JSON
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    return response.json();
                } else {
                    // Se não for JSON, assumir sucesso (redirect do Laravel)
                    return { success: true };
                }
            })
            .then(data => {
                console.log('Resposta do servidor:', data);

                if (data.success || !data.error) {
                    // Sucesso! Mostrar modal
                    const modalElement = document.getElementById('modalSucesso');
                    const modal = new bootstrap.Modal(modalElement);

                    // Função para limpar backdrop e estado do modal
                    function limparBackdrop() {
                        // Remover todos os backdrops
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        backdrops.forEach(backdrop => {
                            backdrop.remove();
                        });

                        // Remover classes e estilos do body
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                    }

                    // Quando o modal for fechado, ocultar formulário e mostrar mensagem
                    const esconderFormulario = function() {
                        // Limpar backdrop
                        limparBackdrop();

                        // Ocultar formulário
                        const formularioContainer = document.getElementById('formularioContainer');
                        if (formularioContainer) {
                            formularioContainer.style.display = 'none';
                        }

                        // Mostrar mensagem de sucesso
                        const mensagemSucesso = document.getElementById('mensagemSucesso');
                        if (mensagemSucesso) {
                            mensagemSucesso.style.display = 'block';
                        }
                    };

                    // Adicionar event listener para quando o modal for fechado
                    modalElement.addEventListener('hidden.bs.modal', esconderFormulario, { once: true });

                    // Adicionar listener para o botão "Entendi" também
                    const btnEntendi = modalElement.querySelector('[data-bs-dismiss="modal"]');
                    if (btnEntendi) {
                        btnEntendi.addEventListener('click', function() {
                            setTimeout(limparBackdrop, 100);
                        }, { once: true });
                    }

                    modal.show();

                    // Garantir que o backdrop seja removido se o modal não fechar corretamente
                    setTimeout(() => {
                        const backdrops = document.querySelectorAll('.modal-backdrop');
                        if (backdrops.length > 1) {
                            // Se houver múltiplos backdrops, remover os extras
                            for (let i = 1; i < backdrops.length; i++) {
                                backdrops[i].remove();
                            }
                        }
                    }, 500);

                    // Limpar formulário
                    form.reset();

                    // Resetar progresso
                    progressBar.style.width = '0%';
                    progressBar.classList.remove('bg-success');

                    // Scroll para o topo
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Erro desconhecido');
                }
            })
            .catch(error => {
                console.error('Erro ao enviar formulário:', error);

                // Limpar qualquer backdrop que possa ter ficado
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';

                // Mostrar erro
                alert('Erro ao enviar o questionário: ' + error.message);

                // Resetar progresso
                progressBar.style.width = '0%';
                progressBar.classList.remove('bg-success');
            })
            .finally(() => {
                // Reabilitar botão
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        }

                                // Função para inicializar o handler de submit
        function inicializarSubmitHandler() {
            if (!formElement) {
                console.error('ERRO: Formulário não encontrado!');
                return;
            }

            console.log('Formulário encontrado, adicionando event listener de submit...');

                        // Adicionar listener diretamente no botão de submit também (usando delegação de eventos para capturar mesmo se o botão for adicionado dinamicamente)
            formElement.addEventListener('click', function(e) {
                const submitButton = e.target.closest('button[type="submit"]');
                if (submitButton) {
                    console.log('Botão de submit clicado (via delegação)!');
                    e.preventDefault();
                    e.stopPropagation();
                    // Disparar submit manualmente usando requestSubmit (suporta validação HTML5)
                    // Se não funcionar, tentar dispatchEvent como fallback
                    console.log('Disparando submit manualmente...');
                    try {
                        if (typeof formElement.requestSubmit === 'function') {
                            formElement.requestSubmit();
                        } else {
                            // Fallback para navegadores mais antigos
                            const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                            formElement.dispatchEvent(submitEvent);
                        }
                    } catch (error) {
                        console.error('Erro ao disparar submit:', error);
                        // Último recurso: chamar a função de validação diretamente
                        const submitHandler = formElement.onsubmit;
                        if (submitHandler) {
                            submitHandler.call(formElement, e);
                        }
                    }
                }
            });

            // Também adicionar listener direto se o botão já existir
            const submitButton = formElement.querySelector('button[type="submit"]');
            if (submitButton) {
                console.log('Botão de submit encontrado, adicionando listener de click...');
                submitButton.addEventListener('click', function(e) {
                    console.log('Botão de submit clicado (listener direto)!');
                    // Não prevenir aqui para não interferir com o submit, mas garantir que o formulário seja submetido
                    // Se o comportamento padrão não funcionar, o listener de delegação vai cuidar disso
                });
            } else {
                console.warn('AVISO: Botão de submit não encontrado no carregamento inicial (pode estar na última seção)');
            }

            formElement.addEventListener('submit', function(e) {
                e.preventDefault(); // Sempre prevenir envio padrão

                console.log('=== VALIDAÇÃO DO FORMULÁRIO ===');
                console.log('Seção atual:', secaoAtual);
                console.log('Total de seções:', totalSecoes);
                console.log('Evento de submit disparado!');

            // Verificar se está na última seção
            if (secaoAtual !== totalSecoes - 1) {
                console.log('ERRO: Não está na última seção. Seção atual:', secaoAtual, 'Última seção:', totalSecoes - 1);
                alert('Por favor, complete todas as seções do questionário antes de enviar.');
                return false;
            }

            const perguntasObrigatorias = document.querySelectorAll('.pergunta-obrigatoria');
            console.log('Perguntas obrigatórias encontradas:', perguntasObrigatorias.length);

            let todasRespondidas = true;
            let todosCamposValidos = true;
            let perguntasNaoRespondidas = [];

            // Validar campos obrigatórios
            perguntasObrigatorias.forEach(obrigatoria => {
                const perguntaItem = obrigatoria.closest('.pergunta-item');
                if (!perguntaItem) return;

                const inputs = perguntaItem.querySelectorAll('input, textarea, select');
                let temResposta = false;

                // Verificar se é um grupo de checkboxes
                const checkboxes = perguntaItem.querySelectorAll('input[type="checkbox"]');
                if (checkboxes.length > 0) {
                    // Para checkboxes, verificar se pelo menos um está marcado (se for obrigatório)
                    const primeiroCheckbox = checkboxes[0];
                    const isObrigatorio = primeiroCheckbox.dataset.obrigatoria === 'true';
                    if (isObrigatorio) {
                        checkboxes.forEach(cb => {
                            if (cb.checked) temResposta = true;
                        });
                    } else {
                        // Se não for obrigatório, considerar como respondido
                        temResposta = true;
                    }
                } else {
                    // Para outros tipos de input, usar a lógica normal
                    inputs.forEach(input => {
                        // Pular inputs do tipo hidden e botões
                        if (input.type === 'hidden' || input.type === 'submit' || input.type === 'button') {
                            return;
                        }

                        if (input.type === 'radio') {
                            if (input.checked) temResposta = true;
                        } else if (input.type === 'text' || input.type === 'textarea' || input.type === 'email' || input.type === 'date') {
                            if (input.value.trim() !== '') temResposta = true;
                        } else if (input.tagName === 'SELECT') {
                            if (input.value !== '') temResposta = true;
                        }
                    });
                }

                if (!temResposta) {
                    todasRespondidas = false;
                    const perguntaTexto = perguntaItem.querySelector('.pergunta-texto');
                    if (perguntaTexto) {
                        perguntasNaoRespondidas.push(perguntaTexto.textContent.trim());
                    }
                    perguntaItem.style.borderColor = '#dc3545';
                    perguntaItem.style.backgroundColor = '#fff5f5';
                } else {
                    perguntaItem.style.borderColor = '#e9ecef';
                    perguntaItem.style.backgroundColor = '#f8f9fa';
                }
            });

            // Validar campos de formatação
            const camposValidacao = document.querySelectorAll('.campo-validacao');
            let camposInvalidos = [];
            camposValidacao.forEach(campo => {
                if (!validarCampo(campo)) {
                    todosCamposValidos = false;
                    const erroDiv = document.getElementById(`erro-${campo.dataset.perguntaId}`);
                    if (erroDiv && erroDiv.textContent.trim()) {
                        camposInvalidos.push(erroDiv.textContent.trim());
                    }
                }
            });

            // Debug: ver o que está sendo enviado
            const formData = new FormData(this);
            console.log('=== DADOS DO FORMULÁRIO ===');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            if (!todasRespondidas || !todosCamposValidos) {
                if (!todasRespondidas) {
                    console.log('Perguntas não respondidas:', perguntasNaoRespondidas);
                    alert('Por favor, responda todas as perguntas obrigatórias antes de enviar.\n\nPerguntas não respondidas: ' + perguntasNaoRespondidas.slice(0, 3).join(', ') + (perguntasNaoRespondidas.length > 3 ? '...' : ''));
                } else {
                    console.log('Campos inválidos:', camposInvalidos);
                    alert('Por favor, corrija os erros de validação antes de enviar.\n\nErros: ' + camposInvalidos.slice(0, 3).join(', ') + (camposInvalidos.length > 3 ? '...' : ''));
                }
                return false;
            }

            // Se passou na validação, enviar via AJAX
            console.log('Validação passou, enviando formulário...');
            enviarFormulario(this);
            });
        }
    </script>
</body>
</html>
