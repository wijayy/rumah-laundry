<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\Transaksi;
use Carbon\Carbon;
use Livewire\Component;

class TransaksiIndex extends Component
{
    public $date, $transaksi;

    public function mount($date = null)
    {
        $this->date = $date ?? date('Y-m-d');

        $this->transaksi = Transaksi::whereDate('created_at', $this->date)->get();
    }

    public function updateDate()
    {
        return redirect(route('transaksi.index', ['date'=>$this->date]));
    }
    public function diproses($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->status = 'diproses';
        $transaksi->save();

        $this->updateDate();

    }
    public function selesai($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->status = 'selesai';
        $transaksi->save();

        $this->updateDate();
    }
    public function diambil($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->status = 'diambil';
        $transaksi->pembayaran = 1;
        $transaksi->diambil = Carbon::now();
        $transaksi->save();

        $this->updateDate();
    }

    public function render()
    {
        return view('livewire.transaksi-index')->layout('components.layouts.app', ['title' => "Transaksi"]);
    }
}
