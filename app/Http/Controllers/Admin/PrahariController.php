<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prahari;

class PrahariController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data = Prahari::all();
        return view('admin.praharis', compact('data'));
//

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            return view('admin.praharis');
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         Prahari::create($request->all());
        return redirect()->route('praharis.index');
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prahari = Prahari::findOrFail($id);
        return view('admin.praharis', compact('prahari'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $prahari = Prahari::find($id);
        return view('admin.praharis', compact('prahari'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Prahari::find($id)->update($request->all());
        return redirect()->route('praharis.index');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Prahari::destroy($id);
        return back();
        //
    }
}
