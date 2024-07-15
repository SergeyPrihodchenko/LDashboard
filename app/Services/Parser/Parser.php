<?php

namespace App\Services\Parser;

use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;
use Exception;

class Parser {

    public static function recordingCSV($data)
    {
        try {
            $file_path = __DIR__ . '/../../../tmp/direct_'.date('Y-m-d').'.csv';

            file_put_contents($file_path, $data);

            return $file_path;
        } catch (Exception $e) {
            return false;
        }
    }

    private static function fileRecordingDirect()
    {
        $file_path = __DIR__ . '/../../../tmp/direct_'.date('Y-m-d').'.csv';

        $yandex = new Yandex($_SERVER['AUTH_TOKEN_DIRECT']);

        $id = uniqid();

        if(!is_dir(__DIR__ . '/../../../tmp')) {

            mkdir(__DIR__ . '/../../../tmp');

        }

        if($result = $yandex->fetchDirect(WikaInvoice::CLIENT_LOGIN, $id)) {
            file_put_contents($file_path, $result);
        } else {
            return false;
        }

        return true;
    }

    public static function fileReader()
    {
        $file_path = __DIR__ . '/../../../tmp/direct_'.date('Y-m-d').'.csv';
        self::fileRecordingDirect();

        try {
            $stream = fopen($file_path, 'r');
        } catch (\Exception $e) {
            return false;
        }

        flock($stream, LOCK_SH);

        $data = [];

        while ($row = fgetcsv($stream, null, "\t")) {
            if(isset($row[1])) {
                if(!isset($data[$row[1]])) {
                    $data[$row[1]] = (float) $row[0];
                } else {
                    $data[$row[1]] += (float) $row[0];
                }
            }
        }

        unlink(__DIR__ . '/../../../tmp/direct_'.date('Y-m-d').'.csv');

        if(count($data)) {

            return $data;

        }

        return false;
    }

}