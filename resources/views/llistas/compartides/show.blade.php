@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 1000px;">

    {{-- VOLVER --}}
    <div class="mb-3">
        <a href="{{ route('llistas.compartidas') }}" class="btn btn-secondary btn-sm">
            ← Tornar a compartides
        </a>
    </div>

    {{-- TÍTULO --}}
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

    {{-- PRODUCTOS --}}
    <ul class="list-group mb-4">
        @forelse($llista->productes as $producte)
        <li class="list-group-item">
            <div class="row align-items-center g-2">

                {{-- CHECK --}}
                <div class="col-auto">
                    <form action="{{ route('productes.toggleComprat', $producte->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="compartidas">
                        <input type="checkbox" onchange="this.form.submit()" {{ $producte->comprat ? 'checked' : '' }}>
                    </form>
                </div>

                {{-- FORM UPDATE --}}
                <form 
                    action="{{ route('productes.update', $producte->id) }}" 
                    method="POST" 
                    class="col d-flex gap-2"
                >
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="redirect_to" value="compartidas">

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

                    <form action="{{ route('productes.destroy', $producte->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="redirect_to" value="compartidas">
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>

                </div>

            </div>
        </li>

        @empty
            <li class="list-group-item text-center text-muted">
                No hi ha productes encara.
            </li>
        @endforelse
    </ul>

    {{-- AÑADIR PRODUCTO --}}
    <form action="{{ route('productes.store') }}" method="POST" class="d-flex gap-2">
        @csrf

        <input type="hidden" name="llista_id" value="{{ $llista->id }}">
        <input type="hidden" name="redirect_to" value="compartidas">

        <input type="text" name="nom" class="form-control" placeholder="Nom del producte" required>

        <input type="number" name="quantitat" class="form-control" value="1" min="1" style="max-width:120px">

        <button class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Afegir
        </button>
    </form>

</div>
@endsection