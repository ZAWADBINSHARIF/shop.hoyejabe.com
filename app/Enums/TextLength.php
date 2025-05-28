<?php

namespace App\Enums;

enum TextLength: int
{
    case TINY         = 3;
    case PASSWORD     = 6;
    case PHONE        = 11;
    case SHORT        = 32;
    case MEDIUM       = 80;
    case LONG         = 255;
    case EXTRA_LONG   = 1400;
    case LARGE        = 5000;
    case EXTRA_LARGE  = 10000;
    case HUGE         = 65000;

    public function label(): string
    {
        return match ($this) {
            self::TINY         => 'Tiny (e.g., initials)',
            self::PASSWORD     => 'Password length',
            self::PHONE        => 'Phone number',
            self::SHORT        => 'Short text (title/slug)',
            self::MEDIUM       => 'Medium (subtitle)',
            self::LONG         => 'Long (input fields)',
            self::EXTRA_LONG   => 'Extra long (description)',
            self::LARGE        => 'Large content (page section)',
            self::EXTRA_LARGE  => 'Extra large (article)',
            self::HUGE         => 'Huge (HTML, blobs)',
        };
    }
}
