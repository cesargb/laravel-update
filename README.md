# Recieve notifications when laravel has pending updates or updates them.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cesargb/laravel-update.svg?style=flat-square)](https://packagist.org/packages/cesargb/laravel-update)
[![Build Status](https://img.shields.io/travis/cesargb/laravel-update/master.svg?style=flat-square)](https://travis-ci.org/cesargb/laravel-update)
[![StyleCI](https://styleci.io/repos/99483997/shield)](https://styleci.io/repos/99483997)
[![Total Downloads](https://img.shields.io/packagist/dt/cesargb/laravel-update.svg?style=flat-square)](https://packagist.org/packages/cesargb/laravel-update)

This package allows receive notifications when you have pending updates in Laravel.

Optionally you can schedule the update of the packages.

## Instalation

This package can be used in Laravel 5.4 or higher.

You can install the package via composer:

```bash
composer require cesargb/laravel-update
```

If you have Laravel 5.4, you must add the service provider in `config/app.php` file:

```php
'providers' => [
    // ...
    Cesargb\Update\UpdateServiceProvider::class,
];
```

You can publish config file with:

```
php artisan vendor:publish --provider="Cesargb\Update\UpdateServiceProvider"
```
This are the contents of the published `config/update.php` config file:

```php
return [

    /*
     * You can especified the program composer with full path.
     */
    'composer_bin' => base_path('vendor').'/bin/composer',

    /*
     * By default disables installation of require-dev package.
     * If you want enable require-dev packages, set this param to true
     */
    'require-dev' => false,

    'scheduler' => [

        'check' => [
            'enable'    => true,
            'cron'      => '0 0 * * * *',
        ],

        'update' => [
            'enable'    => false,
            'cron'      => '0 0 * * * *',
        ],

    ],

    'notifications' => [
        'via' => [
            \Cesargb\Update\Notifications\HasUpdates::class => ['mail'],
            \Cesargb\Update\Notifications\Updated::class    => ['mail'],
            \Cesargb\Update\Notifications\HasError::class   => ['mail'],
        ],

        'notifiable' => \Cesargb\Update\Notifications\Notifiable::class,

        'mail' => [
            'to' => 'email@example.com',
        ],

        'slack' => [
            'webhook_url' => '',
            /*
             * If this is set to null the default channel of the webhook will be used.
             */
            'channel' => null,
        ],
    ]

];
```

## Using artisan commands

You can check if has any update with this command:

```bash
php artisan update:check
```

If you want upgrade the packages run:

```bash
php artisan update:packages
```

Optionally you can receive a notification by email and/or Slack, with option `--notify`

```bash
php artisan update:check --notify
php artisan update:packages --notfy
```

## Scheduler

You need to add the following Cron entry to your server.

```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

If you want receive a notification when your system had any upgrade pending, you
can enable it in config file `config/update.php`

```php
'scheduler' => [

    'check' => [
        'enable'    => true,
        'cron'      => '0 0 * * * *',
    ],

    // ...

],
```

If prefer upgrade the system, set a `true` the param `scheduler.update.enable`:

```php
'scheduler' => [

    // ...

    'update' => [
        'enable'    => true,
        'cron'      => '0 0 * * * *',
    ],

],
```

## Notifications

If you use command with argument `--notifiy` or with scheduler, you can receive notifications when be necessary.

You have three type notifications:

* When the process have an error
* When have updates pending
* When update was done

each one of this notifications, you can especified the channel of notify in this
part of the config file `update.php`

```php
'via' => [
    \Cesargb\Update\Notifications\HasUpdates::class => ['mail'],
    \Cesargb\Update\Notifications\Updated::class    => ['slack'],
    \Cesargb\Update\Notifications\HasError::class   => ['mail', 'slack']
],
``` 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
