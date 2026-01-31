@php
$isCustomPlaceholder = isset($placeholder);
@endphp

@props([
    'multiple' => false,
    'required' => false,
    'disabled' => false,
    'placeholder' => __('Drag & Drop your files or <span class="filepond--label-action"> Browse </span>'),
])

@php
if (! $wireModelAttribute = $attributes->whereStartsWith('wire:model')->first()) {
    throw new Exception("You must wire:model to the filepond input.");
}

$pondProperties = $attributes->except([
    'class',
    'placeholder',
    'required',
    'disabled',
    'multiple',
    'wire:model',
]);

// convert keys from kebab-case to camelCase
$pondProperties = collect($pondProperties)
    ->mapWithKeys(fn ($value, $key) => [Illuminate\Support\Str::camel($key) => $value])
    ->toArray();

$pondLocalizations = __('livewire-filepond::filepond');
@endphp

<div
    class="{{ $attributes->get('class') }}"
    wire:ignore
    x-cloak
    x-data="{
        model: @entangle($wireModelAttribute),
        isMultiple: @js($multiple),
        current: undefined,
        files: [],
        async loadModel() {
            if (! this.model) {
                return;
            }

            if (this.isMultiple) {
                await Promise.all(Object.values(this.model).map(async (picture) => this.files.push(await URLtoFile(picture))))
                return;
            }

            this.files.push(await URLtoFile(this.model))
        }
    }"
    x-init="async () => {
        await loadModel();

        const csrf = '{{ csrf_token() }}';

        const pond = LivewireFilePond.create($refs.input);

        pond.setOptions({
            allowMultiple: isMultiple,
            maxParallelUploads: 8,
            server: {
                process: {
                    url: '/filepond/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    }
                },

                ondata: (formData) => {
                    $dispatch('filepond-upload-started', '{{ $wireModelAttribute }}');
                    return formData;
                },

                onload: (response) => {
                    return response;
                },

                revert: (uniqueFileId, load, error) => {
                    fetch('/filepond/revert', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ path: uniqueFileId }),
                    })
                    .then(resp => {
                        if (resp.ok) load();
                        else error('Revert failed');
                    })
                    .catch(() => error('Revert failed'));
                }
            },

            required: @js($required),
            disabled: @js($disabled),
        });

        pond.setOptions(@js($pondLocalizations));

        pond.setOptions(@js($pondProperties));

        @if($isCustomPlaceholder)
            pond.setOptions({ labelIdle: @js($placeholder) });
        @endif

        pond.on('processfiles', async () => {
            const uploaded = pond.getFiles()
                .map(f => f.serverId)
                .filter(Boolean);

            await $wire.set('files', uploaded);

            $dispatch('filepond-upload-completed', {
                attribute: '{{ $wireModelAttribute }}'
            });
        });

        $wire.on('filepond-reset-{{ $wireModelAttribute }}', () => {
            pond.removeFiles();
        });
    }"
>
    <input type="file" x-ref="input" name="file" />
</div>
