<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataTableController extends Controller
{
    public function index()
    {
        return view('datatable.index');
    }
}



