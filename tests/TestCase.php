<?php

namespace JeffersonGoncalves\BrazilHolidays\Tests;

use JeffersonGoncalves\BrazilHolidays\BrazilHolidaysServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BrazilHolidaysServiceProvider::class];
    }
}
