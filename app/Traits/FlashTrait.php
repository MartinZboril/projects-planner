<?php

namespace App\Traits;

trait FlashTrait
{
    public static function flash(string $message, string $type): void
    {
        session()->flash('message', $message);
        session()->flash('type', $type);
    }
}
