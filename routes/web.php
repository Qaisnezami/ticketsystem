<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dd(User::find(1)->hasPermission('role_access')) ;
    return view('welcome');
});
