<?php
namespace Cesargb\Update\Notifications;

use Cesargb\Update\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\SlackAttachment;

class Updated extends BaseNotification
{
    public $packages_updated;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($packages_updated)
    {
        $this->packages_updated=$packages_updated;
    }

    public function toMail(): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject($this->applicationName().' had updated '.count($this->packages_updated).' packages')
            ->line($this->applicationName().' had updated this '.count($this->packages_updated).' packages:');

        foreach ($this->packages_updated as $update) {
            $mailMessage->line($update['package'].': '.$update['version']);
        }

        return $mailMessage;
    }
    public function toSlack(): SlackMessage
    {
        return (new SlackMessage)
            ->to(config('update.notifications.slack.channel'))
            ->content($this->applicationName().' had updated '.count($this->packages_updated).' packages')
            ->attachment(function (SlackAttachment $attachment) {
                foreach ($this->packages_updated as $update) {
                    $attachment->fields([
                        'package'   => $update['package'],
                        'version'   => $update['version']
                    ]);
                }
            });
    }
}
