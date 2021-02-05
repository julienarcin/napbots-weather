<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Cron extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cron';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Run cron';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Storage::put("reminders.txt", "Task 1");
        Log::info('Running cron.');

        $config = Storage::get('config.json');

        $this->info(json_encode(Config::get('napbots')));
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
