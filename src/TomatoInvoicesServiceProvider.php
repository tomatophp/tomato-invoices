<?php

namespace TomatoPHP\TomatoInvoices;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\TomatoAdmin\Facade\TomatoMenu;
use TomatoPHP\TomatoAdmin\Services\Contracts\Menu;


class TomatoInvoicesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\TomatoInvoices\Console\TomatoInvoicesInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/tomato-invoices.php', 'tomato-invoices');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/tomato-invoices.php' => config_path('tomato-invoices.php'),
        ], 'tomato-invoices-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'tomato-invoices-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tomato-invoices');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/tomato-invoices'),
        ], 'tomato-invoices-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tomato-invoices');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/tomato-invoices'),
        ], 'tomato-invoices-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }

    public function boot(): void
    {
        TomatoMenu::register([
            Menu::make()
                ->group(__('Invoices'))
                ->label(__('Invoices'))
                ->route('admin.invoices.index')
                ->icon('bx bx-money')
        ]);
    }
}
