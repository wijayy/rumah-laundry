<?php

namespace App\Livewire;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public $transaksi, $transaksiDiff, $pendapatan, $pendapatanDiff;

    public $labels = [];
    public $totals = [];
    public $pielabels = [];
    public $sumValues = [];
    public $sumSatuan = [];
    public $countValues = [];

    public function mount()
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();

        // Transaksi bulan ini
        $totalBulanIni = Transaksi::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('total');

        // Transaksi bulan lalu
        $totalBulanLalu = Transaksi::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total');

        // Simpan ke properti
        $this->pendapatan = $totalBulanIni;

        // Hitung persentase
        if ($totalBulanLalu > 0) {
            $this->pendapatanDiff = (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100;
        } else {
            $this->pendapatanDiff = $totalBulanIni > 0 ? 100 : 0;
        }

        $totalBulanIni = Transaksi::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        // Transaksi bulan lalu
        $totalBulanLalu = Transaksi::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        // Simpan ke properti
        $this->transaksi = $totalBulanIni;

        // Hitung persentase
        if ($totalBulanLalu > 0) {
            $this->transaksiDiff = (($totalBulanIni - $totalBulanLalu) / $totalBulanLalu) * 100;
        } else {
            $this->transaksiDiff = $totalBulanIni > 0 ? 100 : 0;
        }

        $data = Transaksi::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM(total) as total")
        )
            ->where('created_at', '>=', now()->subMonthsNoOverflow(12)->startOfMonth())
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month')
            ->get();

        // Format untuk Chart.js


        // Isi data bulan-bulan lengkap meski tidak ada transaksi
        $months = collect(range(0, 11))->map(function ($i) {
            return now()->subMonths(11 - $i)->format('Y-m');
        });

        foreach ($months as $month) {
            $this->labels[] = Carbon::createFromFormat('Y-m', $month)->format('F Y');
            $record = $data->firstWhere('month', $month);
            $this->totals[] = $record ? $record->total : 0;
        }

        // Ambil data SUM(jumlah) per layanan
        $sumData = DB::table('transaksi_details')
            ->join('services', 'transaksi_details.service_id', '=', 'services.id')
            ->select('services.nama', 'services.satuan', DB::raw('SUM(jumlah) as total_jumlah'))
            ->groupBy('services.nama', 'services.satuan')
            ->get();

        // Ambil data COUNT(service_id) per layanan
        $countData = DB::table('transaksi_details')
            ->join('services', 'transaksi_details.service_id', '=', 'services.id')
            ->select('services.nama', DB::raw('COUNT(*) as total_transaksi'))
            ->groupBy('services.nama')
            ->get();

        // Asumsikan label dari sumData dan countData sama urutannya
        $this->pielabels = $sumData->pluck('nama')->toArray();
        $this->sumValues = $sumData->pluck('total_jumlah')->map(fn($v) => (float) $v)->toArray();
        $this->sumSatuan = $sumData->pluck('satuan')->toArray();
        $this->countValues = $countData->pluck('total_transaksi')->map(fn($v) => (int) $v)->toArray();

        // dd($this->bulanLabels, $this->totalPerBulan);
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app', ['title' => "Dashboard"]);
    }
}
