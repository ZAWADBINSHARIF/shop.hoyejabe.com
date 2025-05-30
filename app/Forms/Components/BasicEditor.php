<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Closure;


class BasicEditor extends RichEditor
{
    protected array | Closure $toolbarButtons = [
        // 'attachFiles',
        'blockquote',
        'bold',
        'bulletList',
        // 'codeBlock',
        // 'h1',
        'h2',
        'h3',
        'italic',
        'link',
        'orderedList',
        'redo',
        // 'strike',
        'underline',
        'undo',
    ];
}
