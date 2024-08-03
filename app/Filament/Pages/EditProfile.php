<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.edit-profile';

    protected static ?string $title = 'User Profile';

    public ?array $data = []; 

    public function mount(){
        $this->form->fill(auth()->user()->profile->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('phone')
                    ->label('Phone')
                    ->placeholder('Enter your phone number')
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->placeholder('Upload your profile image')
                    ->image()
                    ->avatar()
                    ->required(),
            ])
            ->statePath('data');
    } 


    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }


    public function save(): void
    {
        try {
            $data = $this->form->getState();
 
            auth()->user()->profile->update($data);
        } catch (Halt $exception) {
            return;
        }
    }






}
