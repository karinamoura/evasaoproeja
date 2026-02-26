<x-admin>
    @section('title', 'Editar Frequência')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-clipboard-check me-2"></i>Editar Frequência
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.frequencia.show', encrypt($frequencia->disciplina_id)) }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('admin.frequencia.update', encrypt($frequencia->id)) }}" method="POST" class="needs-validation" novalidate>
            @method('PUT')
            @csrf
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <p class="mb-1"><strong>Estudante:</strong> {{ $frequencia->estudante->nome }}</p>
                    <p class="mb-1"><strong>Disciplina:</strong> {{ $frequencia->disciplina->nome }}</p>
                    <p class="mb-0"><strong>CPF:</strong> {{ $frequencia->estudante->cpf }}</p>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="data_aula" class="form-label">Data da Aula *</label>
                            <input type="date" name="data_aula" id="data_aula"
                                value="{{ old('data_aula', $frequencia->data_aula->format('Y-m-d')) }}"
                                class="form-control @error('data_aula') is-invalid @enderror" required>
                            <x-error>data_aula</x-error>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="hora_aula" class="form-label">Horas/Aula *</label>
                            <input type="number" name="hora_aula" id="hora_aula"
                                value="{{ old('hora_aula', $frequencia->hora_aula) }}"
                                class="form-control @error('hora_aula') is-invalid @enderror"
                                min="0" required>
                            <x-error>hora_aula</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea name="observacoes" id="observacoes" rows="3"
                                class="form-control @error('observacoes') is-invalid @enderror">{{ old('observacoes', $frequencia->observacoes) }}</textarea>
                            <x-error>observacoes</x-error>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.frequencia.show', encrypt($frequencia->disciplina_id)) }}" class="btn btn-secondary">
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
</x-admin>

