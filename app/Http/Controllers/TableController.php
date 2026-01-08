<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::with('cafe')->get();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cafe_id' => 'required',
            'table_number' => 'required',
            'capacity' => 'required'
        ]);

        Table::create($request->all());
        return redirect()->route('tables.index');
    }

    public function show(Table $table)
    {
        return view('tables.show', compact('table'));
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $table->update($request->all());
        return redirect()->route('tables.index');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index');
    }
}
