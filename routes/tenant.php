<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    'universal',
    App\Providers\TenancyServiceProvider::TENANCY_INITIALIZER,
])->group(function () {

    // Your Tenant routes go here
});
