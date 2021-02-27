<?php

namespace App\Commands;

use Carbon\Carbon;
use App\Classes\AppFile;
use App\Classes\Napbots;
use App\Classes\ConfigFile;
use Illuminate\Support\Facades\Log;
use LaravelZero\Framework\Commands\Command;

class Infos extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'infos';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Get informations about current status.';

    /**
     * Execute the console command.
     *
     * @param Napbots $napbots
     * @param ConfigFile $configFile
     * @param AppFile $appFile
     * @return mixed
     */
    public function handle(Napbots $napbots, ConfigFile $configFile, AppFile $appFile)
    {
        Log::info('💻  Getting napbots infos.');

        $this->alert('Informations');
        $this->output('📡️ Gettings infos');

        try {
            // Get crypto weather
            $weather = $napbots->getCryptoWeather();

            // Authenticate
            $napbots->authenticate($configFile->config['email'], $configFile->config['password'], $configFile->config['user_id']);

            // Get infos
            $infos = $napbots->getExchanges();

            // Crypto weather
            if ($weather == 'mild_bear') {
                $this->output('🌧  Current weather is mild-bear or range markets.');
            } elseif ($weather == 'mild_bull') {
                $this->output('☀️  Current weather is mild-bull markets.');
            } elseif ($weather == 'extreme') {
                $this->output('🌪  Current weather is extreme markets. Trade with prudence.');
            }

            // New line
            $this->newLine();

            // Cooldown infos
            if ($appFile->getValue('cooldown_enabled') && $appFile->getValue('cooldown_end') > Carbon::now()->timestamp) {
                $cooldownRemaining = $appFile->getValue('cooldown_end') - Carbon::now()->timestamp;
                $this->output('❄️  Cooldown: Enabled for '.$cooldownRemaining.' seconds.');
            } else {
                $this->output('❄️  Cooldown mode: Disabled');
            }

            // New line
            $this->newLine();

            // Exchange infos
            foreach ($infos['data'] as $exchange) {
                // Ignore exchange
                if (! in_array(strtolower($exchange['exchange']), array_map('strtolower', $configFile->config['ignored_exchanges']))) {
                    $this->output(' 📈  '.$exchange['exchangeLabel']);

                    // Trading active
                    if ($exchange['tradingActive']) {
                        $this->output(' - ✅ Trading active.');
                    } else {
                        $this->output(' - ❌ Trading inactive.');
                    }

                    // Portfolio value
                    $this->output(' - 💰 Value: $'.$exchange['totalUsdValue'].' / '.$exchange['totalEurValue'].'€');

                    // Portfolio allocation
                    $this->output(' - ⚙️  Allocation:');
                    $this->output('     * Leverage: '.$exchange['compo']['leverage']);
                    $this->output('     * BotOnly: '.($exchange['botOnly'] ? 'true' : 'false'));
                    $this->output('     * Composition:');
                    foreach ($exchange['compo']['compo'] as $key => $value) {
                        $this->output('       '.$key.' => '.$value * 100 .'%');
                    }
                }
            }
            $this->newLine();
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            die();
        }
    }

    /**
     * @param $message
     */
    public function output($message)
    {
        // Resolve ConfigFile
        $configFile = app(ConfigFile::class);

        // Output
        $this->line($message);

        // Log to telegram
        if (! empty($configFile->config['telegram_token']) && ! empty($configFile->config['telegram_chat_ids'])) {
            // Create Telegram API object
            $bot = new \TelegramBot\Api\BotApi($configFile->config['telegram_token']);
            foreach ($configFile->config['telegram_chat_ids'] as $chatId) {
                $bot->sendMessage($chatId, '<pre>NAPBOTS:  '.$message.'</pre>', 'HTML');
            }
        }
    }
}
