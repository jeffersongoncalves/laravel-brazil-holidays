<?php

use JeffersonGoncalves\BrazilHolidays\BrazilHolidays;
use JeffersonGoncalves\BrazilHolidays\Facades\BrazilHolidays as BrazilHolidaysFacade;

it('computes easter', function (int $year, string $expected) {
    expect((new BrazilHolidays)->easter($year)->toDateString())->toBe($expected);
})->with([
    [2000, '2000-04-23'],
    [2019, '2019-04-21'],
    [2024, '2024-03-31'],
    [2025, '2025-04-20'],
    [2026, '2026-04-05'],
    [2038, '2038-04-25'],
]);

it('lists the national holidays of 2026', function () {
    expect((new BrazilHolidays)->forYear(2026))->toBe([
        '2026-01-01' => 'Confraternização Universal',
        '2026-04-03' => 'Paixão de Cristo',
        '2026-04-21' => 'Tiradentes',
        '2026-05-01' => 'Dia do Trabalho',
        '2026-09-07' => 'Independência do Brasil',
        '2026-10-12' => 'Nossa Senhora Aparecida',
        '2026-11-02' => 'Finados',
        '2026-11-15' => 'Proclamação da República',
        '2026-11-20' => 'Dia Nacional de Zumbi e da Consciência Negra',
        '2026-12-25' => 'Natal',
    ]);
});

it('only includes Consciência Negra from 2024 on', function () {
    expect((new BrazilHolidays)->isHoliday('2023-11-20'))->toBeFalse()
        ->and((new BrazilHolidays)->isHoliday('2024-11-20'))->toBeTrue();
});

it('includes optional days and extra holidays when asked', function () {
    $holidays = (new BrazilHolidays(true, ['09-20' => 'Revolução Farroupilha']))->forYear(2026);

    expect($holidays)
        ->toHaveKey('2026-02-16', 'Carnaval')
        ->toHaveKey('2026-02-17', 'Carnaval')
        ->toHaveKey('2026-06-04', 'Corpus Christi')
        ->toHaveKey('2026-09-20', 'Revolução Farroupilha')
        ->and(array_keys($holidays))->toBe(collect($holidays)->keys()->sort()->values()->all());
});

it('checks business days', function () {
    $holidays = new BrazilHolidays;

    expect($holidays->isBusinessDay('2026-10-13'))->toBeTrue()  // Tuesday
        ->and($holidays->isBusinessDay('2026-10-12'))->toBeFalse() // Aparecida (Monday)
        ->and($holidays->isBusinessDay('2026-10-10'))->toBeFalse(); // Saturday
});

it('adds and subtracts business days skipping weekends and holidays', function () {
    $holidays = new BrazilHolidays;

    // Fri 2026-10-09 + 1 -> skips weekend and Mon 12 (Aparecida) -> Tue 13
    expect($holidays->addBusinessDays('2026-10-09', 1)->toDateString())->toBe('2026-10-13')
        ->and($holidays->addBusinessDays('2026-10-13', -1)->toDateString())->toBe('2026-10-09')
        ->and($holidays->addBusinessDays('2026-12-31', 1)->toDateString())->toBe('2027-01-04')
        ->and($holidays->addBusinessDays('2026-10-09', 0)->toDateString())->toBe('2026-10-09');
});

it('resolves the facade from config', function () {
    config()->set('brazil-holidays.extra', ['01-25' => 'Aniversário de São Paulo']);
    app()->forgetInstance(BrazilHolidays::class);

    expect(BrazilHolidaysFacade::isHoliday('2026-01-25'))->toBeTrue()
        ->and(BrazilHolidaysFacade::isHoliday('2026-02-17'))->toBeFalse();
});
