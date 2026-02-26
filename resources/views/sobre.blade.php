<x-guest-layout title="Sobre" :wide="true">
    <div class="guest-card card">
        <div class="card-header">
            <a href="{{ url('/') }}" class="brand">
                <span class="brand-icon-e">E</span>{{ config('app.name') }}
            </a>
        </div>
        <div class="card-body">
            <h2 class="guest-intro-title">Sobre o sistema</h2>
            <p class="mb-3">
                O <strong>Evasão PROEJA</strong> é um sistema de acompanhamento de evasão no contexto do
                <strong>PROEJA</strong> (Programa Nacional de Integração da Educação Profissional com a Educação Básica
                na Modalidade de Educação de Jovens e Adultos).
            </p>
            <p class="mb-0">
                Permite o cadastro de instituições, ofertas, estudantes e disciplinas, registro de frequência,
                aplicação de questionários e geração de relatórios de evasão e risco.
            </p>
            <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                <i class="fas fa-sign-in-alt me-1"></i> Entrar
            </a>
        </div>
    </div>
</x-guest-layout>
