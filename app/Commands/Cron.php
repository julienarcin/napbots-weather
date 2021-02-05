<?php

namespace App\Commands;

use App\Classes\ConfigFile;
use App\Classes\DataFile;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\VarDumper\Cloner\Data;

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
    protected $description = 'Run automated tasks.';

    /**
     * Execute the console command.
     *
     * @param ConfigFile $configFile
     * @param DataFile $dataFile
     * @return mixed
     */
    public function handle(ConfigFile $configFile, DataFile $dataFile)
    {
        Log::info('⏰  Running cron.');
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
