<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $salom = 'user';
        return response()->json(['xat' => $salom]);
    }
}
