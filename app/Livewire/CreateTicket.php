<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Ticket;
use Livewire\Component;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class CreateTicket extends Component implements HasForms
{
    use InteractsWithForms;

    public static $model = Ticket::class;
    
    public ?array $data = [];
    
    public function mount(): void
    {
        $this->form->fill();
    }
    
    public function form(Form $form): Form
    {
        return $form
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
            ])
            ->statePath('data');
    }
    
    public function create(): void
    {
        Ticket::create($this->form->getState() + ['assigned_by' => auth()->id()]);

        Notification::make()
                ->title('Ticket Created')
                ->success()
                ->send();

        $this->redirect(route('tickets'));
        

    }
    
    public function render()
    {
        return view('livewire.create-ticket');
    }
}
