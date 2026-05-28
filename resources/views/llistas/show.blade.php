@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 1000px;">

    {{-- VOLVER --}}
    <div class="mb-3">
        <a href="{{ route('llistas.index') }}" class="btn btn-secondary btn-sm">
            ← Tornar a les llistes
        </a>
    </div>

    {{-- TITULO --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $llista->titol }}</h2>

            @if($llista->categoria)
                <span class="badge bg-primary-subtle text-primary">
                    {{ $llista->categoria->nom }}
                </span>
            @endif
        </div>
    </div>

    {{-- MENSAJE --}}
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    @php
        $pendents = $llista->productes->where('comprat', false);
        $comprats = $llista->productes->where('comprat', true);
    @endphp

    {{-- PRODUCTOS PENDIENTES --}}
    <ul class="list-group mb-4">

        @forelse($pendents as $producte)
        <li class="list-group-item">
            <div class="row align-items-center g-2">

                {{-- CHECK --}}
                <div class="col-auto">
                    <form action="{{ route('productes.toggleComprat', $producte->id) }}" method="POST">
                        @csrf
                        <input type="checkbox" onchange="this.form.submit()" {{ $producte->comprat ? 'checked' : '' }}>
                    </form>
                </div>

                {{-- FORM UPDATE --}}
                <form id="form-update-{{ $producte->id }}" 
                      action="{{ route('productes.update', $producte->id) }}" 
                      method="POST" 
                      class="col d-flex gap-2">
                    @csrf
                    @method('PUT')

                    <input type="text" name="nom" value="{{ $producte->nom }}" class="form-control form-control-sm" required>

                    <input type="number" name="quantitat" value="{{ $producte->quantitat }}" class="form-control form-control-sm" style="max-width:90px" min="1">

                    <select name="categoria_id" class="form-select form-select-sm" style="max-width:160px">
                        <option value="">Categoria</option>
                        @foreach(\App\Models\Categoria::where('user_id', auth()->id())->get() as $categoria)
                            <option value="{{ $categoria->id }}" {{ $producte->categoria_id == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nom }}
                            </option>
                        @endforeach
                    </select>
                </form>

                {{-- BOTONES --}}
                <div class="col-auto d-flex gap-1">

                    <button form="form-update-{{ $producte->id }}" class="btn btn-success btn-sm">
                        <i class="bi bi-check2"></i>
                    </button>

                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteProduct{{ $producte->id }}">
                        <i class="bi bi-trash3"></i>
                    </button>

                </div>

            </div>
        </li>

        {{-- MODAL DELETE --}}
        <div class="modal fade" id="deleteProduct{{ $producte->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5>Eliminar producte</h5>
                        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        Segur que vols eliminar <strong>{{ $producte->nom }}</strong>?
                    </div>
                   <div class="modal-footer d-flex justify-content-between">
    
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel·lar
                        </button>
                        <form action="{{ route('productes.destroy', $producte->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @empty
            <li class="list-group-item text-center text-muted">
                No hi ha productes encara.
            </li>
        @endforelse
    </ul>
     {{-- PRODUCTOS AÑADIDOS --}}
    <form action="{{ route('productes.store') }}" method="POST" class="d-flex gap-2">
        @csrf
        <input type="hidden" name="llista_id" value="{{ $llista->id }}">

        <input type="text" name="nom" class="form-control" placeholder="Nom del producte" required>

        <input type="number" name="quantitat" class="form-control" value="1" min="1" style="max-width:120px">

        <button class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Afegir
        </button>
    </form>

    {{-- PRODUCTOS COMPRADOS --}}
    @if($comprats->count())

        <h4 class="fw-bold mt-5 mb-3 text-success">
            <i class="bi bi-basket2-fill"></i>
            Productes afegits a la cistella
        </h4>

        <ul class="list-group mb-4">

            @foreach($comprats as $producte)

                <li class="list-group-item bg-light">
                    <div class="row align-items-center g-2">

                        {{-- CHECK --}}
                        <div class="col-auto">
                            <form action="{{ route('productes.toggleComprat', $producte->id) }}" method="POST">
                                @csrf
                                <input type="checkbox" onchange="this.form.submit()" checked>
                            </form>
                        </div>

                        {{-- NOMBRE --}}
                        <div class="col">
                            <span class="text-decoration-line-through text-muted">
                                {{ $producte->nom }}
                            </span>
                        </div>

                        {{-- CANTIDAD --}}
                        <div class="col-auto">
                            <span class="badge bg-success">
                                x{{ $producte->quantitat }}
                            </span>
                        </div>

                    </div>
                </li>

            @endforeach

        </ul>

    @endif

    

</div>
@endsection