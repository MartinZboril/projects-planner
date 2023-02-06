<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ClienFileController extends Controller
{
    /**
     * Display the files of client.
     */
    public function index(Client $client): View
    {
        return view('clients.files', ['client' => $client]);
    }
}
