<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortRequest;
use App\Http\Requests\UpdateShortRequest;
use App\Models\Short;
use App\Services\Decode;

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
        $short = Short::create(
            $request->validated()
        );

        return response()->json([
            'short_url' => $short->short_url,
        ], 201);
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

    public function redirect(string $code)
    {
        $id = (new Decode)->decode($code);
        $short = Short::findOrFail($id);

        return redirect()->away($short->url_origin);
    }
}
