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
        'enable'    => true,
        'cron'      => '0 0 * * * *',
        'command'   => Cesargb\Update\Commands\CheckUpate::class,
    ],

    'notifications' => [
        'via' => [
            \Cesargb\Update\Notifications\HasUpdates::class => ['mail'],
            \Cesargb\Update\Notifications\Updated::class    => ['mail'],
            \Cesargb\Update\Notifications\HasError::class   => ['mail'],
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
    ],

];
