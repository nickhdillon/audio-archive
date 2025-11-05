<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'avatar',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn(string $word): string => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    public function artists(): Collection
    {
        return $this->songs()
            ->select(
                'artist',
                DB::raw('COUNT(*) as song_count'),
                DB::raw('COUNT(DISTINCT album) as album_count')
            )
            ->groupBy('artist')
            ->orderBy('artist')
            ->get();
    }

    public function albums(): Collection
    {
        return $this->songs()
            ->select('artist', 'album')
            ->groupBy('artist', 'album')
            ->orderBy('album')
            ->get();
    }
}
