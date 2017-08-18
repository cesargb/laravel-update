<?php

namespace Cesargb\Update\Commands;

use Cesargb\Update\Exceptions\ExceptionExecCommand;
use Cesargb\Update\Notifications\HasError;
use Cesargb\Update\Notifications\HasUpdates;
use Illuminate\Console\Command;

class CheckUpdate extends Command
{
    protected $signature = 'update:check
                            {--notify} : Must notified the updates';
    protected $description = 'Check updates of composer packages';

    const MARK = '  - Updating ';

    public function handle()
    {
        $composer_bin = config('update.composer_bin', 'composer');
        $no_dev = config('update.require-dev', false) ? '' : ' --no-dev';

        exec($composer_bin.' update --dry-run'.$no_dev.' 2>&1', $out, $ret);

        if ($ret !== 0) {
            if ($this->option('notify')) {
                app(config('update.notifications.notifiable', \Cesargb\Update\Notifications\Notifiable::class))->notify(new HasError($out[0]));
            }

            throw ExceptionExecCommand::create(explode("\n", $this->signature)[0], $ret, $out[0]);
        } else {
            $updates = [];
            foreach ($out as $line) {
                if (starts_with($line, self::MARK)) {
                    $update = explode(' ', str_after($line, self::MARK));
                    if (count($update) > 3) {
                        $updates[] = [
                            'package'           => $update[0],
                            'current_version'   => str_replace(['(', ')'], '', $update[1]),
                            'new_version'       => str_replace(['(', ')'], '', $update[4]),
                        ];
                    }
                }
            }
            if (count($updates) === 0) {
                $this->info('All is correct! No updates pending');
            } else {
                $this->line('You have '.count($updates).' package(s) to update.', 'comment');
                foreach ($updates as $update) {
                    $this->line(' - '.$update['package'].' ('.$update['current_version'].'): '.$update['new_version'], 'comment');
                }
                if ($this->option('notify')) {
                    app(config('update.notifications.notifiable', \Cesargb\Update\Notifications\Notifiable::class))->notify(new HasUpdates($updates));
                }
            }
        }
    }
}
