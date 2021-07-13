<?php

namespace App\Commands;

use Carbon\Carbon;
use App\Classes\AppFile;
use App\Classes\Napbots;
use App\Classes\ConfigFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;
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
    protected $description = 'Run automated tasks.';

    /**
     * Execute the console command.
     *
     * @param Napbots $napbots
     * @param ConfigFile $configFile
     * @param AppFile $appFile
     * @throws \App\Exceptions\NapbotsInvalidCryptoWeatherException
     * @throws \App\Exceptions\NapbotsNotResponding
     * @return mixed
     */
    public function handle(Napbots $napbots, ConfigFile $configFile, AppFile $appFile)
    {
        $this->logDisplayNotify('⏰  Running cron.');

        if ($this->option('verbose')) {
            $this->alert('Cron');
        }

        // Get crypto weather
        $weather = $napbots->getCryptoWeather();

        if ($weather == 'mild_bear') {
            $this->logDisplayNotify('🌧  Weather: mild-bear');
        } elseif ($weather == 'mild_bull') {
            $this->logDisplayNotify('☀️  Weather: mild-bull');
        } elseif ($weather == 'extreme') {
            $this->logDisplayNotify('🌪  Weather: extreme');
        }

        // Compare with app weather
        if (empty($appFile->getValue('last_weather')) || $appFile->getValue('last_weather') !== $weather) {
            // Are we in cooldown mode ? If yes, we shouldn't do anything.
            if ($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') > Carbon::now()->timestamp) {
                // Nothing to do, still in cooldown
                $cooldownRemaining = $appFile->getValue('cooldown_end') - Carbon::now()->timestamp;
                $this->logDisplayNotify('❄️ Cooldown for '.$cooldownRemaining.'s.', 'info');
            }

            // Are we in cooldown mode ? If no, we should set up cooldown mode (if enabled) or apply market allocation
            if (! $appFile->getValue('cooldown_enabled') || $appFile->getValue('cooldown_end') <= Carbon::now()->timestamp) {
                // If cooldown mode enabled, apply it
                if ($configFile->config['weather_change_cooldown']['enabled'] && in_array($appFile->getValue('last_weather'), $configFile->config['weather_change_cooldown']['condition_old_weather']) && in_array($weather, $configFile->config['weather_change_cooldown']['condition_new_weather'])) {
                    // Authenticate to napbots
                    $napbots->authenticate($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);

                    // Apply cooldown allocation
                    $napbots->setAllocation($configFile->config['weather_change_cooldown']['allocation']);

                    // Enable cooldown
                    $appFile->setValue('cooldown_enabled', true);
                    $appFile->setValue('cooldown_end', Carbon::now()->timestamp + $configFile->config['weather_change_cooldown']['duration_seconds']);

                    // Notify user
                    $this->logDisplayNotify('❄️  Applied cooldown for '.$configFile->config['weather_change_cooldown']['duration_seconds'].'s.', 'info');
                // Else, apply weather strategy immediately
                } else {
                    // Authenticate to napbots
                    $napbots->authenticate($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);

                    // Apply weather allocation
                    $napbots->setAllocation($configFile->config['allocations'][$weather]);

                    // Log
                    $this->logDisplayNotify('🔧 Changed allocation for '.$weather.' markets.', 'info');
                }

                // Save last weather
                $appFile->setValue('last_weather', $weather);
            }
        } else {
            // Are we in cooldown mode ? If yes, nothing to do
            if ($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') > Carbon::now()->timestamp) {
                // Nothing to do, still in cooldown
                $cooldownRemaining = $appFile->getValue('cooldown_end') - Carbon::now()->timestamp;
                $this->logDisplayNotify('❄️  Cooldown for '.$cooldownRemaining.'s. Nothing to do.', 'info');

            // Weather didn't change and not in cooldown mode
            } elseif (! $appFile->getValue('cooldown_enabled')) {
                // Nothing to do, same weather
                $this->logDisplayNotify('👍  Weather didn\'t change.', 'info');

            // Are we getting out of cooldown mode ? If yes, we should set the weather to the market one, and reset cooldown
            } elseif ($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') <= Carbon::now()->timestamp) {
                // Authenticate to napbots
                $napbots->authenticate($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);

                // Apply weather allocation
                $napbots->setAllocation($configFile->config['allocations'][$weather]);

                // Log
                $this->logDisplayNotify('🔧 Changed allocation for '.$weather.' markets.');

                // Disable cooldown
                $appFile->setValue('cooldown_enabled', false);
                $appFile->setValue('cooldown_end', 0);
            }
        }

        // Ping cron url if set
        if (! empty($configFile->config['cron_ping_url'])) {
            file_get_contents($configFile->config['cron_ping_url']);
        }
    }

    /**
     * Display message + log it.
     * @param $message
     */
    public function logDisplayNotify($message, $type = 'line')
    {
        // Resolve ConfigFile
        $configFile = app(ConfigFile::class);

        Log::info($message);

        if ($this->option('verbose')) {
            if ($type == 'line') {
                $this->line($message);
            } elseif ($type == 'info') {
                $this->info($message);
            }
            $this->notify('Napbots', $message, 'icon.png');
        }

        // Log to telegram
        if (! empty($configFile->config['telegram_token']) && ! empty($configFile->config['telegram_chat_ids'])) {
            // Create Telegram API object
            $bot = new \TelegramBot\Api\BotApi($configFile->config['telegram_token']);
            foreach ($configFile->config['telegram_chat_ids'] as $chatId) {
                $bot->sendMessage($chatId, '<pre>NW:  '.$message.'</pre>', 'HTML');
            }
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     */
    public function schedule(Schedule $schedule)
    {
        $schedule->command(static::class)->hourly();
    }
}
