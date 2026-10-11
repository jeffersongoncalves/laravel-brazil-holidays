<div class="filament-hidden">

![Laravel Brazil Holidays](https://raw.githubusercontent.com/jeffersongoncalves/laravel-brazil-holidays/main/art/jeffersongoncalves-laravel-brazil-holidays.png)

</div>

# Laravel Brazil Holidays

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-brazil-holidays.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-brazil-holidays)
[![Tests](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-brazil-holidays/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-brazil-holidays/actions?query=workflow%3ATests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-brazil-holidays.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-brazil-holidays)

Brazilian national holidays (feriados nacionais) for Laravel: list the holidays of a year, check whether a date is a holiday or a business day, and add or subtract business days.

Easter is calculated in pure PHP, so `ext-calendar` is not required.

## Installation

```bash
composer require jeffersongoncalves/laravel-brazil-holidays
```

Optionally publish the config file:

```bash
php artisan vendor:publish --tag="brazil-holidays-config"
```

## Holidays

| Date | Holiday |
|------|---------|
| 01/01 | Confraternização Universal |
| Easter − 2 | Paixão de Cristo (Good Friday) |
| 21/04 | Tiradentes |
| 01/05 | Dia do Trabalho |
| 07/09 | Independência do Brasil |
| 12/10 | Nossa Senhora Aparecida |
| 02/11 | Finados |
| 15/11 | Proclamação da República |
| 20/11 | Dia Nacional de Zumbi e da Consciência Negra (from 2024, Lei 14.759/2023) |
| 25/12 | Natal |

With `include_optional` enabled, the federal *pontos facultativos* are also treated as holidays: Carnival Monday and Tuesday (Easter − 48 / − 47) and Corpus Christi (Easter + 60).

## Usage

```php
use JeffersonGoncalves\BrazilHolidays\Facades\BrazilHolidays;

BrazilHolidays::forYear(2026);
// ['2026-01-01' => 'Confraternização Universal', '2026-04-03' => 'Paixão de Cristo', ...]

BrazilHolidays::isHoliday('2026-09-07');      // true
BrazilHolidays::isBusinessDay(now());         // false on weekends and holidays

BrazilHolidays::addBusinessDays('2026-10-09', 1);   // 2026-10-13 (skips the weekend and 12/10)
BrazilHolidays::addBusinessDays('2026-10-13', -1);  // 2026-10-09

BrazilHolidays::easter(2026);                 // 2026-04-05
```

Dates may be strings or any `DateTimeInterface`; results are `Carbon\CarbonImmutable`.

You can also inject or instantiate the class directly:

```php
use JeffersonGoncalves\BrazilHolidays\BrazilHolidays;

$holidays = new BrazilHolidays(includeOptional: true, extra: ['09-20' => 'Revolução Farroupilha']);
```

## Configuration

```php
return [
    // Treat Carnival and Corpus Christi as holidays.
    'include_optional' => env('BRAZIL_HOLIDAYS_INCLUDE_OPTIONAL', false),

    // Extra fixed-date holidays (state or municipal), as 'MM-DD' => 'Name'.
    'extra' => [
        // '09-20' => 'Revolução Farroupilha',
        // '01-25' => 'Aniversário de São Paulo',
    ],
];
```

## Testing

```bash
composer test
```

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- Based on [DateHour](https://github.com/jeffersongoncalves/datehour) by Tayron Miranda

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
