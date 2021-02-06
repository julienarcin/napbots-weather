<?php

namespace App\Commands;

use App\Classes\ConfigFile;
use App\Classes\Napbots;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;

class Check extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Check configuration.';

    /**
     * Execute the console command.
     *
     * @param ConfigFile $configFile
     * @param Napbots $napbots
     * @return mixed
     */
    public function handle(ConfigFile $configFile, Napbots $napbots)
    {
        Log::info('💻  Checking configuration.');

        $this->alert('Checking configuration');

        try {
            // Log successful config file existence + format
            $this->line('✅  Config file exists.');
            $this->line('✅  Config file is json.');

            // Check configuration file
            $configFile->checkFile();

            // Log successful config file check
            $this->line('✅  Config file fields all present.');
            $this->line('✅  Config file allocations valid.');

            // Get crypto weather
            $weather = $napbots->getCryptoWeather();

            // Log successful napbots crypto weather
            $this->line('✅  Napbots crypto weather connection successful (' . $weather . ').');

            // Try to authenticate
            $napbots->authenticate($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);

            // Log successful napbots authentication
            $this->line('✅  Napbots authentication is successful.');

            // Try to get infos
            $napbots->getExchanges();

            // Log successful napbots getting current allocation
            $this->line('✅  Napbots management is successful.');

            // Log succesful tests
            Log::info('✅  All checks passed successfully.');

            // Notify user for succesful tests
            $this->notify("Napbots", "✅  All checks passed successfully.", "icon.png");

            // OK
            $this->newLine(1);
            $this->info('🚀 All checks passed successfully.');

        } catch(\Exception $exception) {
            $this->error($exception->getMessage());
            Log::error($exception->getMessage());
            die();
        }
    }
}
