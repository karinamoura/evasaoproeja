<x-admin>
    @section('title', 'Detalhes da Disciplina')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-book me-2"></i>{{ $disciplina->nome }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.disciplina.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informações da Disciplina</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Nome:</th>
                            <td>{{ $disciplina->nome }}</td>
                        </tr>
                        <tr>
                            <th>Professor:</th>
                            <td>{{ $disciplina->professor->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Oferta:</th>
                            <td>{{ $disciplina->oferta->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Período:</th>
                            <td>{{ $disciplina->periodo }}</td>
                        </tr>
                        <tr>
                            <th>Carga Horária Total:</th>
                            <td>{{ $disciplina->carga_horaria_total }} h/aula</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Estudantes Matriculados ({{ $disciplina->estudantes->count() }})</h5>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalEstudantes">
                            <i class="fas fa-user-plus me-1"></i>Gerenciar Estudantes
                        </button>
                    </div>
                    @if($disciplina->estudantes->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Matrícula</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disciplina->estudantes as $estudante)
                                    <tr>
                                        <td>{{ $estudante->nome }}</td>
                                        <td>{{ $estudante->cpf }}</td>
                                        <td>{{ $estudante->matricula ?? 'N/A' }}</td>
                                        <td>{{ $estudante->email ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Nenhum estudante matriculado nesta disciplina.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gerenciar estudantes -->
    <div class="modal fade" id="modalEstudantes" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gerenciar Estudantes da Disciplina</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.disciplina.attach-estudantes', encrypt($disciplina->id)) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted">Selecione os estudantes que devem estar matriculados nesta disciplina:</p>
                        <div style="max-height: 400px; overflow-y: auto;">
                            @foreach($estudantesOferta as $estudante)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox"
                                        name="estudantes[]"
                                        value="{{ $estudante->id }}"
                                        id="estudante_{{ $estudante->id }}"
                                        {{ in_array($estudante->id, $estudantesMatriculados) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estudante_{{ $estudante->id }}">
                                        <strong>{{ $estudante->nome }}</strong> - CPF: {{ $estudante->cpf }}
                                        @if($estudante->matricula)
                                            - Matrícula: {{ $estudante->matricula }}
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin>

