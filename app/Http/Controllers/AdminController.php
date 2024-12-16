<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $salom = 'salom';
        return response()->json(['xat' => $salom]);
    }
}
