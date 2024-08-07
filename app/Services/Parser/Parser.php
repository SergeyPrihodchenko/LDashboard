<?php

namespace App\Services\Parser;

use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;
use Exception;

class Parser {

    public static function recordingCSV($data, $title)
    {
        try {
            $file_path = __DIR__ . '/../../../tmp/direct_'.$title.'_'.date('Y-m-d').'.csv';

            file_put_contents($file_path, $data);

            return $file_path;
        } catch (Exception $e) {
            return false;
        }
    }

}