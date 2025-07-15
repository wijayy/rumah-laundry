<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Home extends Component
{
    #[Validate('required|string')]
    public $nomor_transaksi = '';
    public function render()
    {
        return view('livewire.home')->layout('components.layouts.invoice');
    }

    public function save() {
        $this->validate();

        try {
            $transaksi = Transaksi::where('nomor_transaksi', $this->nomor_transaksi)->firstOrFail();

            return redirect(route('invoice', ['slug'=>$this->nomor_transaksi]));
        } catch (\Throwable $th) {
            $this->dispatch('error');
        }
    }
}
