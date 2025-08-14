<?php

namespace App\Livewire;

use Livewire\Component;

class Whatsapp extends Component
{
    public $slug;

    public function mount($slug) {

    }

    public function render()
    {
        return view('livewire.whatsapp');
    }
}
