<?php

namespace App\Http\Controllers\User\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Challan;
use App\Models\CaseModel;

class ChallanController extends Controller
{
    public function index()
    {
        $challans = Challan::with('case')->get();

        return response()->json([

            'status' => true,
            'data' => $challans

        ]);
    }

    // public function store(Request $request)
    // {
    //     $challan = Challan::create([

    //         'prahari_id' => $request->prahari_id,
    //         'case_model_id' => $request->case_model_id,
    //         'amount' => $request->amount,
    //         'status' => 'Pending'

    //     ]);

    //     return response()->json([

    //         'status' => true,
    //         'message' => 'Challan created successfully',
    //         'data' => $challan

    //     ]);
    // }

//     public function updateStatus(Request $request, $id)
//     {
//         $challan = Challan::where('prahari_id', Auth::id())
//             ->find($id);

//         if (!$challan) {

//             return response()->json([

//                 'status' => false,
//                 'message' => 'Challan not found'

//             ]);
//         }

//         $challan->update([

//             'status' => $request->status

//         ]);

//         return response()->json([

//             'status' => true,
//             'message' => 'Challan status updated successfully'

//         ]);
//     }
 }
