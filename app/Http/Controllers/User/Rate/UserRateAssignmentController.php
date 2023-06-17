<?php

namespace App\Http\Controllers\User\Rate;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\User;
use Illuminate\View\View;

class UserRateAssignmentController extends Controller
{
    /**
     * Show the form for editing the rate.
     */
    public function __invoke(User $user): View
    {
        return view('users.rates.assign', ['user' => $user, 'rates' => Rate::all()]);
    }
}
