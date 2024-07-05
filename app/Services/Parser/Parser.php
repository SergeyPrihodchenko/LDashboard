<?php

namespace App\Services\Parser;

use App\Models\WikaInvoice;
use App\Services\APIHook\Yandex;

class Parser {

    public static function fileRecording()
    {
        $yandex = new Yandex($_SERVER['AUTH_TOKEN_DIRECT']);

        $id = uniqid();

        file_put_contents(__DIR__ . '/../../../tmp/direct_'.date('Y-m-d h:i').'.csv', $yandex->fetchDirect(WikaInvoice::CLIENT_LOGIN, $id));
    }

    public static function fileReader()
    {
        
    }

}