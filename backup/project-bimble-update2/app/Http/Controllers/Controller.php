<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;

abstract class Controller
{
    public function checkServerTime()
{
    $serverTime = Carbon::now();
    return "Waktu server saat ini (zona waktu: Asia/Jakarta) adalah: " . $serverTime->toDateTimeString() . " (" . $serverTime->format('l, j F Y H:i:s') . ")";
}
}
