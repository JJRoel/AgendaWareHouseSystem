<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function getGroups()
    {
        $groups = Group::select('id', 'name', 'coler')->get();
        return response()->json($groups);
    }
}
