<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Transaksi extends Model
{
    /** @use HasFactory<\Database\Factories\TransaksiFactory> */
    use HasFactory, Sluggable;

    protected $guarded = ['id'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nomor_transaksi'
            ]
        ];
    }
    public function items()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public static function generateNomorTransaksi()
    {
        $today = Carbon::now()->format('ymd'); // YYMMDD
        $prefix = 'RL' . $today;

        // Ambil nomor transaksi terakhir hari ini
        $lastTransaksi = self::where('nomor_transaksi', 'like', $prefix . '%')
            ->orderBy('nomor_transaksi', 'desc')
            ->first();

        if ($lastTransaksi) {
            $lastNumber = (int) substr($lastTransaksi->nomor_transaksi, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    public function casts()
    {
        return [
            'selesai' => 'datetime',
            'diambil' => 'datetime',
        ];
    }

    public function scopeFilters(Builder $query, array $filters)
    {
        $query->when($filters["nama"] ?? false, function ($query, $search) {
            return $query->where("nama", 'like', "%$search%");
        });

        $query->when($filters["date"] ?? false, function ($query, $search) {
            return $query->whereDate("created_at", $search);
        });

        $query->when(isset($filters['pembayaran']) && $filters['pembayaran'] != '', function ($query) use ($filters) {
            return $query->where('pembayaran', $filters['pembayaran']);
        });

        $query->when(isset($filters['status']) && $filters['status'] != '',     function ($query) use ($filters) {
        return $query->where('status', $filters['status']);
    });
    }
}
