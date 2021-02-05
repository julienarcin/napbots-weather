<?php

namespace App\Commands;

use App\Classes\ConfigFile;
use App\Classes\Napbots;
use App\Exceptions\InvalidConfigFileException;
use App\Exceptions\MissingConfigFileException;
use App\Exceptions\MissingConfigFileFieldException;
use App\Exceptions\NapbotsAuthException;
use App\Exceptions\NapbotsInvalidCryptoWeatherException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Test extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Test napbots configuration.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // Get configuration
            $configFile = new ConfigFile();

            // Log successful config file existence + format
            $this->info('✅  Config file exists.');
            $this->info('✅  Config file is json.');

            // Check configuration file
            $configFile->checkFile();

            // Log successful config file check
            $this->info('✅  Config file fields all present.');
            $this->info('✅  Config file allocations valid.');

            // Try to log-in to napbots
            $napbots = new Napbots($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);
            $napbots->authenticate();

            // Log successful napbots connexion
            $this->info('✅  Napbots authentication is successful.');

            // Get crypto weather
            $weather = $napbots->getCryptoWeather();

            // Log successful napbots crypto weather
            $this->info('✅  Napbots crypto weather connection successful (' . $weather . ').');
        } catch(\Exception $exception) {
            $this->error($exception->getMessage());
            die();
        }
    }
}
