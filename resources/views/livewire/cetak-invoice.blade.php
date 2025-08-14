<div class="max-w-[80px] text-3xs">
    <div class="text-center">
        <strong class="text-3xs">RUMAH LAUNDRY</strong><br>
        Jl. Contoh Alamat No.123<br>
        Telp: 0812-3456-7890
    </div>

    <flux:separator></flux:separator>

    <div class="text-2xs font-semibold text-center capitalize">{{ $transaksi->nama }}</div>
    <div class="">{{ $transaksi->nomor_transaksi }}</div>

    <table width="100%">
        <tr>
            <td>Status</td>
            <td class="right">{{ $transaksi->status }}</td>
        </tr>
        <tr>
            <td>Pembayaran</td>
            <td class="right">{{ $transaksi->pembayaran ? 'Sudah Bayar' : 'Belum Bayar' }}</td>
        </tr>
        <tr>
            <td>Tgl Masuk</td>
            <td class="right">{{ $transaksi->created_at->format('Y-m-d') }}</td>
        </tr>
        <tr>
            <td>Estimasi</td>
            <td class="right">{{ $transaksi->estimasi_selesai }}</td>
        </tr>
    </table>

    <flux:separator></flux:separator>

    <table width="100%">
        @foreach ($transaksi->items as $item)
            <tr class="odd:pt-2">
                <td>{{ $item->service->nama }}</td>
                <td class="right">{{ $item->jumlah }} {{ $item->service->satuan }}</td>
            </tr>
            <tr>
                <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <flux:separator></flux:separator>

    <div class="flex justify-between">
        <strong>Total</strong>
        <strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
    </div>

    <flux:separator></flux:separator>

    <div class="text-center">
        Terima kasih telah menggunakan jasa kami<br>
        ---- Rumah Laundry ----
    </div>
</div>

<script>
    window.print()
</script>
