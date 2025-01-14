<?php

namespace App\Livewire;

use App\Models\Vacante;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Notifications\NuevoCandidato;

class PostularVacante extends Component
{
    use WithFileUploads;
    public $cv;
    public $vacante;

    protected $rules = [
        'cv' => 'required|mimes:pdf',
    ];
    public function mount(Vacante $vacante)
    {
        $this->vacante = $vacante;
    }
    public function postularme()
    {
        $datos = $this->validate();
        //Almacenar el cv
        $cv = $this->cv->store('cv', 'public');
        $datos['cv'] = str_replace('cv/', '', $cv);
        //Crear candidato
        $this->vacante->candidatos()->create([
            'user_id' => auth()->user()->id,
            'cv' => $datos['cv'],
        ]);
        //Crear notificacion y enviar email
        $this->vacante->reclutador->notify(new NuevoCandidato($this->vacante->id, $this->vacante->titulo, auth()->user()->id));
        //Mostrar al usuario un mensaje de exito
        session()->flash('message', 'Se envio correctamente tu información, mucha suerte');
        return redirect()->back();
    }
    public function render()
    {
        return view('livewire.postular-vacante');
    }
}
