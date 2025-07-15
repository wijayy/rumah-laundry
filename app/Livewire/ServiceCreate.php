<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ServiceCreate extends Component
{
    public $title, $stn = ['kg', 'pcs'], $service;

    #[Validate('required|string')]
    public $nama = '';

    #[Validate('required|integer')]
    public $harga = '';

    #[Validate('required')]
    public $satuan = '';

    public function mount($slug = null)
    {
        if ($slug ?? false) {
            $this->service = Service::where('slug', $slug)->firstOrFail();

            $this->nama = $this->service->nama;
            $this->harga = $this->service->harga;
            $this->satuan = $this->service->satuan;

            $this->title = "Edit $this->nama";
        } else {
            $this->title = "Tambah Service Baru";
        }
    }

    public function save()
    {
        $validated = $this->validate();
        try {
            DB::beginTransaction();
            Service::updateOrCreate(
                ['id' => $this->service?->id],
                [
                    'nama' => $this->nama,
                    'harga' => $this->harga,
                    'satuan' => $this->satuan,
                ]
            );
            DB::commit();
            session()->flash('success', $this->service ? 'Data berhasil diubah' : 'Data berhasil ditambahkan');
            return redirect()->route('service.index');
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
        return view('livewire.service-create')->layout('components.layouts.app', ['title' => $this->title]);
    }
}
