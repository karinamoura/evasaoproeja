<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('admin.profile.update') }}">
    @csrf
    @method('patch')

    <div class="form-group">
        <label for="name" class="form-label">Nome</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="email" class="form-label">E-mail</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <small class="form-text text-muted d-block mt-1">
                Seu e-mail não foi verificado.
                <button form="send-verification" type="submit" class="btn btn-link p-0 align-baseline">
                    Reenviar e-mail de verificação
                </button>
            </small>
            @if (session('status') === 'verification-link-sent')
                <small class="form-text text-success d-block mt-1">
                    Um novo link de verificação foi enviado para o seu e-mail.
                </small>
            @endif
        @endif
    </div>

    <div class="form-group">
        <label for="mode" class="form-label">Tema da interface</label>
        <select name="mode" id="mode" class="form-control">
            <option value="light" {{ Auth::user()->mode == 'light' ? 'selected' : '' }}>Claro</option>
            <option value="dark" {{ Auth::user()->mode == 'dark' ? 'selected' : '' }}>Escuro</option>
        </select>
        @error('mode')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success py-2 mb-3">
            Perfil atualizado com sucesso.
        </div>
    @endif

    <div class="pt-2">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i>Salvar
        </button>
    </div>
</form>
