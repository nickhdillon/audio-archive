<?php

declare(strict_types=1);

namespace App\Livewire;

use getID3;
use Flux\Flux;
use App\Models\Song;
use App\Models\Album;
use App\Models\Artist;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\View\View;
use Spatie\LivewireFilepond\WithFilePond;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UploadAudio extends Component
{
    use WithFilePond;

    #[Validate([
        'files' => ['required', 'array'],
        'files.*' => ['file', 'max:102400', 'mimes:mp3,m4a,jpg,jpeg,png']
    ])]
    public array $files = [];

    public array $metadata = [];

    protected function messages(): array
    {
        return [
            'files.*.file' => 'File must be a valid file',
            'files.*.max' => 'File must be less than 100MB',
            'files.*.mimes' => 'File must be of type: mp3, m4a, jpg, jpeg, png',
        ];
    }

    public function validateUploadedFile(): bool
    {
        $this->validate();

        return true;
    }

    public function updatedFiles(): void
    {
        $get_ID3 = new getID3;

        $this->metadata = collect($this->files)
            ->map(function (TemporaryUploadedFile $file) use ($get_ID3): array {
                $path = $file->getRealPath();
                $extension = $file->getClientOriginalExtension();
                $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                $prefix = match ($extension) {
                    'mp3' => 'id3v2',
                    'm4a' => 'quicktime',
                };

                $info = $get_ID3->analyze($path);

                if (Str::contains($filename, 'Chapter')) {
                    $title = Str::of(data_get($info, "tags.{$prefix}.title.0"))
                        ->replaceMatches('/^\(Wop-\d+\)\s*/i', '')
                        ->replaceMatches('/(\D)0+(\d+)$/', '$1$2')
                        ->toString();

                    $album = 'NKJV Word Of Promise';

                    $artist = $display_artist = 'Thomas Nelson';
                }

                return [
                    'user_id' => auth()->id(),
                    'filename' => Str::slug($filename) . ".{$extension}",
                    'title' => $title ?? data_get($info, "tags.{$prefix}.title.0", 'Unknown Title'),
                    'album' => $album ?? data_get($info, "tags.{$prefix}.album.0", 'Unknown Album'),
                    'track_number' => Str::before(data_get($info, "tags.{$prefix}.track_number.0", '1/12'), '/'),
                    'playtime' => data_get($info, 'playtime_string', '3:30'),
                    'artist' => $artist ?? ($prefix === 'id3v2'
                            ? data_get($info, "tags.id3v2.band.0", 'Unknown Artist')
                            : data_get($info, "tags.quicktime.album_artist.0", 'Unknown Artist')
                        ),
                    'display_artist' => $display_artist ?? data_get($info, "tags.{$prefix}.artist.0", 'Unknown Artist'),
                    'genre' => data_get($info, "tags.{$prefix}.genre.0", 'Metal'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->toArray();
    }

    public function submit(): void
    {
        collect($this->files)
            ->map(function (TemporaryUploadedFile $file, int $index): void {
                $meta = $this->metadata[$index];

                $artist = Artist::firstOrCreate(
                    [
                        'slug' => Str::slug($meta['artist']),
                        'user_id' => auth()->id(),
                    ],
                    ['name' => $meta['artist']]
                );
    
                $album = Album::firstOrCreate(
                    [
                        'slug' => Str::slug($meta['album']),
                        'artist_id' => $artist->id,
                    ],
                    ['name' => $meta['album']]
                );

                $s3_path = (app()->isProduction() ? 'users/' : 'users-test/')
                    . auth()->id()
                    . '/files/'
                    . Str::slug($artist->name)
                    . '/'
                    . Str::slug($album->name);
                
                $stored_path = $file->storePubliclyAs(
                    $s3_path,
                    $meta['filename'],
                    's3',
                );

                Song::create([
                    'album_id' => $album->id,
                    'title' => $meta['title'],
                    'slug' => Str::slug($meta['title']),
                    'display_artist' => $meta['display_artist'],
                    'filename' => $meta['filename'],
                    'track_number' => $meta['track_number'],
                    'playtime' => $meta['playtime'],
                    'path' => $stored_path,
                ]);
            })->toArray();

        Flux::toast(
            variant: 'success',
            text: 'Files successfully uploaded',
        );

        $this->redirectRoute('artists', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.settings.upload-audio');
    }
}
