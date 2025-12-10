<div>
    <flux:modal name="{{ $playlist ? ('edit-playlist' . $playlist->id) : 'add-playlist' }}" class="w-86 sm:w-full">
        <form wire:submit='submit' class="space-y-6">
            <div class="space-y-6">
                <flux:heading size="lg" class="font-semibold -mt-1.5!">
                    {{ $playlist ? 'Edit' : 'Create' }} Playlist
                </flux:heading>

                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input type="text" wire:model='name' required />

                    <flux:error name="name" />
                </flux:field>

                <div class="flex gap-2 items-center">
                    @if ($playlist)
                        <div>
                            <flux:modal.trigger name="delete-playlist">
                                <flux:button variant="danger" size="sm">
                                    Delete
                                </flux:button>
                            </flux:modal.trigger>
                
                            <x-delete-modal name="delete-playlist" heading="playlist" />
                        </div>
                    @endif

                    <flux:spacer />

                    <div class="ml-auto flex gap-2">
                        <flux:modal.close>
                            <flux:button variant="ghost" size="sm">
                                Cancel
                            </flux:button>
                        </flux:modal.close>
                
                        <flux:button type="submit" variant="primary" size="sm">
                            Save
                        </flux:button>
                    </div>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
