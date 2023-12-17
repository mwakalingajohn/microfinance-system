<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Awcodes\FilamentQuickCreate\QuickCreatePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->sidebarWidth("240px")
            ->login()
            ->spa()
            ->font("Nunito")
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        hasAvatars: false
                    )
                    ->enableTwoFactorAuthentication(
                        force: false,
                    ),
                \EightyNine\Approvals\ApprovalPlugin::make(),
                QuickCreatePlugin::make()
            ])
            ->navigationItems([
                NavigationItem::make("Tinker")
                    ->url("/tinker")
                    ->group("Settings")
                    ->openUrlInNewTab(),
                NavigationItem::make("Log Viewer")
                    ->url("/log-viewer")
                    ->group("Settings")
                    ->openUrlInNewTab(),
                NavigationItem::make("System Health")
                    ->url("/pulse")
                    ->group("Settings")
                    ->openUrlInNewTab(),
            ])
            ->navigationGroups([
                NavigationGroup::make("Loans")
                    ->icon("heroicon-o-banknotes"),
                NavigationGroup::make("Reports")
                    ->collapsed()
                    ->icon("heroicon-s-document-chart-bar"),
                NavigationGroup::make("Users")
                    ->collapsed()
                    ->icon("heroicon-o-user-group"),
                NavigationGroup::make("Configuration")
                    ->collapsed()
                    ->icon("heroicon-s-cog-8-tooth"),
                NavigationGroup::make("Settings")
                    ->collapsed()
                    ->icon("heroicon-o-user-group"),
            ])
            ->colors([
                'primary' => '#1f76bb',
            ])
            ->brandLogo(asset('img/light_logo.jpg'))
            ->darkModeBrandLogo(asset('img/dark_logo.PNG'))
            ->brandLogoHeight("56px")
            ->databaseNotifications();
    }
}
