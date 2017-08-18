<?php

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
    ],

];
