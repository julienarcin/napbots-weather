<?php

namespace App\Commands;

use App\Classes\ConfigFile;
use App\Classes\AppFile;
use App\Classes\Napbots;
use Carbon\Carbon;
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
     * @param Napbots $napbots
     * @param ConfigFile $configFile
     * @param AppFile $appFile
     * @return mixed
     * @throws \App\Exceptions\NapbotsInvalidCryptoWeatherException
     * @throws \App\Exceptions\NapbotsNotResponding
     */
    public function handle(Napbots $napbots, ConfigFile $configFile, AppFile $appFile)
    {
        Log::info('⏰  Running cron.');

        $this->alert('Cron');

        // Get crypto weather
        $weather = $napbots->getCryptoWeather();
        if($weather == 'mild_bear') {
            $this->logDisplay('🌧  Current weather is mild-bear or range markets.');
        } elseif($weather == 'mild_bull') {
            $this->logDisplay('☀️  Current weather is mild-bull markets.');
        } elseif($weather == 'extreme') {
            $this->logDisplay('🌪  Current weather is extreme markets.');
        }

        // Compare with app weather
        if(empty($appFile->getValue('last_weather')) || $appFile->getValue('last_weather') !== $weather) {

            // Are we in cooldown mode ? If yes, we shouldn't do anything.
            if($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') > Carbon::now()->timestamp) {
                $cooldownRemaining = $appFile->getValue('cooldown_end') - Carbon::now()->timestamp;
                $this->logDisplay('❄️  Still in cooldown mode for ' . $cooldownRemaining . ' seconds. Nothing to do.', 'info');
            }

            // Are we in cooldown mode ? If no, we should set up cooldown mode (if enabled) or apply market allocation
            if(!$appFile->getValue('cooldown_enabled') || $appFile->getValue('cooldown_end') <= Carbon::now()->timestamp) {
                // If cooldown mode enabled, apply it
                if($configFile->config['weather_change_cooldown']['enabled']) {
                    $this->logDisplay('❄️  ️Applying cooldown mode for ' . $configFile->config['weather_change_cooldown']['duration_seconds'] . ' seconds.', 'info');
                    $appFile->setValue('cooldown_enabled',true);
                    $appFile->setValue('cooldown_end', Carbon::now()->timestamp + $configFile->config['weather_change_cooldown']['duration_seconds']);
                // Else, apply weather strategy immediately
                } else {
                    $this->logDisplay('🔧  Changed allocation for ' . $weather . ' weather.', 'info');
                }

                // Save last weather
                $appFile->setValue('last_weather',$weather);
            }
        } else {
            // Are we in cooldown mode ? If yes, nothing to do
            if($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') > Carbon::now()->timestamp) {
                $cooldownRemaining = $appFile->getValue('cooldown_end') - Carbon::now()->timestamp;
                $this->logDisplay('❄️  Still in cooldown mode for ' . $cooldownRemaining . ' seconds. Nothing to do.', 'info');
            }

            // Weather didn't change and not in cooldown mode
            if(!$appFile->getValue('cooldown_enabled')) {
                $this->logDisplay('👍  Weather didn\'t change. Nothing to do.', 'info');
            }

            // Are we getting out of cooldown mode ? If yes, we should set the weather to the market one, and reset cooldown
            if($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') <= Carbon::now()->timestamp) {
                $this->logDisplay('🔧  Changed allocation for ' . $weather . ' weather.');
                $appFile->setValue('cooldown_enabled',false);
                $appFile->setValue('cooldown_end',0);
            }
        }
    }

    /**
     * Display message + log it
     * @param $message
     */
    public function logDisplay($message, $type = 'line') {
        Log::info($message);
        if($type == 'line') {
            $this->line($message);
        } elseif($type == 'info') {
            $this->info($message);
        }
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
