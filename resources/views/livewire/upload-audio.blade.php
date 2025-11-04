<div class="space-y-4 max-w-4xl mx-auto">
    <flux:heading size="xl">
        Upload Audio
    </flux:heading>

    <form wire:submit='submit' class="space-y-5">
        <div>
            <flux:field>
                <flux:file-upload wire:model="files" multiple label="Upload files">
                    <flux:file-upload.dropzone
                        class="hover:bg-neutral-100 dark:hover:bg-neutral-600 cursor-pointer"
                        heading="Drop files here or click to browse"
                        text="MP3, M4A, JPG, PNG up to 100MB"
                        with-progress
                    />
                </flux:file-upload>
            </flux:field>

            @if ($files) 
                <div class="mt-4 flex flex-col gap-2">
                    @foreach ($files as $index => $file) 
                        <flux:file-item :heading="$file->getClientOriginalName()">
                            <x-slot name="actions">
                                <flux:file-item.remove wire:click="removeFile({{ $index }})" />
                            </x-slot>
                        </flux:file-item>
                    @endforeach
                </div>
            @endif
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
</div>
