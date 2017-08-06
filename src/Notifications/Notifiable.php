<?php

namespace Cesargb\Update\Notifications;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return config('update.notifications.mail.to');
    }

    /**
     * Route notifications for the slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return config('update.notifications.slack.webhook_url');
    }
}
