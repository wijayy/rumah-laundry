<x-layouts.app.header :title="$title ?? null">
    <flux:main class="space-y-4">
        <flux:session>{{ $title??null }}</flux:session>
        <flux:box>
            {{ $slot }}
        </flux:box>
    </flux:main>
</x-layouts.app.header>
