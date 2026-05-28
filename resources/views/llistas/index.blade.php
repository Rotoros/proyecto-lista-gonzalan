@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    
    <h1 class="mb-4 text-center fw-bold text-primary">
        Les meves llistes de compres
    </h1>

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('llistas.index') }}" class="mb-4 d-flex gap-2">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Cercar llista..." 
            value="{{ request('search') }}"
        >

        <button class="btn btn-primary">
            <i class="bi bi-search"></i>
        </button>

        @if(request('search'))
            <a href="{{ route('llistas.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-lg"></i>
            </a>
        @endif
    </form>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="row g-3">
        @forelse($llistas as $llista)
            <div class="col-12">
                <div class="card shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">

                        {{-- INFO --}}
                        <div>
                            <h5 class="mb-1 fw-bold">{{ $llista->titol }}</h5>

                            @if($llista->categoria)
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $llista->categoria->nom }}
                                </span>
                            @endif
                        </div>

                        {{-- BOTONES --}}
                        <div class="d-flex gap-2">

                            {{-- VER LISTA --}}
                            <a href="{{ route('llistas.show', $llista->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- EDITAR --}}
                            <a href="{{ route('llistas.edit', $llista->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            {{-- COMPARTIR --}}
                            <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#share{{ $llista->id }}">
                                <i class="bi bi-share"></i>
                            </button>

                            {{-- ELIMINAR --}}
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete{{ $llista->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            {{-- MODAL DELETE --}}
            <div class="modal fade" id="delete{{ $llista->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5>Eliminar llista</h5>
                            <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            Segur que vols eliminar <strong>{{ $llista->titol }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cancel·lar
                            </button>

                            <form action="{{ route('llistas.destroy', $llista->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL SHARE --}}
            <div class="modal fade" id="share{{ $llista->id }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-share me-2"></i>
                    Compartir llista
                </h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            {{-- BODY --}}
            <div class="modal-body p-4">

                <p class="text-muted small mb-3">
                    Escriu el correu de la persona amb qui vols compartir la llista:
                </p>

                <form action="{{ route('llistas.compartir', $llista->id) }}" method="POST">
                    @csrf

                    {{-- INPUT --}}
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input 
                            type="email" 
                            name="email" 
                            class="form-control" 
                            placeholder="email@exemple.com" 
                            required
                        >
                    </div>

                    {{-- BOTÓN --}}
                    <button class="btn btn-info w-100 text-white fw-bold py-2">
                        <i class="bi bi-send me-1"></i>
                        Enviar invitació
                    </button>
                </form>

                {{-- INFO EXTRA --}}
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        La persona podrà veure i editar la llista
                    </small>
                </div>

            </div>

        </div>
    </div>
</div>

        @empty
            <div class="text-center">
                <p>No tens llistes</p>
            </div>
        @endforelse
    </div>

   @if ($llistas->lastPage() > 1)
    <div class="d-flex justify-content-center mt-4">
        <nav>
            <ul class="pagination">

                @for ($i = 1; $i <= $llistas->lastPage(); $i++)
                    <li class="page-item {{ $llistas->currentPage() == $i ? 'active' : '' }}">
                        <a class="page-link" href="{{ $llistas->url($i) }}">
                            {{ $i }}
                        </a>
                    </li>
                @endfor

            </ul>
        </nav>
    </div>
   @endif

    <div class="text-center mt-5">
        <a href="{{ route('llistas.create') }}" class="btn btn-success">
            Nova llista
        </a>
    </div>

</div>
@endsection
