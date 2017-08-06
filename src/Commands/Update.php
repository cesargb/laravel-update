<?php
namespace Cesargb\Update\Commands;

use Illuminate\Console\Command;
use Cesargb\Update\Notifications\Updated;
use Cesargb\Update\Notifications\HasError;

class Update extends Command
{
    protected $signature = 'update:packages
                            {--notify} : Must notified the changes';
    protected $description = 'Updates of composer packages';

    const MARK = '  - Installing ';

    public function handle()
    {
        $composer_bin=config('update.composer_bin','composer');
        $no_dev=config('update.require-dev',false) ?: ' --no-dev';

        exec($composer_bin.' update ' . $no_dev . ' 2>&1', $out, $ret);

        if ($ret !== 0) {
            if ($this->option('notify')) {
                app(config('update.notifications.notifiable'))->notify(new HasError($out[0]));
            }
            throw ExceptionExecCommand::create(explode("\n",$this->signature)[0], $ret, $out[0]);
        } else {
            $packages_updated=[];
            foreach ($out as $line) {
                if (starts_with($line, self::MARK)) {
                    $package_updated = explode(' ', str_after($line, self::MARK));
                    if (count($package_updated) > 1) {
                        $packages_updated[] = [
                            'package' => $package_updated[0],
                            'version'   => str_replace(['(',')'], '', $package_updated[1])
                        ];
                    }
                }
            }
            if (count($packages_updated) === 0) {
                $this->info('All is correct! No updates pending');
            } else {
                $this->info(count($packages_updated) . ' package(s) updated.', 'comment');
                foreach ($packages_updated as $update){
                    $this->info(' - '.$update['package'].': '.$update['version']);
                }
                if ($this->option('notify')) {
                    app(config('update.notifications.notifiable'))->notify(new Updated($updates));
                }
            }
        }
    }
}
