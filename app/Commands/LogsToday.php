<?php

namespace App\Commands;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class LogsToday extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'logs:today';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get logs for today.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Logs for today');

        $date = (new Carbon())->today();
        $filepath = '/logs/napbots-'.$date->format('Y-m-d').'.log';

        if (Storage::exists($filepath)) {
            echo Storage::get($filepath);
        } else {
            $this->line('This log file doesn\'t exist.');
        }
    }
}
