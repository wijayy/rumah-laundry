<div>
    <form wire:submit='save' class="space-y-4">
        <flux:input wire:model.live='nama' :label="'Nama Service'"></flux:input>
        <div class="grid grid-cols-4">
            <div class="col-span-3">
                <flux:input wire:model.live='harga' type="number" step="500" :label="'Harga'"></flux:input>
            </div>
            <flux:select wire:model.live='satuan' :label="'Satuan'" :placeholder="'Pilih Satuan'">
                @foreach ($stn as $item)
                    <flux:select.option value="{{ $item }}">{{ $item }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        <flux:button variant="primary" type="submit">Submit</flux:button>
    </form>
</div>
