<x-admin>
    @section('title', 'Detalhes do Estudante')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-graduate me-2"></i>{{ $estudante->nome }}
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.estudante.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informações do Estudante</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Nome:</th>
                            <td>{{ $estudante->nome }}</td>
                        </tr>
                        <tr>
                            <th>CPF:</th>
                            <td>{{ $estudante->cpf }}</td>
                        </tr>
                        <tr>
                            <th>Data de Nascimento:</th>
                            <td>{{ $estudante->data_nascimento ? $estudante->data_nascimento->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Matrícula:</th>
                            <td>{{ $estudante->matricula ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Nome da Mãe:</th>
                            <td>{{ $estudante->nome_mae ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Oferta:</th>
                            <td>{{ $estudante->oferta->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>CEP:</th>
                            <td>{{ $estudante->cep ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Telefone:</th>
                            <td>{{ $estudante->telefone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $estudante->email ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <h5>Disciplinas Matriculadas ({{ $estudante->disciplinas->count() }})</h5>
                    @if($estudante->disciplinas->count() > 0)
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Disciplina</th>
                                    <th>Professor</th>
                                    <th>Período</th>
                                    <th>Carga Horária</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($estudante->disciplinas as $disciplina)
                                    <tr>
                                        <td>{{ $disciplina->nome }}</td>
                                        <td>{{ $disciplina->professor->name ?? 'N/A' }}</td>
                                        <td>{{ $disciplina->periodo }}</td>
                                        <td>{{ $disciplina->carga_horaria_total }} h/aula</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Estudante não está matriculado em nenhuma disciplina.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin>

