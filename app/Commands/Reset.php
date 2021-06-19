<?php

namespace App\Commands;

use App\Classes\AppFile;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;

class Reset extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'reset';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Reset application state.';

    /**
     * Execute the console command.
     *
     * @param AppFile $appFile
     * @return mixed
     */
    public function handle(AppFile $appFile)
    {
        Log::info('🗑  Resetting application state.');

        $this->alert('Resetting application state');

        // Delete app file
        $appFile->delete();

        // Notify user for succesful reset
        $this->notify('Napbots Weather', '✅  Application state reset.', getcwd().'/icon.png');

        // Log succesful reset
        Log::info('✅  Application state reset.');

        // Notify user for succesful reset
        $this->notify('Napbots Weather', '✅  Application state reset.', 'icon.png');

        // OK
        $this->newLine(1);
        $this->info('✅  Application state reset.');
    }
}
