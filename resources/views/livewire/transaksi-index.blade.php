<div>
    <div class="flex gap-4">

        <flux:button as href="{{ route('transaksi.create') }}" icon="plus" size="sm" variant="primary">Tambah Transaksi
        </flux:button>
        <flux:button as href="{{ route('transaksi.create') }}" icon="printer" size="sm" variant="primary">Cetak
        </flux:button>
    </div>
    <flux:separator text="Filter" position='center'></flux:separator>
    <div class="grid grid-cols-2 w-full md:grid-cols-4 gap-2">
        <flux:input size="sm" type="date" :label="'Tanggal Masuk'" wire:model.live='date' wire:change='updateTransaksi'
            max="{{ date('Y-m-d') }}"></flux:input>
        <flux:input size="sm" :label="'Nama Customer'" wire:model.live='nama' wire:change='updateTransaksi'></flux:input>
        <flux:select wire:change='updateTransaksi' wire:model.live='status' :label="'Status'">
            <flux:select.option value="">semua</flux:select.option>
            @foreach ($optionStatus as $item)
                <flux:select.option value="{{ $item }}">{{ $item }}</flux:select.option>
            @endforeach
        </flux:select>
        <flux:select wire:change='updateTransaksi' wire:model.live='pembayaran' :label="'Pembayaran'">
            <flux:select.option value="">semua</flux:select.option>
            <flux:select.option value="0">belum bayar</flux:select.option>
            <flux:select.option value="1">sudah bayar</flux:select.option>
        </flux:select>
    </div>
    <flux:separator text="Data Transaksi" position="center"></flux:separator>
    <div class="grid gap-4 grid-cols-1 mt-4 md:grid-cols-2  lg:grid-cols-3">
        @foreach ($transaksi as $item)
            <div class="rounded shadow-sm text-xs p-2 sm:text-sm bg-neutral-100 dark:bg-neutral-600 lg:text-base">
                <div class="flex justify-between text-mine-300">
                    <div class="">{{ $item->created_at->format('d-m-Y') }}</div>
                    <div class="">{{ $item->created_at->format('H:i') }}</div>
                </div>
                <div class="text-sm lg:text-lg mt-2">{{ $item->nomor_transaksi }}</div>
                <div class="">{{ $item->nama }} / 628XXXXXXXXXX</div>
                <div class="">Estimasi Selesai : {{ $item->selesai->format('d-m-Y') }}</div>
                <div class="">Pengambilan : {{ $item->diambil?->format('d-m-Y H:i') ?? '-' }}</div>

                <div class="flex justify-center mt-2 gap-4">
                    @if ($item->status == 'diterima')
                        <div class="p-1 rounded capitalize bg-amber-100 dark:bg-amber-900">{{ $item->status }}</div>
                    @elseif ($item->status == 'diproses')
                        <div class="p-1 rounded capitalize bg-lime-100 dark:bg-lime-900">{{ $item->status }}</div>
                    @elseif ($item->status == 'selesai')
                        <div class="p-1 rounded capitalize bg-emerald-100 dark:bg-emerald-900">{{ $item->status }}</div>
                    @elseif ($item->status == 'diambil')
                        <div class="p-1 rounded capitalize bg-sky-100 dark:bg-sky-900">{{ $item->status }}</div>
                    @endif
                    <div
                        class="p-1 rounded capitalize {{ $item->pembayaran ? 'bg-green-100 dark:bg-green-900' : 'bg-rose-100 dark:bg-rose-900' }}">
                        {{ $item->pembayaran ? "Sudah Bayar" : "Belum Bayar" }}
                    </div>
                </div>
                <flux:separator text="Service" position="center"></flux:separator>
                @foreach ($item->items as $itm)
                    <div class="grid grid-cols-3 gap-2 w-full">
                        <div class="text-center">{{ $itm->service->nama }}</div>
                        <div class="text-center">{{ $itm->jumlah }} {{ $itm->service->satuan }}</div>
                        <div class="text-center">Rp. {{ number_format($itm->subtotal, 0, ',', '.')  }}</div>
                    </div>
                @endforeach
                <flux:separator text="Total" position="center"></flux:separator>
                <div class="text-center">Rp. {{ number_format($item->total, 0, ',', '.')  }}</div>

                <flux:separator text="Action" position="center"></flux:separator>
                <div class="flex gap-4 mt-4 justify-center" x-data="{copied:false}">
                    @if ($item->status == 'diterima')
                        <div>
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
                        </div>
                    @elseif ($item->status == 'diproses')
                        <div>
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
                        </div>
                    @elseif ($item->status == 'selesai')
                        <div>
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
                        </div>
                    @endif
                    <flux:tooltip content="Invoice">
                        <flux:button size="sm" icon="invoice" as href="{{ route('invoice', ['slug' => $item->slug]) }}">
                        </flux:button>
                    </flux:tooltip>
                    <flux:tooltip content="Kirim ke Whatsapp">
                        <flux:button size="sm" icon="whatsapp" target="_blank" as href='https://api.whatsapp.com/send/?phone={{ $item->whatsapp }}&text={{ urlencode("Halo $item->nama, terima kasih telah menggunakan layanan Rumah Laundry. Anda dapat memantau status cucian melalui tautan berikut: " . url("invoice", ["slug"=>$item->slug])) }}&type=phone_number&app_absent=0'>
                        </flux:button>
                    </flux:tooltip>
                    <flux:tooltip content="Cetak Invoice">
                        <flux:button size="sm" icon="printer" as
                            href="{{ route('cetak-invoice', ['slug' => $item->slug]) }}">
                        </flux:button>
                    </flux:tooltip>
                </div>
            </div>
        @endforeach
    </div>

    <div class="">{{ $transaksi->links() }}</div>
</div>
