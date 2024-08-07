<x-filament::section class="max-w-7xl mx-auto items-center justify-center">
    <x-slot name="heading">
        Tickets
    </x-slot>
    <x-slot name="headerEnd">
        <x-filament::button color="primary" href="{{ route('ticket.create') }}" tag="a">
            Create Ticket
        </x-filament::button>
    </x-slot>
    {{$this->table}}
</x-filament::section>
