<div class="">
    <form wire:submit='save'>
        <div class="font-semibold text-sm mb-4">Cek status cucianmu dengan memasukkan nomor transaksi di sini!</div>
        <flux:input wire:model.live='nomor_transaksi' required :label="'Masukan Nomor Transaksi'"></flux:input>
        <x-action-message on="error" class="text-sm mt-2 text-rose-400">
            Nomor Transaksi tidak Ditemukan
        </x-action-message>
        <div class="flex mt-4 justify-center">
            <flux:button variant="primary" type="submit">Submit</flux:button>
        </div>
    </form>

    <div class="text-xs sm:text-sm flex gap-2 mt-6 justify-center">
        <div class="">Ingin masuk ke sistem? </div>
        <a href="{{ route('login') }}" class="underline">Login</a>
    </div>
</div>
