<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CaseModel;
use App\Models\Prahari;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cases = CaseModel::with(['prahari', 'challans'])->get();
        return view('admin.cases', compact('cases'));
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $praharis = Prahari::all();
        $challanTypes = config('challan_types');

        return view('admin.cases', compact('praharis', 'challanTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $challanTypes = config('challan_types');

         $request->validate([

            'prahari_id' => 'required|exists:praharis,id',
            'type' => ['required', Rule::in(array_keys($challanTypes))],

        ], [

            'prahari_id.exists' => 'Entered Prahari ID does not exist.',
            'type.in' => 'Please select a valid challan type.',

        ]);
        CaseModel::create([
            'prahari_id' => $request->prahari_id,
            // 'title' => $request->title,
            'type' => $request->type,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status ?? 'Open',
        ]);

        return redirect()->route('cases.index');
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $case = CaseModel::findOrFail($id);
        return view('admin.cases', compact('case'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $case = CaseModel::findOrFail($id);
        $praharis = Prahari::all();
        $challanTypes = config('challan_types');

        return view('admin.cases', compact('case', 'praharis', 'challanTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $case = CaseModel::findOrFail($id);
        $challanTypes = config('challan_types');

        $request->validate([
            'prahari_id' => 'required|exists:praharis,id',
            'type' => ['required', Rule::in(array_keys($challanTypes))],
        ], [
            'prahari_id.exists' => 'Entered Prahari ID does not exist.',
            'type.in' => 'Please select a valid challan type.',
        ]);

        $case->update([
            'prahari_id' => $request->prahari_id,
            // 'title' => $request->title,
            'type' => $request->type,
            'location' => $request->location,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('cases.index');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CaseModel::destroy($id);
        $this->resetCaseIdsIfEmpty();

        return back();
        //
    }

    private function resetCaseIdsIfEmpty(): void
    {
        if (CaseModel::exists()) {
            return;
        }

        $driver = DB::connection()->getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement('ALTER TABLE case_models AUTO_INCREMENT = 1');
        }
    }
}
