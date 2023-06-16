<?php

namespace App\Http\Controllers\Client\File;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\View\View;

class ClienFileController extends Controller
{
    /**
     * Display the files of client.
     */
    public function index(Client $client): View
    {
        return view('clients.files.index', ['client' => $client]);
    }
}
