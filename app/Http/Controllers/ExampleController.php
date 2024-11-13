<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function index()
    {
        $name = "AHMAD";
        $gender = "MALE";

        return view('welcome', compact('name','gender'));

    }

    public function index2()
    {

        return "text";

    }
}
