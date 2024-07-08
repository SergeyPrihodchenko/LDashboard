<?php

namespace App\Services\Parser;

use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;

class Parser {

    private static function fileRecordingDirect()
    {
        $yandex = new Yandex($_SERVER['AUTH_TOKEN_DIRECT']);

        $id = uniqid();

        if(!is_dir(__DIR__ . '/../../../tmp')) {

            mkdir(__DIR__ . '/../../../tmp');

        }

        if($yandex->fetchDirect(WikaInvoice::CLIENT_LOGIN, $id)) {
            file_put_contents(__DIR__ . '/../../../tmp/direct_'.date('Y-m-d').'.csv', $yandex->fetchDirect(WikaInvoice::CLIENT_LOGIN, $id));
        } else {
            return false;
        }

        return true;
    }

    public static function fileReader()
    {
        self::fileRecordingDirect();

        try {
            $stream = fopen(__DIR__ . '/../../../tmp/direct_'.date('Y-m-d').'.csv', 'r');
        } catch (\Exception $e) {
            return false;
        }
        flock($stream, LOCK_SH);

        fgetcsv($stream, null, "\t");
        fgetcsv($stream, null, "\t");

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