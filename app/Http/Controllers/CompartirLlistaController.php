<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Llista;
use App\Models\LlistaCompartida;
use Illuminate\Http\Request;

class CompartirLlistaController extends Controller
{
    public function compartir(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $receptor = User::where('email', $request->email)->first();
        $llistaOriginal = Llista::findOrFail($id);

        // Si ya se compartió con este usuario, no duplicamos el registro
        $existe = LlistaCompartida::where('llista_original_id', $llistaOriginal->id)
            ->where('receptor_id', $receptor->id)
            ->exists();

        if (!$existe) {
            LlistaCompartida::create([
                'remitente_id' => auth()->id(),
                'receptor_id' => $receptor->id,
                'llista_original_id' => $llistaOriginal->id,
            ]);
        }

        return back()->with('success', 'Llista compartida correctament.');
    }

    public function recibidas()
    {
        // Buscar las listas que han compartido conmigo
        $llistaIds = LlistaCompartida::where('receptor_id', auth()->id())
            ->pluck('llista_original_id');

        // Cargar las listas originales (no copias)
        $llistas = Llista::with(['productes', 'categoria'])
            ->whereIn('id', $llistaIds)
            ->get();

        return view('llistas.compartides.index', compact('llistas'));
    }
    public function show($id)
    {
        // Verificar que esta lista está compartida con el usuario
        $existe = LlistaCompartida::where('receptor_id', auth()->id())
            ->where('llista_original_id', $id)
            ->exists();

        if (!$existe) {
            abort(403);
        }

        // Cargar la lista con relaciones
        $llista = Llista::with(['productes', 'categoria'])
            ->findOrFail($id);

        return view('llistas.compartides.show', compact('llista'));
    }
}
