<?php

declare(strict_types=1);

namespace App\Interfaces;

interface PlaysSongs
{
	public function playSongs(bool $shuffle = false): void;
}
