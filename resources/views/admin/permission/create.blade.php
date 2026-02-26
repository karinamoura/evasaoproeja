<x-admin>
    @section('title','Cadastrar Permissão')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-key me-2"></i>Cadastrar Nova Permissão
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.permission.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.permission.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome da Permissão <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   name="name"
                                   id="name"
                                   required
                                   value="{{ old('name') }}"
                                   placeholder="Ex: criar-usuario, editar-produto, etc.">
                            <x-error>name</x-error>
                            <small class="form-text text-muted">Use letras minúsculas, números e hífens. Exemplo: gerenciar-permissoes</small>
                            <div class="invalid-feedback">O nome da permissão é obrigatório.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.permission.index') }}" class="btn btn-secondary">
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
