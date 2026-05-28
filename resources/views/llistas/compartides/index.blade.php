@extends('layouts.app')

@section('content')
<div class="container py-5" style="max-width: 900px;">
    
    <h1 class="mb-4 text-center fw-bold text-primary">
        Llistes compartides amb mi
    </h1>

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
                        <div class="text-muted small">
                            @php
                                $compartida = \App\Models\LlistaCompartida::with('remitente')
                                    ->where('llista_original_id', $llista->id)
                                    ->where('receptor_id', auth()->id())
                                    ->first();
                            @endphp

                            @if($compartida)
                                Compartida per: {{ $compartida->remitente->name }}
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('llistas.compartidas.show', $llista->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        @empty
            <div class="text-center">
                <p class="text-muted">No tens cap llista compartida amb tu.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection