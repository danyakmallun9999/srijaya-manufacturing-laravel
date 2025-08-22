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
    public function generate(Order $order)
    {
        // Check if order already has invoice
        if ($order->invoices()->exists()) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Order ini sudah memiliki invoice.');
        }

        // Check if order has total_price
        if (!$order->total_price) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Harga jual belum ditentukan. Silakan update order terlebih dahulu.');
        }

        DB::beginTransaction();
        try {
            // Generate invoice number
            $today = now()->format('Ymd');
            $latestInvoice = Invoice::where('invoice_number', 'like', "INV-{$today}-%")->latest('id')->first();
            $nextNumber = $latestInvoice ? (int)substr($latestInvoice->invoice_number, -4) + 1 : 1;
            $invoiceNumber = "INV-{$today}-" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Calculate amounts
            $subtotal = $order->total_price * $order->quantity;
            $taxAmount = 0; // Default no tax
            $totalAmount = $subtotal + $taxAmount;

            // Create invoice
            $invoice = $order->invoices()->create([
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => now()->addDays(30), // Default 30 days
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'status' => 'Draft',
            ]);

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', "Invoice {$invoiceNumber} berhasil dibuat.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('orders.show', $order)
                ->with('error', 'Gagal membuat invoice: ' . $e->getMessage());
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
            $invoice->order->update(['status' => 'Lunas']);
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Status invoice berhasil diupdate.');
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
