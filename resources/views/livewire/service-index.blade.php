<div>
    <flux:button size="sm" variant="primary" as  icon="plus" href="{{ route('service.create') }}"><div class="text-xs">Tambah Service</div></flux:button>
    <div class="flex gap-4 mt-4 w-full text-xs py-2">
        <div class="w-1/12">#</div>
        <div class="w-1/3">Nama Service</div>
        <div class="w-1/3">Harga</div>
        <div class="w-2/12">Action</div>
    </div>
    @foreach ($services as $key => $item)
        <div class="flex gap-4 items-center w-full text-xs py-1">
            <div class="w-1/12">{{ $key + 1 }}</div>
            <div class="w-1/3">{{ $item->nama }}</div>
            <div class="w-1/3">Rp. {{ number_format($item->harga, 0, ',', '.') }}/{{ $item->satuan }}</div>
            <div class="w-2/12 space-x-0">
                <flux:tooltip class="text-xs!" content="Edit Service">
                    <flux:button size="xs" as href="{{ route('service.edit', ['slug' => $item->slug]) }}" icon='pencil-square'>
                    </flux:button>
                </flux:tooltip>
                <flux:modal.trigger name="delete-service-{{ $key }}">
                    <flux:tooltip class="text-xs!"  content="Delete Service">
                        <flux:button size="xs" variant="danger" icon='trash'></flux:button>
                    </flux:tooltip>
                </flux:modal.trigger>
            </div>
            <flux:modal name="delete-service-{{ $key }}">
                <div class="mt-4">Apakah yakin menghapus service {{ $item->nama }}?</div>
                <div class="mt-4 flex justify-end">
                    {{-- <flux:modal.close class="close"> --}}
                        <flux:button size="sm" wire:click="deleteService({{ $item->id }})" variant="danger">Hapus</flux:button>
                        {{--
                    </flux:modal.close> --}}
                </div>
            </flux:modal>
        </div>
    @endforeach
</div>
