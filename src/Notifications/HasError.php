<?php

namespace Cesargb\Update\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class HasError extends BaseNotification
{
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->error()
            ->subject($this->applicationName().' error to update')
            ->line($this->applicationName().' get error when update: '.$this->message);
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage())
            ->error()
            ->to(config('update.notifications.slack.channel'))
            ->content($this->applicationName().' get error when update: '.$this->message);
    }
}
