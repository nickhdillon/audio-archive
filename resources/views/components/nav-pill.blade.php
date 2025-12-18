@props(['route', 'current'])

<a href="{{ $route }}" wire:navigate>
    <div
        @class([
            'bg-amber-400! text-neutral-800!' => $current,
            'bg-neutral-100 dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 hover:text-neutral-800 dark:hover:text-neutral-100 hover:bg-neutral-200/75 hover:bg-neutral-700/75 px-3 py-1 text-sm font-medium text-center rounded-full items-center'
        ])
    >
        {{ $slot }}
    </div>
</a>
