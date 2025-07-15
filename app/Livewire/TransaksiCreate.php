<?php

namespace App\Livewire;

use App\Models\Service;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class TransaksiCreate extends Component
{
    public $total = 0, $service;

    #[Validate('required|string')]
    public $nama = '';

    #[Validate('required|array|min:1')]
    public $services = [];

    #[Validate('required')]
    public $payment = 1;

    #[Validate('required')]
    public $selesai = '';

    public function mount()
    {
        $this->service = Service::all();
        $this->addItem();
        $this->selesai = date('Y-m-d');
    }

    public function addItem()
    {
        $service = Service::first();
        $this->services[] = ['id' => $service->id, 'jumlah' => null, 'satuan' => $service->satuan];
        // $this->updatePrice();
        $this->updateTotal();
    }

    public function removeItem($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services); // reindex
        $this->updateTotal();
    }

    public function updateServices()
    {
        $services = [];
        foreach ($this->services as $key => $item) {
            $service = Service::where('id', $item['id'])->firstOrFail();
            $services[] = ['id' => $service->id, 'jumlah' => $item['jumlah'], 'satuan' => $service->satuan];

            // dd($item);
        }
        $this->services = $services;
        $this->updateTotal();
        // dd($this->services);
    }

    public function updateTotal()
    {
        $this->total = 0;
        foreach ($this->services as $key => $item) {
            if (!is_numeric($item['jumlah'])) {
                continue;
            }
            $service = Service::findOrFail($item['id']);
            $this->total += $service->harga * $item['jumlah'];
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $transaksi = Transaksi::create([
                'nomor_transaksi' => Transaksi::generateNomorTransaksi(),
                'nama' => $this->nama,
                'selesai' => $this->selesai,
                'total' => $this->total,
                'pembayaran' => $this->payment,
                'status' => 'diterima'
            ]);

            foreach ($this->services as $key => $item) {
                $service = Service::findOrFail($item['id']);
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'service_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                    'harga' => $service->harga,
                    'subtotal' => $service->harga * $item['jumlah'],
                ]);
            }
            DB::commit();

            return redirect(route('transaksi.index'))->with('success', "Data Berhasil Ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            if (config('app.debug') == true) {
                throw $th;
            } else {
                return back()->with('error', $th->getMessage());
            }
        }
    }

    public function render()
    {
        return view('livewire.transaksi-create')->layout('components.layouts.app', ['title' => "Tambah Transaksi"]);
    }
}
