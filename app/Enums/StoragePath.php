<?php

namespace App\Enums;

enum StoragePath: string
{
    case PRODUCT_IMAGES = 'product_images';
    case CAROUSEL_IMAGES = 'carousel_images';
    case LOGOS = 'logos';
}
