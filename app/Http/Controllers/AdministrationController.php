<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemId;

class AdministrationController extends Controller
{
    public function index()
    {
        $items = ItemId::with('group')->orderBy('groupid')->get();
        return view('administration.items.index', compact('items'));
    }

    public function updateStatus(Request $request, $id)
    {
        $item = ItemId::findOrFail($id);
        $item->status = $request->input('status');
        $item->save();

        return redirect()->back()->with('status', 'Item status updated successfully!');
    }

    public function updateName(Request $request, $id)
    {
        $item = ItemId::findOrFail($id);
        $item->name = $request->input('name');
        $item->save();

        return redirect()->back()->with('status', 'Item name updated successfully!');
    }

    public function calendar()
    {
        return view('administration.calendar');
    }
}



