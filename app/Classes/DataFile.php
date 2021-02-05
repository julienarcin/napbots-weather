<?php

namespace App\Classes;

use App\Exceptions\InvalidDataFileException;
use App\Exceptions\MissingDataFileException;
use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * Class DataFile
 * @package App\Classes
 */
class DataFile
{
    /**
     * @var
     */
    public $data;

    /**
     * DataFile constructor.
     */
    public function __construct() {
        if(!Storage::exists('data/data.json')) {
            Storage::put('data/data.json','{}');
        }

        $file = Storage::get('data/data.json');
        $decoded = json_decode($file,true);

        if(empty($decoded) || !is_array($decoded)) {
            throw new InvalidDataFileException();
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

    public function setValue($key, $value) {
        //$this->data = array_replace_recursive()
    }
}
