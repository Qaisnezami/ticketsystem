<?php

use App\Livewire\CreateTicket;
use App\Livewire\EditTicket;
use App\Livewire\ListTickets;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dd(User::find(1)->hasPermission('role_access')) ;
    return view('welcome');
});


Route::get('/tickets',ListTickets::class)->name('tickets');

Route::get('/ticket/create',CreateTicket::class)->name('ticket.create');

Route::get('/ticket/{ticket}/edit',EditTicket::class)->name('ticket.edit');