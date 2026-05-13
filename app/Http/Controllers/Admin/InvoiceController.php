<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\CitaPublica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('patient')->orderByDesc('created_at');

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%$search%")
                  ->orWhere('patient_name', 'like', "%$search%");
            });
        }

        $invoices     = $query->paginate(15)->withQueryString();
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $unpaidCount  = Invoice::where('status', 'unpaid')->count();

        return view('admin.invoices.index', compact('invoices', 'totalRevenue', 'unpaidCount'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('id')->get();
        $selectedPatient = $request->patient_id ? Patient::find($request->patient_id) : null;
        return view('admin.invoices.create', compact('patients', 'selectedPatient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'items'        => 'required|array|min:1',
            'items.*.desc' => 'required|string',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.qty'  => 'required|integer|min:1',
            'tax_percent'  => 'nullable|numeric|min:0|max:100',
            'discount'     => 'nullable|numeric|min:0',
            'due_date'     => 'nullable|date',
            'notes'        => 'nullable|string|max:500',
        ]);

        $patient  = Patient::find($request->patient_id);
        $items    = [];
        $subtotal = 0;

        foreach ($request->items as $item) {
            $amount    = $item['qty'] * $item['rate'];
            $subtotal += $amount;
            $items[]   = [
                'description' => $item['desc'],
                'quantity'    => (int) $item['qty'],
                'rate'        => (float) $item['rate'],
                'amount'      => $amount,
            ];
        }

        $taxPercent = (float) ($request->tax_percent ?? 0);
        $discount   = (float) ($request->discount ?? 0);
        $taxAmount  = round($subtotal * $taxPercent / 100, 2);
        $total      = max(0, $subtotal + $taxAmount - $discount);

        Invoice::create([
            'invoice_number' => Invoice::generateNumber(),
            'patient_id'     => $patient->id,
            'patient_name'   => $patient->full_name,
            'patient_email'  => $patient->email,
            'patient_phone'  => $patient->phone,
            'items'          => $items,
            'subtotal'       => $subtotal,
            'tax_percent'    => $taxPercent,
            'tax_amount'     => $taxAmount,
            'discount'       => $discount,
            'total'          => $total,
            'paid_amount'    => 0,
            'status'         => 'unpaid',
            'due_date'       => $request->due_date,
            'notes'          => $request->notes,
            'created_by'     => Auth::id(),
        ]);

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('patient', 'createdBy');
        return view('admin.invoices.show', compact('invoice'));
    }

    public function markPaid(Request $request, Invoice $invoice)
    {
        $request->validate(['paid_amount' => 'required|numeric|min:0']);

        $paid  = (float) $request->paid_amount;
        $status = $paid >= $invoice->total ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid');

        $invoice->update([
            'paid_amount' => $paid,
            'status'      => $status,
            'paid_at'     => $status === 'paid' ? now() : null,
        ]);

        return back()->with('success', 'Payment updated.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted.');
    }
}
