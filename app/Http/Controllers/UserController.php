<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Displays view of users
     */
    public function index()
    {
        $users = User::all();

        return view('users.index', ['users' => $users]);
    }
}
