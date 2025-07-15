<div>
    <div class="flex flex-wrap sm:flex-nowrap items-center gap-2">
        <flux:input size="sm" type="date" class="w-fit!" wire:model='date' wire:change='updateDate'
            max="{{ date('Y-m-d') }}"></flux:input>
        <div class="text-xs">Summary</div>
        <div class="w-full"></div>
        <flux:button as href="{{ route('transaksi.create') }}" size="sm" variant="primary">Tambah Transaksi
        </flux:button>
    </div>
    <div class="overflow-x-auto">
        <div class="mt-4 grid grid-cols-16 items-center gap-2 py-2 min-w-5xl text-sm">
            <div class="">#</div>
            <div class="col-span-2">Nomor Transaksi</div>
            <div class="col-span-2">Nama Customer</div>
            <div class=" col-span-3">
                <div class="text-center">Service</div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="">Service</div>
                    <div class="">Jumlah</div>
                    <div class="">Subtotal</div>
                </div>
            </div>
            <div class="col-span-2 text-center">Total</div>
            <div class="col-span-1 text-center">Status</div>
            <div class="col-span-2 text-center">Estimasi Selesai</div>
            <div class="col-span-2 text-center">Pengambilan</div>
            <div class="col-span-1 text-center">Action</div>
        </div>
        @foreach ($transaksi as $key => $item)
            <div class="mt-4 grid grid-cols-16 items-center gap-2 py-2 min-w-5xl text-sm">
                <div class="">{{ $key + 1 }}</div>
                <div class="col-span-2">{{ $item->nomor_transaksi }}</div>
                <div class="col-span-2">{{ $item->nama }}</div>
                <div class=" col-span-3">
                    @foreach ($item->items as $itm)
                        <div class="grid grid-cols-3 gap-4">
                            <div class="">{{ $itm->service->nama }}</div>
                            <div class="">{{ $itm->jumlah }} {{ $itm->service->satuan }}</div>
                            <div class="">Rp. {{ number_format($itm->subtotal, 0, ',', '.')  }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="col-span-2 text-center">Rp. {{ number_format($item->total, 0, ',', '.')  }}</div>
                <div class="col-span-1 text-center space-y-1">
                    @if ($item->status == 'diterima')
                        <div class="p-1 rounded bg-amber-100">{{ $item->status }}</div>
                    @elseif ($item->status == 'diproses')
                        <div class="p-1 rounded bg-lime-100">{{ $item->status }}</div>
                    @elseif ($item->status == 'selesai')
                        <div class="p-1 rounded bg-emerald-100">{{ $item->status }}</div>
                    @elseif ($item->status == 'diambil')
                        <div class="p-1 rounded bg-sky-100">{{ $item->status }}</div>
                    @endif
                    <div class="p-1 rounded {{ $item->pembayaran ? 'bg-green-100' : 'bg-rose-100' }}">
                        {{ $item->pembayaran ? "Sudah Bayar" : "Belum Bayar" }}</div>
                </div>
                <div class="col-span-2 text-center">{{ $item->selesai->format("d M Y") }}</div>
                <div class="col-span-2 text-center">{{ $item->diambil?->format('d M Y H:i') ?? '-' }}</div>
                <div class="col-span-1 text-center">
                    @if ($item->status == 'diterima')
                        <flux:modal.trigger name="diproses-{{$item->id }}">
                            <flux:tooltip content="Proses Order">
                                <flux:button size="sm" icon="arrow-path"></flux:button>
                            </flux:tooltip>
                        </flux:modal.trigger>
                        <flux:modal name="diproses-{{$item->id }}">
                            <div class="mt-4">
                                Apakah yakin untuk memproses order {{ $item->nomor_transaksi }}?
                            </div>
                            <div class="flex mt-4 justify-end">
                                <flux:modal.close>
                                    <flux:button wire:click="diproses({{ $item->id }})" variant="primary">Ya
                                    </flux:button>
                                </flux:modal.close>
                            </div>
                        </flux:modal>
                    @elseif ($item->status == 'diproses')
                        <flux:modal.trigger name="selesai-{{ $item->id }}">
                            <flux:tooltip content="Order Selesai">
                                <flux:button size="sm" icon="check-circle"></flux:button>
                            </flux:tooltip>
                        </flux:modal.trigger>
                        <flux:modal name="selesai-{{$item->id }}">
                            <div class="mt-4">
                                Apakah yakin sudah menyelesaikan order {{ $item->nomor_transaksi }}?
                            </div>
                            <div class="flex mt-4 justify-end">
                                <flux:modal.close>
                                    <flux:button wire:click="selesai({{ $item->id }})" variant="primary">Ya
                                    </flux:button>
                                </flux:modal.close>
                            </div>
                        </flux:modal>
                    @elseif ($item->status == 'selesai')
                        <flux:modal.trigger name="diambil-{{ $item->id }}">

                            <flux:tooltip content="Order Diambil">
                                <flux:button size="sm" icon="hand-box"></flux:button>
                            </flux:tooltip>
                        </flux:modal.trigger>
                        <flux:modal name="diambil-{{$item->id }}">
                            @if ($item->pembayaran)
                                <div class="mt-4">
                                    Apakah Anda yakin untuk Customer sudah datang untuk mengambil order
                                    {{ $item->nomor_transaksi }}?
                                </div>
                            @else
                                <div class="mt-4">
                                    Apakah Anda yakin untuk Customer sudah datang untuk mengambil dan sudah membayar order
                                    {{ $item->nomor_transaksi }}?
                                </div>
                            @endif
                            <div class="flex mt-4 justify-end">
                                <flux:modal.close>
                                    <flux:button wire:click="diambil({{ $item->id }})" variant="primary">Ya
                                    </flux:button>
                                </flux:modal.close>
                            </div>
                        </flux:modal>
                    @endif
                    <flux:tooltip content="Invoice">
                        <flux:button size="sm" icon="invoice" as href="{{ route('invoice', ['slug'=>$item->slug]) }}"></flux:button>
                    </flux:tooltip>
                </div>
            </div>
        @endforeach
    </div>
</div>
