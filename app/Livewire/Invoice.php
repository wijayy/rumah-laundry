<?php

namespace App\Livewire;

use App\Models\Transaksi;
use Livewire\Component;

class Invoice extends Component
{
    public $transaksi;

    public function mount($slug)
    {
        try {
            $this->transaksi = Transaksi::where('slug', $slug)->firstOrFail();
        } catch (\Throwable $th) {
           return redirect(route('home'))->with('error', 'Nomor Transaksi Tidak Ditemukan');
        }
    }


    public function render()
    {
        return view('livewire.invoice')->layout('components.layouts.invoice', ['title'=> "Invoice {$this->transaksi->nomor_transaksi}"]);
    }
}
