<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Challan;
use App\Models\CaseModel;
use App\Models\Prahari;


use App\Models\Payment;
use Illuminate\Validation\Rule;


class ChallanController extends Controller
{
    public function index()
    {
        $challans = Challan::with(['case.prahari', 'prahari'])->get();
        return view('admin.challans', compact('challans'));
    }

    public function create()
    {
        $cases = CaseModel::all();
        $praharis = Prahari::all();
        $caseAmounts = $this->caseAmounts($cases);

        return view('admin.challans', compact('cases', 'praharis', 'caseAmounts'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'prahari_id' => 'required|exists:praharis,id',

            'case_model_id' => 'required|exists:case_models,id',

            'status' => ['nullable', Rule::in(['Pending', 'Paid', 'Cancelled'])],

        ], [

            'prahari_id.exists' => 'Entered Prahari ID does not exist.',

            'case_model_id.exists' => 'Entered Case ID does not exist.'

        ]);
        $case = CaseModel::findOrFail($request->case_model_id);

        if (!$this->caseBelongsToPrahari($case, $request->prahari_id)) {
            return back()
                ->withErrors(['case_model_id' => 'Case ID for this Prahari does not exist.'])
                ->withInput();
        }

        $amount = $this->amountForCase($case);

        if ($amount === null) {
            return back()
                ->withErrors(['case_model_id' => 'Selected case does not have a valid challan type.'])
                ->withInput();
        }

        $challan = Challan::create([
            'prahari_id' => $request->prahari_id,
            'case_model_id' => $request->case_model_id,
            'amount' => $amount,
            'status' => $request->status ?? 'Pending',
        ]);

        // Auto-create a withdrawal request in payments (skip for cancelled challans)
        if (($request->status ?? 'Pending') !== 'Cancelled') {
            Payment::create([
                'prahari_id' => $request->prahari_id,
                'amount' => $amount,
                'bank_account' => null,
                'type' => 'debit',
                'status' => ($request->status ?? 'Pending') === 'Paid' ? 'Paid' : 'Pending',
            ]);
        }

        return redirect()->route('challans.index');
    }

    public function show(string $id)
    {
        $challan = Challan::findOrFail($id);
        return view('admin.challans', compact('challan'));
    }

    public function edit(string $id)
    {
        $challan = Challan::findOrFail($id);
        $cases = CaseModel::all();
        $praharis = Prahari::all();
        $caseAmounts = $this->caseAmounts($cases);

        return view('admin.challans', compact('challan', 'cases', 'praharis', 'caseAmounts'));
    }

    public function update(Request $request, string $id)
    {
        $challan = Challan::findOrFail($id);
        $request->validate([
            'prahari_id' => 'required|exists:praharis,id',
            'case_model_id' => 'required|exists:case_models,id',
            'status' => ['required', Rule::in(['Pending', 'Paid', 'Cancelled'])],
        ], [
            'prahari_id.exists' => 'Entered Prahari ID does not exist.',
            'case_model_id.exists' => 'Entered Case ID does not exist.',
        ]);

        $oldPrahariId = $challan->prahari_id;
        $oldAmount = $challan->amount;
        $case = CaseModel::findOrFail($request->case_model_id);

        if (!$this->caseBelongsToPrahari($case, $request->prahari_id)) {
            return back()
                ->withErrors(['case_model_id' => 'Case ID for this Prahari does not exist.'])
                ->withInput();
        }

        $amount = $this->amountForCase($case);

        if ($amount === null) {
            return back()
                ->withErrors(['case_model_id' => 'Selected case does not have a valid challan type.'])
                ->withInput();
        }

        $challan->update([
            'prahari_id' => $request->prahari_id,
            'case_model_id' => $request->case_model_id,
            'amount' => $amount,
            'status' => $request->status,
        ]);

        // Sync matching payment record
        // Find the matching payment (using old prahari_id and amount to locate it)
        $payment = Payment::where('prahari_id', $oldPrahariId)
            ->where('amount', $oldAmount)
            ->latest()
            ->first();

        if ($payment) {
            if ($request->status === 'Cancelled') {
                // Cancelled challans should not appear in payments
                $payment->delete();
            } else {
                $payment->update([
                    'prahari_id' => $request->prahari_id,
                    'amount' => $amount,
                    'status' => $request->status === 'Paid' ? 'Paid' : 'Pending',
                ]);
            }
        }

        return redirect()->route('challans.index');
    }

    public function destroy(string $id)
    {
        $challan = Challan::findOrFail($id);

        // Delete matching payment record
        Payment::where('prahari_id', $challan->prahari_id)
            ->where('amount', $challan->amount)
            ->latest()
            ->first()
                ?->delete();

        $challan->delete();
        return back();
    }

    private function amountForCase(CaseModel $case): ?int
    {
        $challanTypes = config('challan_types');

        return $challanTypes[$case->type] ?? null;
    }

    private function caseBelongsToPrahari(CaseModel $case, $prahariId): bool
    {
        return (int) $case->prahari_id === (int) $prahariId;
    }

    private function caseAmounts($cases): array
    {
        return $cases
            ->mapWithKeys(fn (CaseModel $case) => [$case->id => $this->amountForCase($case)])
            ->filter(fn ($amount) => $amount !== null)
            ->all();
    }
}
