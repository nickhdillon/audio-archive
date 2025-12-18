<x-layouts.app.header :title="$title ?? null">
    <flux:main class="mx-auto w-full max-w-6xl">
        {{ $slot }}
    </flux:main>
</x-layouts.app.header>
