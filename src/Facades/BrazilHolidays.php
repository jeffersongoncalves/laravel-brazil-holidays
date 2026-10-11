<?php

namespace JeffersonGoncalves\BrazilHolidays\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array<string, string> forYear(int $year)
 * @method static bool isHoliday(\DateTimeInterface|string $date)
 * @method static bool isBusinessDay(\DateTimeInterface|string $date)
 * @method static \Carbon\CarbonImmutable addBusinessDays(\DateTimeInterface|string $date, int $days)
 * @method static \Carbon\CarbonImmutable easter(int $year)
 *
 * @see \JeffersonGoncalves\BrazilHolidays\BrazilHolidays
 */
class BrazilHolidays extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \JeffersonGoncalves\BrazilHolidays\BrazilHolidays::class;
    }
}
