<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="form-group">
        <label for="current_password" class="form-label">Senha atual</label>
        <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Nova senha</label>
        <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
        <small class="form-text text-muted">Mínimo de 8 caracteres</small>
        @error('password', 'updatePassword')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation" class="form-label">Confirmar nova senha</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success py-2 mb-3">
            Senha alterada com sucesso.
        </div>
    @endif

    <div class="pt-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Alterar senha
        </button>
    </div>
</form>
