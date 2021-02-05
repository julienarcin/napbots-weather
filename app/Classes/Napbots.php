<?php

namespace App\Classes;

use App\Exceptions\NapbotsAuthException;
use App\Exceptions\NapbotsInvalidCryptoWeatherException;

/**
 * Class Napbots
 * @package App\Classes
 */
class Napbots
{
    /**
     * @var
     */
     public $email;

    /**
     * @var
     */
     public $password;

    /**
     * @var
     */
     public $userId;

    /**
     * @var
     */
     private $authToken;

    /**
     * Napbots constructor.
     * @param $email
     * @param $password
     * @param $userId
     */
    public function __construct($email, $password, $userId) {
        // Set data
        $this->email = $email;
        $this->password = $password;
        $this->userId = $userId;

        // Return instance
        return $this;
    }

    /**
     * Authenticate to Napbots
     */
    public function authenticate() {
        // Login to app (get auth token)
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://middle.napbots.com/v1/user/login' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => $this->email, 'password' => $this->password]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45000); // 45s timeout
        $response = curl_exec ($ch);
        curl_close($ch);

        $json = json_decode($response,true);
        if($json['success'] !== true || empty($json['data']) || empty($json['data']['accessToken'])) {
            throw new NapbotsAuthException();
        }

        $this->authToken = $json['data']['accessToken'];

        // Return instance
        return $this;
    }

    /**
     * Get crypto weather
     */
    public function getCryptoWeather() {
        // Get crypto weather
        $weatherApi = file_get_contents('https://middle.napbots.com/v1/crypto-weather');
        if($weatherApi) {
            $weather = json_decode($weatherApi,true)['data']['weather']['weather'];
        }

        // Check crypto weather
        if($weather === 'Extreme markets') {
            return 'extreme';
        } elseif($weather === 'Mild bull markets'){
            return 'mild_bull';
        } elseif($weather === 'Mild bear or range markets') {
            return 'mild_bear';
        } else {
            throw new NapbotsInvalidCryptoWeatherException($weather);
        }
    }
}
