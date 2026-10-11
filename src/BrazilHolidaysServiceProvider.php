<?php

namespace JeffersonGoncalves\BrazilHolidays;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BrazilHolidaysServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('brazil-holidays')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(BrazilHolidays::class, fn ($app) => new BrazilHolidays(
            (bool) $app['config']->get('brazil-holidays.include_optional', false),
            (array) $app['config']->get('brazil-holidays.extra', []),
        ));
    }
}
