<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index()
    {
        $categories = MenuCategory::with('cafe')->get();
        return view('menu_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('menu_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cafe_id' => 'required',
            'name' => 'required'
        ]);

        MenuCategory::create($request->all());
        return redirect()->route('menu-categories.index');
    }

    public function show(MenuCategory $menuCategory)
    {
        return view('menu_categories.show', compact('menuCategory'));
    }

    public function edit(MenuCategory $menuCategory)
    {
        return view('menu_categories.edit', compact('menuCategory'));
    }

    public function update(Request $request, MenuCategory $menuCategory)
    {
        $menuCategory->update($request->all());
        return redirect()->route('menu-categories.index');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        $menuCategory->delete();
        return redirect()->route('menu-categories.index');
    }
}
