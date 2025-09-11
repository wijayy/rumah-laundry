<?php

namespace App\Livewire;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ServiceIndex extends Component
{

    public $services;

public function mount()
{
    $this->services = Service::all();
}

public function deleteService($key)
{
    // dd($key);
    try {
        DB::beginTransaction();
        $service = Service::findOrFail($key);
        $service->delete();

        DB::commit();
        return redirect(route('service.index'))->with('success', 'Service berhasil dihapus');
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
        return view('livewire.service-index')->layout('components.layouts.app', ['title' => 'Service']);
    }
}
