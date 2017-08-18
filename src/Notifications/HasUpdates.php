<?php

namespace Cesargb\Update\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackAttachment;
use Illuminate\Notifications\Messages\SlackMessage;

class HasUpdates extends BaseNotification
{
    public $updates;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($updates)
    {
        $this->updates = $updates;
    }

    public function toMail(): MailMessage
    {
        $mailMessage = (new MailMessage())
            ->error()
            ->subject($this->applicationName().' have '.count($this->updates).' updates pending')
            ->line($this->applicationName().' have '.count($this->updates).' updates pending:');

        foreach ($this->updates as $update) {
            $mailMessage->line('* '.$update['package'].' ('.$update['current_version'].'): '.$update['new_version']);
        }

        return $mailMessage;
    }

    public function toSlack(): SlackMessage
    {
        return (new SlackMessage())
            ->error()
            ->to(config('update.notifications.slack.channel'))
            ->content($this->applicationName().' have '.count($this->updates).' updates pending')
            ->attachment(function (SlackAttachment $attachment) {
                foreach ($this->updates as $update) {
                    $attachment->fields([
                        'package'       => $update['package'],
                        'version'       => $update['current_version'],
                        'new version'   => $update['new_version'],
                    ]);
                }
            });
    }
}
