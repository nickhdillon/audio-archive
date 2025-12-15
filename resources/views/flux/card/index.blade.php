@blaze

@php
$classes = Flux::classes()
    ->add('rounded-[12px]')
    ->add('bg-neutral-50/80 dark:bg-neutral-900')
    ->add('border border-neutral-200 dark:border-neutral-700')
    ->add('-space-y-1')
    ->add('shadow-xs dark:shadow-2xl')
@endphp

<div {{ $attributes->class($classes) }} data-flux-card>
    {{ $slot }}
</div>
