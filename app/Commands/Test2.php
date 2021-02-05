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

class Test2 extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'test2';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Test configuration.';

    /**
     * Execute the console command.
     *
     * @param ConfigFile $configFile
     * @param Napbots $napbots
     * @return mixed
     * @throws NapbotsInvalidCryptoWeatherException
     * @throws \App\Exceptions\NapbotsNotResponding
     */
    public function handle(ConfigFile $configFile, Napbots $napbots)
    {
        // Notify user for succesful tests
        $this->notify("Napbots", "✅  All tests passed successfully.", getcwd() . '/icon.png');
    }
}
