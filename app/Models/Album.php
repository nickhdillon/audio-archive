<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Enums\BibleBook;
use Illuminate\Support\Str;

class Album extends Model
{
    /** @use HasFactory<\Database\Factories\AlbumFactory> */
    use HasFactory;

    protected $fillable = [
        'artist_id',
        'parent_id',
        'name',
        'slug',
        'artwork_url',
        'order'
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Album::class, 'parent_id')->orderBy('order');
    }

    public function artist(): BelongsTo
    {
        return $this->belongsTo(Artist::class);
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class)->orderBy('track_number');
    }

    public function breadcrumbs(): Collection
    {
        return collect()
            ->when($this->parent, fn (Collection $collection): Collection =>
                $collection->merge($this->parent->breadcrumbs()
            ))
            ->push($this);
    }

    protected function isBibleBook(): Attribute
    {
        return Attribute::make(
            get: fn () => BibleBook::tryFrom($this->name) !== null,
        );
    }

    #[Scope]
    public function orderedSongs(?string $search = null): Collection
    {
        $this->loadMissing(['songs', 'children.songs']);

        $filter = fn(Collection $songs): Collection => $search
            ? $songs->filter(fn(Song $song): bool => Str::contains(Str::lower($song->title), Str::lower($search)))
            : $songs;

        $children = $this->children
            ->sortBy('order')
            ->values();

        if ($children->isNotEmpty()) {
            return $children->flatMap(function (Album $child) use ($filter): Collection {
                return $filter($child->songs)
                    ->sort(fn (Song $a, Song $b): int => strnatcmp($a->title, $b->title));
            })->values();
        }

        if ($this->is_bible_book) {
            return $filter($this->songs)
                ->sort(fn (Song $a, Song $b): int => strnatcmp($a->title, $b->title))
                ->values();
        }

        return $filter($this->songs)
            ->sortBy('track_number')
            ->values();
    }
}
