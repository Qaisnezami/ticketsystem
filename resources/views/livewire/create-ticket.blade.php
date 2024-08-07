<x-filament::section class="max-w-7xl mx-auto items-center justify-center">
    <x-slot name="heading">
        Create Ticket
    </x-slot>
 
    <form wire:submit="create" >
        {{ $this->form }}


        <x-filament::button color="success" type="submit" class="mt-3">
            Submit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</x-filament::section>