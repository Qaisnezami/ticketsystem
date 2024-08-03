<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Ticket;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTickets extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                auth()->user()->hasRole('Agent') ? Ticket::where('assigned_to', auth()->id()) : Ticket::query()
            )
            ->columns([
                TextColumn::make('title')
                    ->description(fn (Ticket $ticket) => $ticket->description)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('priority')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'low' => 'gray',
                                'medium' => 'warning',
                                'high' => 'danger',
                            }),
                SelectColumn::make('status')
                            ->options(Ticket::STATUS),
                TextColumn::make('assignedTo.name')
                            ->label('Assigned To')
                            ->searchable()
                            ->sortable(),
                TextColumn::make('assignedBy.name')
                            ->label('Assigned By')
                            ->searchable()
                            ->sortable(),
                TextInputColumn::make('comment')
                            ->searchable()
                            ->sortable(),
                TextColumn::make('created_at')
                            ->sortable()
                            ->searchable(),
            ]);
    }
}
