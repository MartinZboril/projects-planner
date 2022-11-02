<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class FlashService
{
    public function flash(string $message, string $type): void
    {
        Session::flash('message', $message);
        Session::flash('type', $type);
    }
}