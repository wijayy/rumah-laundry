<div>
    <form wire:submit='save' class="space-y-4">
        <flux:input wire:model.live='nama' :label="'Nama Customer'" required></flux:input>
        <flux:input wire:model.live='whatsapp' :label="'Nomor Whatsapp'" required></flux:input>
        <flux:input wire:model.live='selesai' min="{{ date('Y-m-d') }}" type="date" :label="'Estimasi Selesai'" required></flux:input>
        <flux:select wire:model.live='payment' :label="'Pembayaran'" required>
            <flux:select.option class="text-xs! w-fit!" value="1">Sudah Bayar</flux:select.option>
            <flux:select.option class="text-xs! w-fit!" value="0">Belum Bayar</flux:select.option>
        </flux:select>
        <div class="my-4 space-y-4 w-full!">
            <flux:separator text="Service"></flux:separator>
            @foreach ($services as $index => $item)
                <div class="flex gap-4 items-end">
                    <div class="flex gap-4 w-10/12">
                        <flux:select wire:model.live='services.{{ $index }}.id' wire:change='updateServices' :label="'Service'">
                            @foreach ($service as $itm)
                                <flux:select.option class="text-xs!" value="{{ $itm->id }}">{{ $itm->nama }}</flux:select.option>
                            @endforeach
                        </flux:select>
                        @if ($item['satuan'] == 'kg')
                            <flux:input type="number" required wire:input='updateTotal' class="" min="0" step="0.1"
                                wire:model.live='services.{{ $index }}.jumlah' :label="'Jumlah'" kbd="{{ $item['satuan'] }}">
                            </flux:input>
                        @else
                            <flux:input type="number" required wire:input='updateTotal' class="" min="0" wire:model.live='services.{{ $index }}.jumlah'
                                :label="'Jumlah'" kbd="{{ $item['satuan'] }}"></flux:input>
                        @endif
                        {{-- <flux:input type="text" class="w-fit!" wire:model.live='services.{{ $index }}.satuan'
                            :label="'Satuan'">
                        </flux:input> --}}

                    </div>
                    <div class="w-fit">
                        <flux:button size='sm' variant="danger" wire:click='removeItem({{ $index }})' icon="trash">
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>
        <flux:button class="w-full!" wire:click='addItem()' icon="plus"></flux:button>

        <flux:separator text="Total"></flux:separator>

        <div class="flex justify-between">
            <div class="">Total</div>
            <div class="">Rp. {{ number_format($total,0, ',', '.')  }}</div>
        </div>
        <div class="flex justify-center">
            <flux:button variant="primary" type="submit">Submit</flux:button>
        </div>
    </form>
</div>
