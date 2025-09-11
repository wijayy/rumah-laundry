<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'is_admin' => 1,
        ]);

        $services = [
            ['nama' => 'cuci kiloan', 'harga' => 6000, 'satuan' => 'kg'],
            ['nama' => 'cuci satuan', 'harga' => 500, 'satuan' => 'pcs'],
            ['nama' => 'shoes cleaning', 'harga' => 30000, 'satuan' => 'pasang'],
            ['nama' => 'boneka', 'harga' => 40000, 'satuan' => 'pcs'],
            ['nama' => 'tas', 'harga' => 15000, 'satuan' => 'pcs'],
            ['nama' => 'bantal', 'harga' => 10000, 'satuan' => 'pcs'],
        ];
        foreach ($services as $key => $item) {
            Service::factory(1)->create($item);
        }

        foreach (range(0, 20) as $key => $item) {
            $createdAt = Carbon::now()->subDays(rand(0, 7))->setTime(rand(8, 22), rand(0, 59), rand(0, 59));
            $transaksi = Transaksi::create([
                'nama' => fake()->name(),
                'nomor_transaksi' => Transaksi::generateNomorTransaksi(),
                'whatsapp' => fake()->phoneNumber(),
                'status' => fake()->randomElement(['diterima', 'diproses', 'selesai', 'diambil']),
                'pembayaran' => mt_rand(0, 1),
                'total' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'selesai' => $createdAt->copy()->addDays(2),
            ]);

            $total = 0;

            foreach (range(1, mt_rand(1, 5)) as $key => $item) {
                $service = Service::inRandomOrder()->first();
                $jumlah = mt_rand(1, 5);
                $total += $service->harga * $jumlah;

                
                TransaksiDetail::factory()->recycle([$transaksi, $service])->create([
                    'jumlah' => $jumlah,
                    'harga' => $service->harga,
                    'subtotal' => $jumlah * $service->harga,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // update total di instance model (bukan collection)
            $transaksi->update(['total' => $total]);
        }
    }
}
