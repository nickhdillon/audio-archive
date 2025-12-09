<?php

use App\Models\Album;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    public function fetchAlbumArtwork(): void
    {
        $disk = Storage::disk('s3');

        $albums = auth()->user()
            ->albums()
            ->with('artist:id,slug,artwork_url')
            ->whereNull('artwork_url')
            ->get();

        $albums->each(function (Album $album) use ($disk) : void {
            $artwork_url = Http::get("https://itunes.apple.com/search", [
                'term' => "{$album->slug} {$album->artist->slug}",
                'entity' => 'album',
                'attribute' => 'albumTerm',
                'limit' => 1,
            ])->json('results.0.artworkUrl100');

            if ($artwork_url) {
                $artwork_url = Str::replace('100x100bb.jpg', '2000x2000bb.jpg', $artwork_url);

                $path = '/users/' . auth()->id() . "/files/{$album->artist->slug}/{$album->slug}/artwork.jpg";

                $image = Http::get($artwork_url)->body();

                $disk->put($path, $image, 'public');

                $album->update(['artwork_url' => $path]);
            }
        });

        $this->dispatch('artwork-fetched');
}
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Media')" :subheading="__('Update your media settings such as album artwork, equalizer, etc.')">
        <form wire:submit="fetchAlbumArtwork" class="flex items-center gap-4 my-6 w-full">
            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" size="sm" class="w-full">
                    {{ __('Fetch album artwork') }}
                </flux:button>
            </div>

            <x-action-message class="me-3" on="artwork-fetched">
                {{ __('Fetched.') }}
            </x-action-message>
        </form>
    </x-settings.layout>
</section>