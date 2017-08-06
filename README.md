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
This is the contents of the published `config/update.php` config file:

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
        'enable'    => true,
        'cron'      => '0 0 * * * *',
        'command'   => Cesargb\Update\Commands\CheckUpate::class
    ],

    'notifications' => [
        'via' => [
            \Cesargb\Update\Notifications\HasUpdates::class => ['mail'],
            \Cesargb\Update\Notifications\Updated::class    => ['mail'],
            \Cesargb\Update\Notifications\HasError::class   => ['mail']
        ],

        'notifiable' => \Cesargb\Update\Notifications\Notifiable::class,

        'mail' => [
            'to' => 'email@example.con',
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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
