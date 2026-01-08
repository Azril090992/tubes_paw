<?php

namespace App\Http\Controllers;

use App\Models\CafeUser;
use App\Http\Requests\StoreCafeUserRequest;
use App\Http\Requests\UpdateCafeUserRequest;

class CafeUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCafeUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CafeUser $cafeUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CafeUser $cafeUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCafeUserRequest $request, CafeUser $cafeUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CafeUser $cafeUser)
    {
        //
    }
}
