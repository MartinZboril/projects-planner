<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class FlashService
{
    /**
     * Flash messages to user.
     */
    public function flash(string $message, string $type): void
    {
        Session::flash('message', $message);
        Session::flash('type', $type);
    }
}