<?php
namespace Cesargb\Update\Notifications;

use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        $notificationChannels = config('update.notifications.via.'.static::class);
        return array_filter($notificationChannels);
    }

    public function applicationName(): string
    {
        return config('app.name') ?? config('app.url') ?? 'Laravel application';
    }
}
