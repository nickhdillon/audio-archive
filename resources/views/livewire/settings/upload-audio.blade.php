<section class="w-full mb-22">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Upload Audio')">
        <form wire:submit='submit' class="space-y-5">
            <div>
                <flux:field>
                    <x-filepond::upload
                        wire:model="files"
                        multiple
                        :allow-image-transform="false"
                    />
    
                    <flux:error name="files" />
                </flux:field>
            </div>
    
            <div class="flex gap-2">
                <flux:button :href="route('artists')"
                    wire:navigate variant="outline" class="!px-4" size="sm">
                    Cancel
                </flux:button>
        
                <flux:button variant="primary" class="!px-4" size="sm" type="submit">
                    Submit
                </flux:button>
            </div>
        </form>
    </x-settings.layout>
</section>