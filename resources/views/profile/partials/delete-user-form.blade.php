<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Excluir Conta') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Uma vez que sua conta seja excluída, todos os seus recursos e dados serão permanentemente excluídos. Antes de excluir sua conta, faça o download de quaisquer dados ou informações que você deseja manter.') }}
        </p>
    </header>

        <button class="btn btn-danger" x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" data-toggle="modal"
        data-target="#modal-default">{{ __('Excluir Conta') }}</button>

    <div class="modal fade" id="modal-default" name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()"
        focusable>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Excluir Conta</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('admin.profile.destroy') }}" class="p-6">
                        @csrf
                        @method('delete')

                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Tem certeza de que deseja excluir sua conta?') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __('Uma vez que sua conta seja excluída, todos os seus recursos e dados serão permanentemente excluídos. Por favor, digite sua senha para confirmar que deseja excluir permanentemente sua conta.') }}
                        </p>

                        <div class="mt-6">
                            <x-input-label for="password" value="{{ __('Senha') }}" class="form-label" />

                            <x-text-input id="password" name="password" type="password"
                                class="color-dark form-control" placeholder="{{ __('Senha') }}" required />

                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                        </div>

                        <div class="mt-6 flex justify-end">
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-secondary" data-dismiss="modal" x-on:click="$dispatch('close')">
                        {{ __('Cancelar') }}
                    </button>

                    <button class="btn btn-danger" type="submit">
                        {{ __('Excluir Conta') }}
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</section>
