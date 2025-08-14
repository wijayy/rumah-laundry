<div class="text-xs">
    <div class="text-sm text-center font-semibold">Invoice</div>
    <div class="grid grid-cols-3 gap-y-1">
        <div class="col-span-2">Nomor Transaksi</div>
        <div class="">: {{ $transaksi->nomor_transaksi }}</div>
        <div class="col-span-2">Nama Customer</div>
        <div class="">: {{ $transaksi->nama }}</div>
        <div class="col-span-2">Status Transaksi</div>
        <div class="capitalize">: {{ $transaksi->status }}</div>
        <div class="col-span-2">Status Pembayaran</div>
        <div class="">: {{ $transaksi->pembayaran ? "Sudah Bayar" : "Belum Bayar" }}</div>
        <div class="col-span-2">Tanggal Masuk</div>
        <div class="capitalize">: {{ $transaksi->created_at->format('d-m-Y H:i') }}</div>
        <div class="col-span-2">Estimasi Selesai</div>
        <div class="capitalize">: {{ $transaksi->selesai->format('d-m-Y') }}</div>
        <div class="col-span-2">Tanggal Pengambilan</div>
        <div class="capitalize">: {{ $transaksi->diambil?->format('d-m-Y H:i') ?? '-' }}</div>
    </div>

    <flux:separator text="Service">
    </flux:separator>

    <div class="grid grid-cols-3">
        <div class="">Service</div>
        <div class="">Jumlah</div>
        <div class="">Subtotal</div>
        @foreach ($transaksi->items as $item)
            <div class="">{{ $item->service->nama }}</div>
            <div class="">{{ $item->jumlah }} {{ $item->service->satuan }}</div>
            <div class="">Rp. {{ number_format($item->subtotal, 0, ',', '.')  }}</div>
        @endforeach
    </div>

    <flux:separator text="Total"></flux:separator>
    <div class="grid grid-cols-3">
        <div class="col-span-2">Total</div>
        <div class="">Rp. {{ number_format($transaksi->total, 0, ',', '.')  }}</div>
    </div>

    @auth()
    <div class="flex mt-4 justify-center">
        <flux:tooltip content="Cetak Invoice">
            <flux:button size="sm" icon="printer" as href="{{ route('cetak-invoice', ['slug' => $transaksi->slug]) }}">
            </flux:button>
        </flux:tooltip>
    </div>
    @endauth


</div>
