<?php

namespace App\Observers;

use App\Models\Ticket;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        $agent = $ticket->assignedTo;
        Notification::make()
        ->title('New Ticket Assigned')
        ->body('A new ticket has been assigned to you.')
        ->actions([
            Action::make('view')
                ->button()
                ->markAsRead(),
        ])
        ->sendToDatabase($agent);

    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
        $admin = $ticket->assignedBy;

        Notification::make()
            ->title('Ticket Updated')
            ->sendToDatabase($admin);
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
