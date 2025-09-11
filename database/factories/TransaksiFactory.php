<?php

namespace Database\Factories;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaksi>
 */
class TransaksiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'nomor_transaksi' => Transaksi::generateNomorTransaksi(),
            'whatsapp' => fake()->phoneNumber(),
            'status' => fake()->randomElement(['diterima', 'diproses', 'selesai', 'diambil']),
            'pembayaran' => mt_rand(0, 1),
            'total' => 0,
        ];
    }
}
