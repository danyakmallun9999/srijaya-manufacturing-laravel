<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate invoice for an order
     */
    public function generate(Order $order, Request $request)
    {
        // Check if order has total_price or is custom product
        if (!$order->total_price && !$order->isCustomProduct()) {
            $currentTab = $request->input('current_tab', 'invoice');
            return redirect()->route('orders.show', $order)
                ->with('error', 'Harga jual belum ditentukan. Silakan update order terlebih dahulu.')
                ->with('active_tab', $currentTab);
        }

        DB::beginTransaction();
        try {
            // Generate invoice number
            $today = now()->format('Ymd');
            $latestInvoice = Invoice::where('invoice_number', 'like', "INV-{$today}-%")->latest('id')->first();
            $nextNumber = $latestInvoice ? (int)substr($latestInvoice->invoice_number, -4) + 1 : 1;
            $invoiceNumber = "INV-{$today}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Calculate amounts based on product type
            if ($order->product_type === 'custom') {
                // For custom products: calculate from production costs + margin
                $totalPembelian = $order->purchases->sum(function ($purchase) {
                    return $purchase->quantity * $purchase->price;
                });
                $totalBiayaProduksi = $order->productionCosts->sum('amount');
                $totalHPP = $totalPembelian + $totalBiayaProduksi;
                
                // Use default margin percentage (30%)
                $marginPercentage = 30; // Default margin
                $marginAmount = $totalHPP * ($marginPercentage / 100);
                
                $subtotal = $totalHPP + $marginAmount;
                
                // Store HPP breakdown for invoice
                $hppBreakdown = [
                    'total_pembelian' => $totalPembelian,
                    'total_biaya_produksi' => $totalBiayaProduksi,
                    'total_hpp' => $totalHPP,
                    'margin_percentage' => $marginPercentage,
                    'margin_amount' => $marginAmount,
                ];
            } else {
                // For fixed products: use existing total_price
                $subtotal = $order->total_price * $order->quantity;
                $hppBreakdown = null;
            }

            $taxAmount = 0; // Default no tax
            $shippingCost = (float) str_replace(['.', ','], ['', ''], $request->input('shipping_cost', 0));
            $totalAmount = $subtotal + $taxAmount + $shippingCost;

            // Calculate payment status based on incomes
            $totalPaid = $order->incomes->sum('amount');
            
            if ($order->product_type === 'custom') {
                // For custom products: don't calculate remaining amount since price is not determined
                $paymentStatus = $totalPaid > 0 ? 'Partial' : 'Unpaid';
                $remainingAmount = 0; // Don't show remaining amount for custom products
            } else {
                // For fixed products: calculate based on total order value
                $totalOrderValue = $order->total_price * $order->quantity;
                $remainingAmount = $totalOrderValue - $totalPaid;
                
                if ($totalPaid >= $totalOrderValue) {
                    $paymentStatus = 'Paid';
                    // Update order status to 'Closed' if fully paid
                    if ($order->status !== 'Closed') {
                        $order->update(['status' => 'Closed']);
                    }
                } elseif ($totalPaid > 0) {
                    $paymentStatus = 'Partial';
                } else {
                    $paymentStatus = 'Unpaid';
                }
            }

            // Ensure due_days is integer
            $dueDays = (int) $request->input('due_days', 30);

            // Create invoice with flexible data
            $invoiceData = [
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => now()->addDays($dueDays),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'Unpaid',
                'notes' => $request->input('notes'),
                
                // Product and company information (default values)
                'product_image' => $order->image, // Use order image as product image
                'company_name' => 'Idefu Furniture',
                'company_address' => 'Office : Jl. Hugeng Imam Santoso Km.09 NGabul Tahunan Jepara, Central Java Indonesia. Workshop : Bawu Rt 10/02 Batealit Jepara.',
                'company_phone' => '+6285741555089',
                'company_email' => 'idesign@idefu.co.id',
                'company_website' => 'idefu.co.id',
                
                // Shipping information
                'shipping_address' => $request->input('shipping_address', $order->customer->address ?? ''),
                'shipping_cost' => $shippingCost,
                'shipping_method' => $request->input('shipping_method'),
                
                // Payment information
                'payment_method' => $request->input('payment_method'),
                'bank_name' => $request->input('bank_name', 'BCA'),
                'account_number' => $request->input('account_number'),
                'account_holder' => $request->input('account_holder'),
                
                // Invoice customization (default values)
                'po_number' => 'PO-' . date('Y') . '-' . str_pad($order->id, 3, '0', STR_PAD_LEFT),
                'seller_name' => 'Manager SIM Make To Order',
                'terms_conditions' => 'Pembayaran harus dilakukan sebelum tanggal jatuh tempo yang tertera pada invoice. Barang yang sudah dipesan dan diproduksi tidak dapat dibatalkan atau dikembalikan. Perubahan spesifikasi setelah produksi dimulai akan dikenakan biaya tambahan. Waktu pengerjaan dihitung setelah pembayaran diterima dan spesifikasi final disetujui. Segala perselisihan akan diselesaikan secara musyawarah atau melalui arbitrase.',
                'notes_customer' => $request->input('notes_customer'),
                
                // Payment tracking
                'paid_amount' => $totalPaid,
                'remaining_amount' => $remainingAmount,
                'payment_date' => $totalPaid > 0 ? now() : null,
                'payment_status' => $paymentStatus,
                
                // Payment information for custom products
                'notes' => $request->input('notes') . ($hppBreakdown ? "\n\nInformasi Pembayaran:\nTotal DP yang sudah dibayar: Rp " . number_format($totalPaid, 0, ',', '.') . "\nStatus: " . $paymentStatus : ''),
            ];

            $invoice = $order->invoices()->create($invoiceData);

            DB::commit();

            $currentTab = $request->input('current_tab', 'invoice');
            $successMessage = $order->isCustomProduct() 
                ? "Invoice {$invoiceNumber} berhasil dibuat dengan margin {$marginPercentage}%."
                : "Invoice {$invoiceNumber} berhasil dibuat.";
                
            return redirect()->route('orders.show', $order)
                ->with('success', $successMessage)
                ->with('active_tab', $currentTab);

        } catch (\Exception $e) {
            DB::rollBack();
            $currentTab = $request->input('current_tab', 'invoice');
            return redirect()->route('orders.show', $order)
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage())
                ->with('active_tab', $currentTab);
        }
    }

    /**
     * Show invoice details
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['order.customer']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:Draft,Sent,Paid,Overdue,Cancelled'
        ]);

        $invoice->update(['status' => $request->status]);

        // If invoice is paid, update order status
        if ($request->status === 'Paid') {
            $invoice->order->update(['status' => 'Closed']);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Status invoice berhasil diupdate.');
    }

    /**
     * Update invoice details
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_website' => 'nullable|string|max:255',
            'shipping_address' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_method' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_holder' => 'nullable|string|max:255',
            'po_number' => 'nullable|string|max:255',
            'seller_name' => 'nullable|string|max:255',
            'terms_conditions' => 'nullable|string',
            'notes_customer' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        // Recalculate total if shipping cost changed
        if (isset($validated['shipping_cost'])) {
            $validated['shipping_cost'] = (float) str_replace(['.', ','], ['', ''], $validated['shipping_cost']);
            $validated['total_amount'] = $invoice->subtotal + $invoice->tax_amount + $validated['shipping_cost'];
            $validated['remaining_amount'] = $validated['total_amount'] - $invoice->paid_amount;
        }

        $invoice->update($validated);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil diupdate.');
    }

    /**
     * Revise invoice
     */
    public function revise(Request $request, Invoice $invoice)
    {
        if (!$invoice->canBeRevised()) {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Invoice tidak dapat direvisi.');
        }

        $request->validate([
            'revision_reason' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        // Ensure numeric values are properly cast
        $newSubtotal = (float) str_replace(['.', ','], ['', ''], $request->subtotal);
        $newShippingCost = $request->shipping_cost ? (float) str_replace(['.', ','], ['', ''], $request->shipping_cost) : 0;
        
        $newTotal = $newSubtotal + $invoice->tax_amount + $newShippingCost;
        $newRemaining = $newTotal - $invoice->paid_amount;

        $invoice->revise($request->revision_reason, [
            'subtotal' => $newSubtotal,
            'shipping_cost' => $newShippingCost,
            'total_amount' => $newTotal,
            'remaining_amount' => $newRemaining,
            'payment_status' => $newRemaining <= 0 ? 'Paid' : ($invoice->paid_amount > 0 ? 'Partial' : 'Unpaid'),
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil direvisi.');
    }

    /**
     * Download invoice as PDF
     */
    public function download(Invoice $invoice)
    {
        $invoice->load(['order.customer']);
        
        // Generate PDF using DomPDF
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        
        // Return PDF for download
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Send invoice via email
     */
    public function send(Invoice $invoice)
    {
        // TODO: Implement email sending
        $invoice->update(['status' => 'Sent']);
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dikirim.');
    }
}
