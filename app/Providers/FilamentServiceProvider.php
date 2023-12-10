<?php

namespace App\Providers;

use Filament\Forms\Components\Select;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

        FilamentView::registerRenderHook(
            'panels::body.start',
            fn (): string => Blade::render('<x-impersonate::banner/>'),
        );


        FilamentView::registerRenderHook(
            'panels::sidebar.footer',
            fn (): View => view('filament.navigation.footer'),
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Select::configureUsing(function (Select $select): void {
            $select->native(false);
        });
    }
}
