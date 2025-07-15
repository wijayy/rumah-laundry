<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    /** @use HasFactory<\Database\Factories\TransaksiDetailFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
