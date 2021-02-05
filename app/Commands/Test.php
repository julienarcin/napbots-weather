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
    protected $description = 'Test configuration.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert('Testing configuration');

        try {
            // Get configuration
            $configFile = new ConfigFile();

            // Log successful config file existence + format
            $this->line('✅  Config file exists.');
            $this->line('✅  Config file is json.');

            // Check configuration file
            $configFile->checkFile();

            // Log successful config file check
            $this->line('✅  Config file fields all present.');
            $this->line('✅  Config file allocations valid.');

            // Create napbots instance
            $napbots = new Napbots($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);

            // Get crypto weather
            $weather = $napbots->getCryptoWeather();

            // Log successful napbots crypto weather
            $this->line('✅  Napbots crypto weather connection successful (' . $weather . ').');

            // Try to authenticate
            $napbots->authenticate();

            // Log successful napbots authentication
            $this->line('✅  Napbots authentication is successful.');

            // Try to get infos
            $napbots->getInfos();

            // Log successful napbots getting current allocation
            $this->line('✅  Napbots management is successful.');

            // Log succesful tests
            Log::info('All tests passed successfully.');

            // OK
            $this->newLine(1);
            $this->info('🚀 Script ready.');

        } catch(\Exception $exception) {
            $this->error($exception->getMessage());
            Log::error($exception->getMessage());
            die();
        }
    }
}
