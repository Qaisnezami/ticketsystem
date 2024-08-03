<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\TicketResource\Pages;
use Filament\Infolists\Components\RepeatableEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Filament\Resources\TicketResource\RelationManagers\CategoriesRelationManager;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->autofocus(),
                Textarea::make('description')
                    ->required(),
                Select::make('priority')
                    ->options(self::$model::PRIORITY)
                    ->required(),
                Select::make('status')
                    ->options(self::$model::STATUS)
                    ->required(),
                Select::make('assigned_to')
                    ->options(
                        User::whereHas('roles', function (Builder $query) {
                            $query->where('title', 'Agent');
                        })->pluck('name', 'id')->toArray()
                    )
                    ->required(),
                FileUpload::make('attachment')
                    ->image(),
                Repeater::make('checklist')
                    ->schema([
                        Checkbox::make('is_completed')
                            ->inline(false)
                            ->columnSpan(1),
                        TextInput::make('task')
                            ->required()
                            ->columnSpan(3),
                    ])
                    ->columns(4),
                Textarea::make('comment')
                    ->nullable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(
                fn (Builder $query) =>
                auth()->user()->hasRole('Agent') ? $query->where('assigned_to', auth()->id()) : $query

            )
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
                    ->options(self::$model::STATUS),
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
                    ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    DeleteAction::make(),
                    Action::make('Edit Comment')
                        ->icon('heroicon-o-pencil')
                        ->hidden(!auth()->user()->hasPermission('ticket_edit'))
                        ->url(fn (Ticket $ticket) => route('filament.admin.resources.tickets.edit-comment', $ticket))
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->hidden(!auth()->user()->hasPermission('ticket_delete')),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns(4)
            ->schema([
                TextEntry::make('title'),
                TextEntry::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'warning',
                        'high' => 'danger',
                    }),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        'solved' => 'warning',
                    }),
                TextEntry::make('assignedTo.name')
                    ->label('Assigned To'),
                TextEntry::make('assignedBy.name')
                    ->label('Assigned By')
                    ->columnSpanFull(),
                TextEntry::make('description')
                    ->columnSpan(2),
                TextEntry::make('comment')->columnSpan(2),
                RepeatableEntry::make('checklist')
                    ->schema([
                        IconEntry::make('is_completed')
                            ->boolean()
                            ->label('Completed')
                            ->columnSpan(1),
                        TextEntry::make('task')
                            ->label('Task')
                            ->columnSpan(3),

                    ])
                    ->columns(4)
                    ->columnSpan(4),
                ImageEntry::make('attachment')
                    ->columnSpanFull(),

            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit-comment' => Pages\EditComment::route('/{record}/edit-comment'),
        ];
    }
}
