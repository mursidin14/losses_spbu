<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LossesDespenserHarian;
use App\Filament\Widgets\OperationtLosses;
use App\Filament\Widgets\PenerimaanChart;
use App\Filament\Widgets\PenerimaanLosses;
use App\Filament\Widgets\StatsLossesBulanan;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\SusutBulananChart;
use App\Filament\Widgets\SusutChartWidget;
use App\Filament\Widgets\SusutHarian;
use App\Filament\Widgets\SusutMingguan;
use App\Models\Product;
use App\Models\Report;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
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
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{

    public function boot(): void
{
    Filament::registerRenderHook(
        'head.start',
        fn () => '<title>Login | Sistem Manajemen Losses</title>'
    );
}

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->profile()
            ->brandName('Sistem Manajemen Losses')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->widgets([
                StatsLossesBulanan::class,
                SusutMingguan::class,
                SusutHarian::class,
                SusutBulananChart::class,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
            ])
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
            ]);
    }
}
