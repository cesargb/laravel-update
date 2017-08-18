# Recieve notifications when laravel has pending updates or updates them.

This package allows you schedule to receive notifications when you have pending updates in Laravel.

Optionally you can schedule the update of the packages.

## Instalation

This package can be used in Laravel 5.4 or higher.

You can install the package via composer:

```bash
composer require cesargb/laravel-update
```

Now add the service provider in `config/app.php` file:

```php
'providers' => [
    // ...
    Cesargb\Update\UpdateServiceProvider::class,
];
```

You can publish config file with:

```
php artisan vendor:publish --provider="Cesargb\Update\UpdateServiceProvider" --tag=config
```
This are the contents of the published `config/update.php` config file:

```php
return [

    /*
     * You can especified the program composer with full path.
     */
    'composer_bin' => 'composer',

    /*
     * By default disables installation of require-dev package.
     * If you want enable require-dev packages, set this param to true
     */
    'require-dev' => false,

    'scheduler' => [

        'check' => [
            'enable'    => true,
            'cron'      => '0 0 * * * *'
        ],

        'update' => [
            'enable'    => false,
            'cron'      => '0 0 * * * *'
        ],

    ],

    'notifications' => [
        'via' => [
            \Cesargb\Update\Notifications\HasUpdates::class => ['mail'],
            \Cesargb\Update\Notifications\Updated::class    => ['mail'],
            \Cesargb\Update\Notifications\HasError::class   => ['mail']
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

If you want upgrade the packages, run:

```bash
php artisan update:packages
```

Optionally you can receive a notification by email and/or Slack, with option `--notfy`

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
        'cron'      => '0 0 * * * *'
    ],

    // ...

],
```

If prefer upgrade the system, change de command for `Cesargb\Update\Commands\Update::class`

```php
'scheduler' => [

    // ...

    'update' => [
        'enable'    => true,
        'cron'      => '0 0 * * * *'
    ],

],
```

## Notifications

If you use command with argument `--notifiy` or with scheduler, you can receive notifications when be necessary.

You have three type notifications:

* when you have Error
* When have updates pending
* When update was make

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
