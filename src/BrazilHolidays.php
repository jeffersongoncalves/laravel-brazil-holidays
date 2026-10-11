<?php

namespace JeffersonGoncalves\BrazilHolidays;

use Carbon\CarbonImmutable;
use DateTimeInterface;

class BrazilHolidays
{
    /**
     * @param  bool  $includeOptional  Treat federal "pontos facultativos" (Carnival, Corpus Christi) as holidays.
     * @param  array<string, string>  $extra  Extra yearly holidays as ['MM-DD' => 'Name'] (state/municipal).
     */
    public function __construct(
        protected bool $includeOptional = false,
        protected array $extra = [],
    ) {}

    /**
     * Holidays of the given year, sorted by date.
     *
     * @return array<string, string> ['Y-m-d' => 'Name']
     */
    public function forYear(int $year): array
    {
        $easter = $this->easter($year);

        $holidays = [
            "$year-01-01" => 'Confraternização Universal',
            $easter->subDays(2)->toDateString() => 'Paixão de Cristo',
            "$year-04-21" => 'Tiradentes',
            "$year-05-01" => 'Dia do Trabalho',
            "$year-09-07" => 'Independência do Brasil',
            "$year-10-12" => 'Nossa Senhora Aparecida',
            "$year-11-02" => 'Finados',
            "$year-11-15" => 'Proclamação da República',
            "$year-12-25" => 'Natal',
        ];

        // Lei 14.759/2023
        if ($year >= 2024) {
            $holidays["$year-11-20"] = 'Dia Nacional de Zumbi e da Consciência Negra';
        }

        if ($this->includeOptional) {
            $holidays[$easter->subDays(48)->toDateString()] = 'Carnaval';
            $holidays[$easter->subDays(47)->toDateString()] = 'Carnaval';
            $holidays[$easter->addDays(60)->toDateString()] = 'Corpus Christi';
        }

        foreach ($this->extra as $monthDay => $name) {
            $holidays["$year-$monthDay"] = $name;
        }

        ksort($holidays);

        return $holidays;
    }

    public function isHoliday(DateTimeInterface|string $date): bool
    {
        $date = CarbonImmutable::parse($date);

        return isset($this->forYear($date->year)[$date->toDateString()]);
    }

    public function isBusinessDay(DateTimeInterface|string $date): bool
    {
        $date = CarbonImmutable::parse($date);

        return ! $date->isWeekend() && ! $this->isHoliday($date);
    }

    /**
     * Move forward (or backward, when $days is negative) the given number of business days.
     */
    public function addBusinessDays(DateTimeInterface|string $date, int $days): CarbonImmutable
    {
        $date = CarbonImmutable::parse($date);
        $step = $days < 0 ? -1 : 1;

        for ($left = abs($days); $left > 0;) {
            $date = $date->addDays($step);

            if ($this->isBusinessDay($date)) {
                $left--;
            }
        }

        return $date;
    }

    /**
     * Easter Sunday (Anonymous Gregorian algorithm), no ext-calendar needed.
     */
    public function easter(int $year): CarbonImmutable
    {
        $a = $year % 19;
        $b = intdiv($year, 100);
        $c = $year % 100;
        $d = intdiv($b, 4);
        $e = $b % 4;
        $f = intdiv($b + 8, 25);
        $g = intdiv($b - $f + 1, 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intdiv($c, 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intdiv($a + 11 * $h + 22 * $l, 451);
        $month = intdiv($h + $l - 7 * $m + 114, 31);
        $day = (($h + $l - 7 * $m + 114) % 31) + 1;

        return CarbonImmutable::parse(sprintf('%04d-%02d-%02d', $year, $month, $day));
    }
}
