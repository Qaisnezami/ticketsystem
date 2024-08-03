<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Action::make('updatePassword')
                ->form([
                    TextInput::make('password')
                        ->label('Password')
                        ->placeholder('Password')
                        ->password()
                        ->required()
                        ->confirmed(),
                    TextInput::make('password_confirmation')
                        ->label('Password Confirmation')
                        ->placeholder('Password Confirmation')
                        ->password()
                        ->required(),
                ])
                ->action(function (array $data, User $record): void {
                    $record->update([
                        'password' => $data['password'],
                    ]);
                    Notification::make()
                    ->title('Password Updated')
                    ->success()
                    ->send();
                })
                ->slideOver()
        ];
    }
}
