<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubAdmin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SubAdminController extends Controller
{
    public function index()
    {
        // $subadmins = SubAdmin::all();
        $subadmins = User::where('role', 'subadmin')->get();

        return view('admin.subadmins', compact('subadmins'));
    }

    public function create()
    {
        return view('admin.subadmins');
    }

    public function store(Request $request)
    {
        User::create([

            'name' => $request->name,
            'email' => $request->email,

            'password' => Hash::make($request->password),
            'role' => 'subadmin'

            // 'is_active' => $request->is_active ?? 1
        ]);

        return redirect()->route('subadmins.index');
    }

    public function edit(string $id)
    {
        $subadmin = SubAdmin::findOrFail($id);

        return view('admin.subadmins', compact('subadmin'));
    }

    public function update(Request $request, string $id)
    {
        $subadmin = SubAdmin::findOrFail($id);

        $data = [

            'name' => $request->name,
            'email' => $request->email,
            // 'is_active' => $request->is_active
        ];

        // only update password if entered
        if ($request->password) {

            $data['password'] = Hash::make($request->password);
        }

        $subadmin->update($data);

        return redirect()->route('subadmins.index');
    }

    public function destroy( string $id)
    {
        SubAdmin::destroy($id);

        return back();
    }
}