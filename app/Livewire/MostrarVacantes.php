<?php

namespace App\Livewire;

use App\Models\Vacante;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class MostrarVacantes extends Component
{
    protected $listeners = ['eliminarVacante'];

    public function eliminarVacante( Vacante $vacante)
    {
        Gate::authorize('delete', $vacante);
        $vacante->delete();
    }

    public function render()
    {
        $vacantes = Vacante::where('user_id', auth()->user()->id)->paginate(10);
        return view('livewire.mostrar-vacantes', [
            'vacantes' => $vacantes
        ]);
    }
}
