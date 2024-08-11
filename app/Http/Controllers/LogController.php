<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $data['logs'] = Log::orderBy('id', 'desc')->get();

        return view('log.index', $data);
    }
}
