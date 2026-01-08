<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenumController extends Controller
{
    public function index()
    {
        $items = Menu::with('category')->get();
        return view('menu_items.index', compact('items'));
    }

    public function create()
    {
        return view('menu_items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'name' => 'required',
            'price' => 'required'
        ]);

        Menu::create($request->all());
        return redirect()->route('menu-items.index');
    }

    public function show(Menu $Menu)
    {
        return view('menu_items.show', compact('Menu'));
    }

    public function edit(Menu $Menu)
    {
        return view('menu_items.edit', compact('Menu'));
    }

    public function update(Request $request, Menu $Menu)
    {
        $Menu->update($request->all());
        return redirect()->route('menu-items.index');
    }

    public function destroy(Menu $Menu)
    {
        $Menu->delete();
        return redirect()->route('menu-items.index');
    }
}
