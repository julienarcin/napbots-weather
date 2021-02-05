<?php

namespace App\Classes;

use App\Exceptions\InvalidAppFileException;
use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * Class AppFile
 * @package App\Classes
 */
class AppFile
{
    /**
     * @var
     */
    public $data;

    /**
     * AppFile constructor.
     */
    public function __construct() {
        if(!Storage::exists('data/app.json')) {
            Storage::put('data/app.json','{}');
        }

        $file = Storage::get('data/app.json');
        $decoded = json_decode($file,true);

        if($decoded === null || !is_array($decoded)) {
            throw new InvalidAppFileException();
        }

        $this->data = $decoded;

        // Return instance
        return $this;
    }

    /**
     * @param $key
     * @return array|ArrayAccess|mixed
     */
    public function getValue($key) {
        return Arr::get($this->data, $key, null);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setValue($key, $value) {
        Arr::set($this->data, $key, $value);

        // Write data file
        Storage::put('data/app.json', json_encode($this->data));
    }
}
