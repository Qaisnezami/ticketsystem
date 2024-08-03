<?php

namespace App\Filament\Resources\TicketResource\Pages;

use Filament\Actions;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TicketResource;

class EditComment extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected static ?string $title = 'Edit Comment';
    
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Textarea::make('comment')
                    ->required(),
            ]);
    }
}
