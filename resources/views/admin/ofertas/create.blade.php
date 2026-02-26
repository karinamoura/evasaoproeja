<x-admin>
    @section('title', 'Cadastrar Oferta')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-graduation-cap me-2"></i>Cadastrar Oferta
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.oferta.index') }}" class="btn btn-sm btn-secondary">
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

        <form action="{{ route('admin.oferta.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <!-- Informações Básicas -->
                <div class="form-section-header mb-3">
                    <i class="fas fa-info-circle me-2"></i>Informações Básicas
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" required>
                            <x-error>name</x-error>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="coordenador" class="form-label">Coordenador</label>
                            <select name="coordenador" id="coordenador" class="form-control @error('coordenador') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione o Coordenador</option>
                                @foreach ($users as $user)
                                    <option {{ old('coordenador') == $user->id ? 'selected' : '' }}
                                        value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <x-error>coordenador</x-error>
                        </div>
                    </div>
                </div>

                <!-- Localização e Turno -->
                <div class="form-section-header mb-3">
                    <i class="fas fa-map-marker-alt me-2"></i>Localização e Turno
                </div>
                <div class="row mb-4">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="category" class="form-label">Instituição</label>
                            <select name="category" id="category" class="form-control @error('category') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione a Instituição</option>
                                @foreach ($institution as $inst)
                                    <option {{ old('category') == $inst->id ? 'selected' : '' }}
                                        value="{{ $inst->id }}">{{ $inst->name }}</option>
                                @endforeach
                            </select>
                            <x-error>category</x-error>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="school" class="form-label">Escola</label>
                            <select name="school" id="school" class="form-control @error('school') is-invalid @enderror">
                                <option value="" selected disabled>Selecione a Escola (Opcional)</option>
                                @foreach ($schools as $school)
                                    <option value="{{ $school->id }}" {{ old('school') == $school->id ? 'selected' : '' }}>
                                        {{ $school->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>school</x-error>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="turno" class="form-label">Turno</label>
                            <select name="turno" id="turno" class="form-control @error('turno') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione o turno da oferta</option>
                                <option value="Matutino" {{ old('turno') == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                                <option value="Vespertino" {{ old('turno') == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                                <option value="Noturno" {{ old('turno') == 'Noturno' ? 'selected' : '' }}>Noturno</option>
                            </select>
                            <x-error>turno</x-error>
                        </div>
                    </div>
                </div>

                <!-- Informações Acadêmicas -->
                <div class="form-section-header mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>Informações Acadêmicas
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="codigo_sistema_academico" class="form-label">Código do Sistema Acadêmico</label>
                            <input type="text" name="codigo_sistema_academico" id="codigo_sistema_academico" value="{{ old('codigo_sistema_academico') }}"
                                class="form-control @error('codigo_sistema_academico') is-invalid @enderror" required>
                            <x-error>codigo_sistema_academico</x-error>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="turma" class="form-label">Turma</label>
                            <input type="text" name="turma" id="turma" value="{{ old('turma') }}"
                                class="form-control @error('turma') is-invalid @enderror" required>
                            <x-error>turma</x-error>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="nome_curso" class="form-label">Nome do Curso</label>
                            <input type="text" name="nome_curso" id="nome_curso" value="{{ old('nome_curso') }}"
                                class="form-control @error('nome_curso') is-invalid @enderror" required>
                            <x-error>nome_curso</x-error>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="ano_letivo" class="form-label">Ano Letivo</label>
                            <select name="ano_letivo" id="ano_letivo" class="form-control @error('ano_letivo') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione o ano letivo</option>
                                <option value="2024" {{ old('ano_letivo') == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ old('ano_letivo') == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2026" {{ old('ano_letivo') == '2026' ? 'selected' : '' }}>2026</option>
                            </select>
                            <x-error>ano_letivo</x-error>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="periodo_letivo" class="form-label">Período Letivo</label>
                            <select name="periodo_letivo" id="periodo_letivo" class="form-control @error('periodo_letivo') is-invalid @enderror" required>
                                <option value="" selected disabled>Selecione o período letivo</option>
                                <option value="1º Semestre" {{ old('periodo_letivo') == '1º Semestre' ? 'selected' : '' }}>1º Semestre</option>
                                <option value="2º Semestre" {{ old('periodo_letivo') == '2º Semestre' ? 'selected' : '' }}>2º Semestre</option>
                                <option value="Anual" {{ old('periodo_letivo') == 'Anual' ? 'selected' : '' }}>Anual</option>
                            </select>
                            <x-error>periodo_letivo</x-error>
                        </div>
                    </div>
                </div>

                <!-- Transporte e Auxílio -->
                <div class="form-section-header mb-3">
                    <i class="fas fa-bus me-2"></i>Transporte e Auxílio
                </div>
                <div class="row mb-4">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="responsavel_transporte_estudante" class="form-label">Responsável pelo transporte do estudante</label>
                            <select name="responsavel_transporte_estudante" id="responsavel_transporte_estudante" class="form-control @error('responsavel_transporte_estudante') is-invalid @enderror">
                                <option value="" selected disabled>Selecione a opção</option>
                                <option value="Instituição" {{ old('responsavel_transporte_estudante') == 'Instituição' ? 'selected' : '' }}>Instituição</option>
                                <option value="Escola" {{ old('responsavel_transporte_estudante') == 'Escola' ? 'selected' : '' }}>Escola</option>
                                <option value="Não há disponibilização de transporte" {{ old('responsavel_transporte_estudante') == 'Não há disponibilização de transporte' ? 'selected' : '' }}>Não há disponibilização de transporte</option>
                            </select>
                            <x-error>responsavel_transporte_estudante</x-error>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="oferta_auxilio_financeiro" class="form-label">Há oferta de auxílio financeiro aos estudantes?</label>
                            <select name="oferta_auxilio_financeiro" id="oferta_auxilio_financeiro" class="form-control @error('oferta_auxilio_financeiro') is-invalid @enderror">
                                <option value="" selected disabled>Selecione a opção</option>
                                <option value="Sim" {{ old('oferta_auxilio_financeiro') == 'Sim' ? 'selected' : '' }}>Sim</option>
                                <option value="Não" {{ old('oferta_auxilio_financeiro') == 'Não' ? 'selected' : '' }}>Não</option>
                            </select>
                            <x-error>oferta_auxilio_financeiro</x-error>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.oferta.index') }}" class="btn btn-secondary">
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
