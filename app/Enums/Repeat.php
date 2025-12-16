<?php

declare(strict_types=1);

namespace App\Enums;

enum Repeat: string
{
	case ALL = 'all';
	case ONE = 'one';
	case OFF = 'off';
}
