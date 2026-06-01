<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\CaseModel;

class CaseController extends Controller
{
    public function index()
    {
        $cases = CaseModel::with('prahari')->get();

        return response()->json([

            'status' => true,
            'data' => $cases

        ]);
    }

    public function store(Request $request)
    {
        $case = CaseModel::create([

            'prahari_id' => $request->prahari_id,
            'type' => $request->type,
            'location' => $request->location,
            'description' => $request->description,
            'status' => 'Open'

        ]);

        return response()->json([

            'status' => true,
            'message' => 'Case created successfully',
            'data' => $case

        ]);
    }

    public function show($id)
    {
        // $case = CaseModel::where('prahari_id', Auth::id())
        //     ->find($id);
        $case = CaseModel::find($id);

        if (!$case) {

            return response()->json([

                'status' => false,
                'message' => 'Case not found'

            ]);
        }

        return response()->json([

            'status' => true,
            'data' => $case

        ]);
    }

    public function update(Request $request, $id)
    {
        $case = CaseModel::find($id);
        $case->update($request->all());

        if (!$case) {

            return response()->json([

                'status' => false,
                'message' => 'Case not found'

            ]);
        }

        $case->update([

            'type' => $request->type,
            'location' => $request->location,
            'description' => $request->description

        ]);

        return response()->json([

            'status' => true,
            'message' => 'Case updated successfully'

        ]);
    }
}
