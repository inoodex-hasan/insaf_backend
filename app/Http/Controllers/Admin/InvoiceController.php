<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Student;
use App\Models\University;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*accountant');
    }

    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        $invoices = Invoice::with(['student', 'university'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('admin.accounts.invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $students = Student::orderBy('first_name')->get();
        $universities = University::orderBy('name')->get();
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code')->get();

        return view('admin.accounts.invoices.create', compact('students', 'universities', 'accounts'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'university_id' => 'nullable|exists:universities,id',
            'date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:date',
            'invoice_number' => 'nullable|string|max:50|unique:invoices,invoice_number',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = collect($request->items)->sum(fn($i) => (float) $i['quantity'] * (float) $i['unit_price']);

            $invoice = Invoice::create([
                'student_id' => $request->student_id,
                'university_id' => $request->university_id,
                'invoice_number' => $request->invoice_number ?? 'INV-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2))),
                'date' => $request->date,
                'due_date' => $request->due_date,
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $subtotal = (float) $item['quantity'] * (float) $item['unit_price'];
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'chart_of_account_id' => $item['chart_of_account_id'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                    'tax_amount' => 0.00,
                    'total' => $subtotal,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.invoices.index')->with('success', 'Student invoice created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['msg' => 'Invoice creation failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['items.chartOfAccount', 'student', 'university']);
        return view('admin.accounts.invoices.show', compact('invoice'));
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Prevent deletion if payments are linked
        if ($invoice->payments()->exists()) {
            return redirect()->back()->withErrors(['msg' => 'Invoice cannot be deleted because it has linked payments.']);
        }

        $invoice->delete();
        return redirect()->back()->with('success', 'Invoice deleted successfully.');
    }
}
