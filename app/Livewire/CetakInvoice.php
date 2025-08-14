<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Component;

class CetakInvoice extends Component
{
    public $transaksi;

    public function mount($slug) {
        try {
            $this->transaksi = Transaksi::where('slug', $slug)->firstOrFail();

        } catch (\Throwable $th) {
            return redirect(route('transaksi.index'));
        }
    }

    public function render()
    {
        return view('livewire.cetak-invoice');
    }
}
