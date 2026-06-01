<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Prahari;
use App\Models\Challan;
class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $payments = Payment::with('prahari')->orderBy('created_at', 'desc')->get();
         $pendingPayments = Payment::with('prahari')->where('status', 'Pending')->orderBy('created_at', 'desc')->get();
        return view('admin.payments', compact('payments', 'pendingPayments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          $praharis = Prahari::all();
                return view('admin.payments', compact('praharis'));
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          Payment::create([
            'prahari_id' => $request->prahari_id,
            'amount' => $request->amount,
            'bank_account' => $request->bank_account,
            'type' => $request->type, // credit / debit
            'status' => $request->status ?? 'Pending',
        ]);

        return redirect()->route('payments.index');
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $payment = Payment::findOrFail($id);
        return view('admin.payments', compact('payment'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
          $payment = Payment::findOrFail($id);
        $praharis = Prahari::all();

        return view('admin.payments', compact('payment', 'praharis'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $payment = Payment::findOrFail($id);

        $payment->update([
            'prahari_id' => $request->prahari_id,
            'amount' => $request->amount,
            'bank_account' => $request->bank_account,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->route('payments.index');
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        Payment::destroy($id);
        return back();
        //
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'Approved']);

        // Update matching challan status to Paid
        Challan::where('prahari_id', $payment->prahari_id)
            ->where('amount', $payment->amount)
            ->where('status', 'Pending')
            ->latest()
            ->first()
            ?->update(['status' => 'Paid']);

        return redirect()->route('payments.index');
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update(['status' => 'Rejected']);

        // Update matching challan status to Cancelled
        Challan::where('prahari_id', $payment->prahari_id)
            ->where('amount', $payment->amount)
            ->where('status', 'Pending')
            ->latest()
            ->first()
            ?->update(['status' => 'Cancelled']);

        return redirect()->route('payments.index');
    }
}
