<?php

namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class ListTickets extends Component implements HasForms, HasTable
{

    use InteractsWithTable;
    use InteractsWithForms;

    public static $model = Ticket::class;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(Ticket::query())
            ->columns([
                TextColumn::make('title')
                    ->description(fn (Ticket $ticket) => Str::words($ticket->description, 5))
                    ->searchable()
                    ->sortable()
                    ->words(5),
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
                ImageColumn::make('attachment'),
                TextInputColumn::make('comment')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('priority')
                    ->options(self::$model::PRIORITY)
                    ->label('Priority')
                    ->placeholder('Filter by Priority'),
                SelectFilter::make('status')
                    ->options(self::$model::STATUS)
                    ->label('Status')
                    ->placeholder('Filter by Status'),
            
            ])
            ->actions([
                ActionGroup::make([
                    Action::make()
                        ->name('Edit Ticket')
                        ->icon('heroicon-o-pencil')
                        ->url(fn (Ticket $ticket) => route('ticket.edit', $ticket)),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->hidden(!auth()->user()->hasPermission('ticket_delete')),
                ]),
            ]);
    }
    public function render()
    {
        return view('livewire.list-tickets');
    }
}
