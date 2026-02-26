<x-admin>
    @section('title', 'Meu Perfil')

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user me-2"></i>Informações do Perfil
                    </h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock me-2"></i>Alterar Senha
                    </h3>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-admin>
