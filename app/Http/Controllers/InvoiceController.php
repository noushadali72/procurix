<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('items.product', 'items.unit')
            ->latest()
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::orderBy('name')->get();

        return view('invoices.create', compact('products', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => [
                'required',
                'integer',
                'unique:invoices,invoice_number',
            ],

            'type' => [
                'required',
                Rule::in(['raw_material', 'products']),
            ],

            'party_name' => [
                'required',
                'string',
                'max:255',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['paid', 'unpaid', 'pending']),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.description' => [
                'nullable',
                'string',
            ],

            'items.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.unit_id' => [
                'required',
                'exists:units,id',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $subtotal = collect($validated['items'])
                ->sum(function ($item) {
                    return $item['qty'] * $item['price'];
                });

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;

            $total = $subtotal + $tax - $discount;

            $invoice = Invoice::create([
                'invoice_number' => $validated['invoice_number'],
                'type' => $validated['type'],
                'party_name' => $validated['party_name'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {

                $itemTotal = $item['qty'] * $item['price'];

                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'qty' => $item['qty'],
                    'unit_id' => $item['unit_id'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ]);
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');

        $products = Product::where('is_active', true)
            ->orderBy('name')
            ->get();

        $units = Unit::orderBy('name')->get();

        return view(
            'invoices.edit',
            compact('invoice', 'products', 'units')
        );
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_number' => [
                'required',
                'integer',
                Rule::unique('invoices', 'invoice_number')
                    ->ignore($invoice->id),
            ],

            'type' => [
                'required',
                Rule::in(['raw_material', 'products']),
            ],

            'party_name' => [
                'required',
                'string',
                'max:255',
            ],

            'invoice_date' => [
                'required',
                'date',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['paid', 'unpaid', 'pending']),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.description' => [
                'nullable',
                'string',
            ],

            'items.*.qty' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.unit_id' => [
                'required',
                'exists:units,id',
            ],

            'items.*.price' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::transaction(function () use ($validated, $invoice) {

            $subtotal = collect($validated['items'])
                ->sum(function ($item) {
                    return $item['qty'] * $item['price'];
                });

            $tax = $validated['tax'] ?? 0;
            $discount = $validated['discount'] ?? 0;

            $total = $subtotal + $tax - $discount;

            $invoice->update([
                'invoice_number' => $validated['invoice_number'],
                'type' => $validated['type'],
                'party_name' => $validated['party_name'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => $validated['status'],
                'description' => $validated['description'] ?? null,
            ]);

            $invoice->items()->delete();

            foreach ($validated['items'] as $item) {

                $itemTotal = $item['qty'] * $item['price'];

                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'description' => $item['description'] ?? null,
                    'qty' => $item['qty'],
                    'unit_id' => $item['unit_id'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ]);
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}