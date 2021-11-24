<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        $msg = "Hello auditor";

        return view('welcome', ['msg' => $msg]);
    }

    public function create()
    {
        return view('audit.create');
    }
}
