<x-admin>
    @section('title', 'Visualizar Termos e Condições')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-file-contract me-2"></i>Visualizar Termo e Condições
            </h3>
            <div class="card-tools">
                <a href="{{ route('admin.termo-condicao.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-12">
                    <h4>{{ $termo->titulo }}</h4>
                    <div class="mb-2">
                        @if($termo->ativo)
                            <span class="badge badge-success">Ativo</span>
                        @else
                            <span class="badge badge-secondary">Inativo</span>
                        @endif
                        <span class="badge badge-info ml-2">{{ $termo->questionarioOfertas->count() }} questionário(s) usando</span>
                    </div>
                    <small class="text-muted">
                        Criado em: {{ $termo->created_at->format('d/m/Y H:i') }} |
                        Atualizado em: {{ $termo->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-12">
                    <h5>Conteúdo:</h5>
                    <div class="border p-4 bg-light rounded">
                        {!! $termo->conteudo !!}
                    </div>
                </div>
            </div>

            @if($termo->questionarioOfertas->count() > 0)
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h5>Questionários que utilizam este termo:</h5>
                        <ul class="list-group">
                            @foreach($termo->questionarioOfertas as $questionarioOferta)
                                <li class="list-group-item">
                                    <a href="{{ route('admin.questionario-oferta.show', $questionarioOferta->id) }}">
                                        <strong>{{ $questionarioOferta->titulo }}</strong>
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $questionarioOferta->oferta->name }} - {{ $questionarioOferta->oferta->institution->name }}
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="{{ route('admin.termo-condicao.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Voltar
                    </a>
                    <a href="{{ route('admin.termo-condicao.edit', $termo->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Editar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin>

