<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\Transaksi;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class TransaksiIndex extends Component
{
    use WithPagination;

    public $date, $nama, $status, $pembayaran, $optionStatus = ['diterima', 'diproses', 'selesai', 'diambil'];

    protected $queryString = [
        'nama' => ['except' => ''],
        'date' => ['except' => ''],
        'status' => ['except' => ''],
        'pembayaran' => ['except' => ''],
    ];
    public function mount()
    {
        $this->transaksi = Transaksi::filters(['date' => $this->date, 'nama'=>$this->nama, 'status' => "$this->status", 'pembayaran' => $this->pembayaran ]);
    }

    public function updateTransaksi()
    {
        return redirect(route('transaksi.index', ['date' => $this->date, 'nama'=>$this->nama, 'status' => $this->status, 'pembayaran' => $this->pembayaran ]));
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
        $transaksi = Transaksi::filters(['date' => $this->date, 'nama'=>$this->nama, 'status' => "$this->status", 'pembayaran' => $this->pembayaran ])->paginate(24);

        return view('livewire.transaksi-index', compact('transaksi'))->layout('components.layouts.app', ['title' => "Transaksi"]);
    }
}
