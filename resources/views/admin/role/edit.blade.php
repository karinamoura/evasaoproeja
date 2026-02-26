<x-admin>
    @section('title','Editar Perfil')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-shield me-2"></i>Editar Perfil
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.role.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form action="{{ route('admin.role.store') }}" method="POST"
                        class="needs-validation" novalidate="">
                        @csrf
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Perfil</label>
                                        <input type="text" class="form-control" name="name" id="name"
                                            required="" value="{{ old('name', $data->name) }}">
                                            <x-error>name</x-error>
                                        <div class="invalid-feedback">Perfil é obrigatório.</div>
                                    </div>
                                </div>
                            </div>

                            @php $selectedPermissions = old('permissions', $data->permissions->pluck('id')->toArray()); @endphp
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">Permissões por módulo</label>
                                        <p class="text-muted small mb-2">Altere as permissões que este perfil tem em cada área do sistema.</p>
                                        <div class="card card-outline card-info">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <i class="fas fa-key me-2"></i>Módulos e permissões
                                                </h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selecionarTodasPermissoes()">
                                                        <i class="fas fa-check-square"></i> Todas
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="desmarcarTodasPermissoes()">
                                                        <i class="fas fa-square"></i> Nenhuma
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body" style="max-height: 520px; overflow-y: auto;">
                                                @if(!empty($groupedPermissions))
                                                    @foreach($groupedPermissions as $moduleName => $permissionList)
                                                        @if($permissionList->isNotEmpty())
                                                            @php $moduleId = \Illuminate\Support\Str::slug($moduleName); @endphp
                                                            <div class="border rounded p-3 mb-3 bg-light" data-module="{{ $moduleId }}">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <strong class="text-primary">{{ $moduleName }}</strong>
                                                                    <span>
                                                                        <button type="button" class="btn btn-xs btn-outline-primary" onclick="selecionarModulo('{{ $moduleId }}')">Marcar</button>
                                                                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="desmarcarModulo('{{ $moduleId }}')">Desmarcar</button>
                                                                    </span>
                                                                </div>
                                                                <div class="row">
                                                                    @foreach($permissionList as $permission)
                                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                                            <div class="icheck-primary">
                                                                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                                                       id="permission_{{ $permission->id }}" class="perm-module-{{ $moduleId }}"
                                                                                       {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                                                                                <label for="permission_{{ $permission->id }}" class="mb-0 small">{{ $permission->name }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Nenhuma permissão cadastrada.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <x-error>permissions</x-error>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-12 text-right">
                                    <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
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

    @section('js')
        <script>
            function selecionarTodasPermissoes() {
                document.querySelectorAll('input[name="permissions[]"]').forEach(function(cb) { cb.checked = true; });
            }
            function desmarcarTodasPermissoes() {
                document.querySelectorAll('input[name="permissions[]"]').forEach(function(cb) { cb.checked = false; });
            }
            function selecionarModulo(moduleId) {
                document.querySelectorAll('.perm-module-' + moduleId).forEach(function(cb) { cb.checked = true; });
            }
            function desmarcarModulo(moduleId) {
                document.querySelectorAll('.perm-module-' + moduleId).forEach(function(cb) { cb.checked = false; });
            }
        </script>
    @endsection
</x-admin>
