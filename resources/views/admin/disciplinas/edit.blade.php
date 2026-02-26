<x-admin>
    @section('title', 'Editar Disciplina')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-book me-2"></i>Editar Disciplina
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.disciplina.index') }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('admin.disciplina.update', encrypt($disciplina->id)) }}" method="POST" class="needs-validation" novalidate>
            @method('PUT')
            @csrf
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome da Disciplina *</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome', $disciplina->nome) }}"
                                class="form-control @error('nome') is-invalid @enderror" required>
                            <x-error>nome</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="oferta_id" class="form-label">Oferta *</label>
                            <select name="oferta_id" id="oferta_id" class="form-control @error('oferta_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione a Oferta</option>
                                @foreach ($ofertas as $oferta)
                                    <option {{ old('oferta_id', $disciplina->oferta_id) == $oferta->id ? 'selected' : '' }}
                                        value="{{ $oferta->id }}">{{ $oferta->name }}</option>
                                @endforeach
                            </select>
                            <x-error>oferta_id</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="professor_id" class="form-label">Professor *</label>
                            <select name="professor_id" id="professor_id" class="form-control @error('professor_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione o Professor</option>
                                @foreach ($professores as $professor)
                                    <option {{ old('professor_id', $disciplina->professor_id) == $professor->id ? 'selected' : '' }}
                                        value="{{ $professor->id }}">{{ $professor->name }}</option>
                                @endforeach
                            </select>
                            <x-error>professor_id</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="periodo" class="form-label">Período *</label>
                            <input type="text" name="periodo" id="periodo" value="{{ old('periodo', $disciplina->periodo) }}"
                                class="form-control @error('periodo') is-invalid @enderror"
                                placeholder="Ex: 2025.1, 2025.2, 2026.1" required>
                            <small class="form-text text-muted">Formato: YYYY.P (ex: 2025.1)</small>
                            <x-error>periodo</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="carga_horaria_total" class="form-label">Carga Horária Total (h/aula) *</label>
                            <input type="number" name="carga_horaria_total" id="carga_horaria_total" value="{{ old('carga_horaria_total', $disciplina->carga_horaria_total) }}"
                                class="form-control @error('carga_horaria_total') is-invalid @enderror"
                                min="1" required>
                            <x-error>carga_horaria_total</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="data_inicio" class="form-label">Data de Início</label>
                            <input type="date" name="data_inicio" id="data_inicio"
                                value="{{ old('data_inicio', $disciplina->data_inicio ? $disciplina->data_inicio->format('Y-m-d') : '') }}"
                                class="form-control @error('data_inicio') is-invalid @enderror">
                            <small class="form-text text-muted">Data de início da disciplina (opcional, mas recomendado para cálculo de evasão)</small>
                            <x-error>data_inicio</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="data_fim" class="form-label">Data de Término</label>
                            <input type="date" name="data_fim" id="data_fim"
                                value="{{ old('data_fim', $disciplina->data_fim ? $disciplina->data_fim->format('Y-m-d') : '') }}"
                                class="form-control @error('data_fim') is-invalid @enderror">
                            <small class="form-text text-muted">Data de término da disciplina (opcional, mas recomendado para cálculo de evasão)</small>
                            <x-error>data_fim</x-error>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.disciplina.index') }}" class="btn btn-secondary">
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

