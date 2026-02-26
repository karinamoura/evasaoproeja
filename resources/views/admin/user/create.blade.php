<x-admin>
    @section('title', 'Cadastrar Usuário')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-plus me-2"></i>Cadastrar Novo Usuário
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.user.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.user.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   id="name"
                                   required
                                   value="{{ old('name') }}"
                                   placeholder="Nome completo do usuário">
                            <x-error>name</x-error>
                            <div class="invalid-feedback">O nome é obrigatório.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   id="email"
                                   required
                                   value="{{ old('email') }}"
                                   placeholder="usuario@exemplo.com">
                            <x-error>email</x-error>
                            <div class="invalid-feedback">O e-mail é obrigatório e deve ser válido.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Senha <span class="text-danger">*</span></label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password"
                                   id="password"
                                   required
                                   minlength="6"
                                   placeholder="Mínimo de 6 caracteres">
                            <x-error>password</x-error>
                            <small class="form-text text-muted">Mínimo de 6 caracteres</small>
                            <div class="invalid-feedback">A senha é obrigatória (mínimo 6 caracteres).</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role" class="form-label">Perfil <span class="text-danger">*</span></label>
                            <select name="role"
                                    id="role"
                                    class="form-control @error('role') is-invalid @enderror"
                                    required>
                                <option value="">Selecione um perfil</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $role->name == old('role') ? 'selected' : '' }}>
                                        {{ $roleLabels[$role->name] ?? $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>role</x-error>
                            <div class="invalid-feedback">O perfil é obrigatório.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
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
