<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Filament\Resources\BookingResource;
use App\Filament\Resources\CustomerResource;
use App\Filament\Resources\MailConfigurationResource;
use App\Filament\Resources\MenuResource;
use App\Filament\Resources\NewsResource;
use App\Filament\Resources\ScheduleResource;
use App\Filament\Resources\ServiceResource;
use App\Filament\Resources\SubMenuResource;
use App\Filament\Resources\TherapistScheduleResource;
use App\Filament\Resources\UserProfileResource;
use App\Filament\Resources\UserResource;
use App\Policies\PermissionHelper;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Green,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                $user = auth()->user();
                if ($user && $user->hasRole('therapist') && !$user->hasRole('admin') && !$user->is_superuser) {
                    return $builder->groups([
                        NavigationGroup::make()
                            ->items([
                                NavigationItem::make('Dashboard')
                                    ->icon('heroicon-o-home')
                                    ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                    ->url(route('filament.admin.pages.dashboard'))
                                    ->activeIcon('heroicon-s-home'),
                            ]),
                        NavigationGroup::make('Therapist Menu')
                            ->items([
                                ...BookingResource::getNavigationItems(),
                                ...TherapistScheduleResource::getNavigationItems(),
                            ]),
                    ]);
                }

                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                                ->icon('heroicon-o-home')
                                ->isActiveWhen(fn(): bool => request()->routeIs('filament.admin.pages.dashboard'))
                                ->url(route('filament.admin.pages.dashboard'))
                                ->activeIcon('heroicon-s-home'),
                            ...NewsResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Menus')
                        ->items([
                            ...MenuResource::getNavigationItems(),
                            ...SubMenuResource::getNavigationItems(),
                        ]),
                    NavigationGroup::make('Customers and Orders')
                        ->items([
                            ...CustomerResource::getNavigationItems(),
                            ...BookingResource::getNavigationItems(),
                            ...ServiceResource::getNavigationItems(),
                            ...ScheduleResource::getNavigationItems(),
                            ...TherapistScheduleResource::getNavigationItems(),
                        ]),
                    // NavigationGroup::make('Users and Permissions')
                    //     ->items([
                    //         ...UserResource::getNavigationItems(),
                    //         ...UserProfileResource::getNavigationItems(),
                    //         NavigationItem::make('Roles')
                    //             ->icon('heroicon-o-user-group')
                    //             ->isActiveWhen(fn(): bool => request()->routeIs([
                    //                 'filament.admin.resources.roles.index',
                    //                 'filament.admin.resources.roles.create',
                    //                 'filament.admin.resources.roles.edit',
                    //                 'filament.admin.resources.roles.view',
                    //             ]))
                    //             ->url(fn(): string => '/admin/roles'),
                    //         NavigationItem::make('Permissions')
                    //             ->icon('heroicon-o-lock-closed')
                    //             ->isActiveWhen(fn(): bool => request()->routeIs([
                    //                 'filament.admin.resources.permissions.index',
                    //                 'filament.admin.resources.permissions.create',
                    //                 'filament.admin.resources.permissions.edit',
                    //                 'filament.admin.resources.permissions.view',
                    //             ]))
                    //             ->url(fn(): string => '/admin/permissions'),

                    //     ]),
                    NavigationGroup::make('Users and Permissions')
                        ->items(array_merge(
                            PermissionHelper::canViewMenu() ? UserResource::getNavigationItems() : [],
                            PermissionHelper::canViewMenu() ? UserProfileResource::getNavigationItems() : [],
                            PermissionHelper::canViewRoles()
                                ? [
                                    NavigationItem::make('Roles')
                                        ->icon('heroicon-o-user-group')
                                        ->isActiveWhen(fn(): bool => request()->routeIs([
                                            'filament.admin.resources.roles.index',
                                            'filament.admin.resources.roles.create',
                                            'filament.admin.resources.roles.edit',
                                            'filament.admin.resources.roles.view',
                                        ]))
                                        ->url('/admin/roles'),
                                ] : [],
                            PermissionHelper::canViewPermissions()
                                ? [
                                    NavigationItem::make('Permissions')
                                        ->icon('heroicon-o-lock-closed')
                                        ->isActiveWhen(fn(): bool => request()->routeIs([
                                            'filament.admin.resources.permissions.index',
                                            'filament.admin.resources.permissions.create',
                                            'filament.admin.resources.permissions.edit',
                                            'filament.admin.resources.permissions.view',
                                        ]))
                                        ->url('/admin/permissions'),
                                ] : [],
                        )),
                    NavigationGroup::make('Settings')
                        ->items(array_merge(
                            PermissionHelper::canViewEmailConfiguration() ? MailConfigurationResource::getNavigationItems() : [],
                        )),
                ]);
            });
    }
}
