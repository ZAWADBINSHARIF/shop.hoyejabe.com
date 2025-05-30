<?php

namespace App\Filament\Resources\CarouselImageResource\Pages;

use App\Filament\Resources\CarouselImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarouselImage extends EditRecord
{
    protected static string $resource = CarouselImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
