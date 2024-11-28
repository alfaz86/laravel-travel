<?php

namespace App\Traits;

use Midtrans\Config;

trait MidtransConfig
{
    public function setMidtransConfig()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
