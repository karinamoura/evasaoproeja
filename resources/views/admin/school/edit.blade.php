<x-admin>
    @section('title','Editar Escola')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-school me-2"></i>Editar Escola
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.escola.index') }}" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <form action="{{ route('admin.escola.update',$data) }}" method="POST" class="needs-validation" novalidate>
            @method('PUT')
            @csrf
            <input type="hidden" name="id" value="{{ $data->id }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution" class="form-label">Campus <span class="text-danger">*</span></label>
                            <select name="institution"
                                    id="institution"
                                    class="form-control @error('institution') is-invalid @enderror"
                                    required>
                                <option value="">Selecione o campus</option>
                                @foreach ($institution as $inst)
                                    <option value="{{ $inst->id }}"
                                        {{ ($inst->id == $data->institution_id) || (old('institution') == $inst->id) ? 'selected' : '' }}>
                                        {{ $inst->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-error>institution</x-error>
                            <div class="invalid-feedback">O campus é obrigatório.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Nome da Escola <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   placeholder="Nome da escola"
                                   required
                                   value="{{ old('name', $data->name) }}">
                            <x-error>name</x-error>
                            <div class="invalid-feedback">O nome da escola é obrigatório.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-12 text-right">
                        <a href="{{ route('admin.escola.index') }}" class="btn btn-secondary">
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
