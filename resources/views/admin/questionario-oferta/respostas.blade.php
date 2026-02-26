<x-admin>
    @section('title', 'Respostas do Questionário')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-bar me-2"></i>
                Respostas: {{ $questionarioOferta->titulo }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.questionario-oferta.export-csv', $questionarioOferta->id) }}"
                   class="btn btn-success">
                    <i class="fas fa-download"></i> Exportar CSV
                </a>
                <a href="{{ route('admin.questionario-oferta.show', $questionarioOferta->id) }}"
                   class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Estatísticas -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="info-box bg-info">
                        <span class="info-box-icon"><i class="fas fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total de Respostas</span>
                            <span class="info-box-number">{{ $respostas->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-success">
                        <span class="info-box-icon"><i class="fas fa-calendar"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Hoje</span>
                            <span class="info-box-number">{{ $respostas->where('created_at', '>=', now()->startOfDay())->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-warning">
                        <span class="info-box-icon"><i class="fas fa-calendar-week"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Esta Semana</span>
                            <span class="info-box-number">{{ $respostas->where('created_at', '>=', now()->startOfWeek())->count() }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box bg-primary">
                        <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Este Mês</span>
                            <span class="info-box-number">{{ $respostas->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($respostas->count() > 0)
                <table class="table table-striped" id="respostasTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Respondente</th>
                            @if($questionarioOferta->temCampoCpf())
                                <th>Estudante (CPF)</th>
                                <th>Frequência</th>
                            @endif
                            <th>Data Resposta</th>
                            <th>Perguntas Respondidas</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($respostas as $resposta)
                            <tr>
                                <td>{{ $resposta->id }}</td>
                                <td>
                                    <strong>{{ $resposta->identificador_respondente ?? '-' }}</strong>
                                </td>
                                @if($questionarioOferta->temCampoCpf())
                                    @php $estudante = $resposta->estudante_vinculado; @endphp
                                    <td>
                                        @if($estudante)
                                            <a href="{{ route('admin.estudante.show', $estudante->id) }}" title="Ver estudante">
                                                {{ $estudante->nome }}
                                            </a>
                                            <br><small class="text-muted">{{ $estudante->cpf }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($estudante)
                                            @php
                                                $totalRegistros = $estudante->frequencias()->count();
                                                $totalHoras = $estudante->frequencias()->sum('hora_aula');
                                            @endphp
                                            <span class="badge badge-info">{{ $totalRegistros }} registro(s)</span>
                                            <br><small>{{ $totalHoras }} h</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <span title="{{ $resposta->data_resposta->format('d/m/Y H:i:s') }}">
                                        {{ $resposta->data_resposta->format('d/m/Y') }}
                                    </span>
                                    <br>
                                    <small class="text-muted">{{ $resposta->data_resposta->format('H:i') }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $resposta->respostasIndividuais->count() }} respostas
                                    </span>
                                </td>
                                <td>
                                    @if($resposta->respostasIndividuais->count() > 0)
                                        <span class="badge badge-success">Completa</span>
                                    @else
                                        <span class="badge badge-warning">Parcial</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.questionario-oferta.resposta-detalhe', [$questionarioOferta->id, $resposta->id]) }}"
                                       class="btn btn-sm btn-info" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.questionario-oferta.destroy', $resposta->id) }}"
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir esta resposta?')"
                                                title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma resposta encontrada</h5>
                    <p class="text-muted">Este questionário ainda não recebeu respostas.</p>
                </div>
            @endif
        </div>
    </div>

    @section('css')
        <style>
            .info-box {
                display: flex;
                min-height: 80px;
                background: #fff;
                width: 100%;
                box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
                border-radius: 0.25rem;
                margin-bottom: 1rem;
            }

            .info-box-icon {
                border-radius: 0.25rem 0 0 0.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.875rem;
                font-weight: 300;
                text-align: center;
                width: 70px;
                color: #fff;
            }

            .info-box-content {
                display: flex;
                flex-direction: column;
                justify-content: center;
                line-height: 1.8;
                flex: 1;
                padding: 0 10px;
            }

            .info-box-text {
                display: block;
                font-size: 0.875rem;
                color: #6c757d;
            }

            .info-box-number {
                display: block;
                font-weight: 700;
                font-size: 1.25rem;
            }

            .bg-info {
                background-color: #17a2b8 !important;
            }

            .bg-success {
                background-color: #28a745 !important;
            }

            .bg-warning {
                background-color: #ffc107 !important;
            }

            .bg-primary {
                background-color: #007bff !important;
            }
        </style>
    @endsection

    @section('js')
        <script>
            $(function() {
                $('#respostasTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "responsive": true,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Portuguese-Brasil.json"
                    },
                    "order": [[2, "desc"]] // Ordenar por data de resposta (mais recente primeiro)
                });
            });
        </script>
    @endsection
</x-admin>
