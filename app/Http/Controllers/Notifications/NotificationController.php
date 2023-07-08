<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        return view('notifications.index', [
            'notifications' => auth()->user()->unreadNotifications()->orderBy('created_at','desc')->get()
        ]);
    }
}
