<x-guest-layout title="Entrar">

    <div class="guest-card card">
        <div class="card-header">
            <a href="{{ url('/') }}" class="brand">
                <span class="brand-icon-e">E</span>{{ config('app.name') }}
            </a>
        </div>
        <div class="card-body">
            <div class="guest-card-intro">
                <h2 class="guest-intro-title">Entrar</h2>
                <p class="guest-intro-subtitle">Use seu e-mail e senha para acessar o sistema.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 list-unstyled">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="guest-input-group">
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="E-mail" required autofocus autocomplete="username">
                    <span class="input-icon"><i class="fas fa-envelope"></i></span>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="guest-input-group">
                    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           placeholder="Senha" required autocomplete="current-password">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="remember" id="remember" class="form-check-input">
                        <label for="remember" class="form-check-label">Lembrar de mim</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Entrar
                </button>

                <p class="mb-0 text-center">
                    <a href="{{ route('password.request') }}" class="btn-link-forgot">Esqueceu a senha?</a>
                </p>
                <p class="mb-0 text-center mt-2">
                    <a href="{{ route('sobre') }}" class="btn-link-forgot small">Sobre o sistema</a>
                </p>
            </form>
        </div>
    </div>
</x-guest-layout>
