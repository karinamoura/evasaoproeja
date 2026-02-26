<x-admin>
    @section('title', 'Cadastrar Estudante')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-graduate me-2"></i>Cadastrar Estudante
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.estudante.index') }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('admin.estudante.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" name="nome" id="nome" value="{{ old('nome') }}"
                                class="form-control @error('nome') is-invalid @enderror" required>
                            <x-error>nome</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="cpf" class="form-label">CPF *</label>
                            <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}"
                                class="form-control @error('cpf') is-invalid @enderror"
                                placeholder="000.000.000-00" required>
                            <x-error>cpf</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" id="data_nascimento" value="{{ old('data_nascimento') }}"
                                class="form-control @error('data_nascimento') is-invalid @enderror">
                            <x-error>data_nascimento</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="matricula" class="form-label">Matrícula</label>
                            <input type="text" name="matricula" id="matricula" value="{{ old('matricula') }}"
                                class="form-control @error('matricula') is-invalid @enderror">
                            <x-error>matricula</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="nome_mae" class="form-label">Nome da Mãe</label>
                            <input type="text" name="nome_mae" id="nome_mae" value="{{ old('nome_mae') }}"
                                class="form-control @error('nome_mae') is-invalid @enderror">
                            <x-error>nome_mae</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="oferta_id" class="form-label">Oferta *</label>
                            <select name="oferta_id" id="oferta_id" class="form-control @error('oferta_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione a Oferta</option>
                                @foreach ($ofertas as $oferta)
                                    <option {{ old('oferta_id') == $oferta->id ? 'selected' : '' }}
                                        value="{{ $oferta->id }}">{{ $oferta->name }}</option>
                                @endforeach
                            </select>
                            <x-error>oferta_id</x-error>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" name="cep" id="cep" value="{{ old('cep') }}"
                                class="form-control @error('cep') is-invalid @enderror"
                                placeholder="00000-000">
                            <x-error>cep</x-error>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" name="telefone" id="telefone" value="{{ old('telefone') }}"
                                class="form-control @error('telefone') is-invalid @enderror"
                                placeholder="(00) 00000-0000">
                            <x-error>telefone</x-error>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror">
                            <x-error>email</x-error>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.estudante.index') }}" class="btn btn-secondary">
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

