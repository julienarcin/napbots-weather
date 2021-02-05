<?php

namespace App\Classes;

use App\Exceptions\NapbotsAuthException;
use App\Exceptions\NapbotsInvalidCryptoWeatherException;
use App\Exceptions\NapbotsInvalidInfosException;
use App\Exceptions\NapbotsNotResponding;
use App\Exceptions\NapbotsUnauthenticated;

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
     * Authenticate to Napbots
     * @param $email
     * @param $password
     * @param $userId
     * @return Napbots
     * @throws NapbotsAuthException
     */
    public function authenticate($email, $password, $userId): Napbots
    {
        // Set data
        $this->email = $email;
        $this->password = $password;
        $this->userId = $userId;

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

        // Napbots not responding
        if(!is_array($json) || !isset($json['success'])) {
            throw new NapbotsNotResponding();
        }

        // Napbots auth exception
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
    public function getCryptoWeather(): string
    {
        // Get crypto weather
        try {
            $weatherApi = file_get_contents('https://middle.napbots.com/v1/crypto-weather');
        } catch(\ErrorException $exception) {
            throw new NapbotsNotResponding();
        }

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

    /**
     * Get informations
     */
    public function getInfos() {
        // Unauthenticated
        if(empty($this->authToken)) {
            throw new NapbotsUnauthenticated();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://middle.napbots.com/v1/account/for-user/' . $this->userId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'token: ' . $this->authToken]);
        $response = curl_exec ($ch);

        $json = json_decode($response,true);

        // Napbots not responding
        if(!is_array($json) || !isset($json['success'])) {
            throw new NapbotsNotResponding();
        }

        // Napbots invalid infos
        if(!$json['success'] || empty($json['data']) || !is_array($json['data'])) {
            throw new NapbotsInvalidInfosException();
        }

        return json_decode($response,true);
    }
}
