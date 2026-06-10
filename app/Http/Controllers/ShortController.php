<?php

namespace App\Http\Controllers;

use App\Models\Short;
use App\Http\Requests\StoreShortRequest;
use App\Http\Requests\UpdateShortRequest;

class ShortController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShortRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Short $short)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Short $short)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShortRequest $request, Short $short)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Short $short)
    {
        //
    }
}
