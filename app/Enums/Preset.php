<?php

declare(strict_types=1);

namespace App\Enums;

enum Preset: string
{
    case BASS_BOOST = 'bass_boost';
    case CLASSICAL = 'classical';
    case ELECTRONIC = 'electronic';
    case HIPHOP = 'hiphop';
    case JAZZ = 'jazz';
	case POP = 'pop';
    case ROCK = 'rock';
    case TREBLE_BOOST = 'treble_boost';

	public function gains(): array
    {
        return match ($this) {
            self::BASS_BOOST => [5, 4, 2, -1, -2, -3],
            self::CLASSICAL => [0, 1, -1, -2, 1, 2],
            self::ELECTRONIC => [4, 3, 0, 1, 3, 4],
            self::HIPHOP => [4, 3, 1, -1, 2, 3],
            self::JAZZ => [0, 2, 1, 1, 2, 0],
            self::POP => [3, 2, 0, -2, 1, 3],
            self::ROCK => [4, 3, 0, 2, 3, 4],
            self::TREBLE_BOOST => [-2, -1, 0, 2, 4, 5],
        };
    }
	
	public function label(): string
	{
		return match ($this) {
			self::BASS_BOOST => 'Bass boost',
			self::CLASSICAL => 'Classical',
			self::ELECTRONIC => 'Electronic',
			self::HIPHOP => 'HipHop',
			self::JAZZ => 'Jazz',
			self::POP => 'Pop',
			self::ROCK => 'Rock',
			self::TREBLE_BOOST => 'Treble boost'
		};
	}
}
