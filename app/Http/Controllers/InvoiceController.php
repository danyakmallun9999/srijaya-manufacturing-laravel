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
        // Add logging for debugging
        \Log::info('Invoice generation started', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'total_price' => $order->total_price,
            'product_type' => $order->product_type,
            'incomes_count' => $order->incomes->count(),
            'total_incomes' => $order->incomes->sum('amount')
        ]);

        // Check if order has incomes
        if ($order->incomes->count() === 0) {
            $currentTab = $request->input('current_tab', 'invoice');
            return redirect()->route('orders.show', $order)
                ->with('error', 'Invoice hanya dapat dibuat setelah ada input pemasukan (DP/Cicilan/Lunas).')
                ->with('active_tab', $currentTab);
        }

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
            
            // Get the latest income to determine payment status
            $latestIncome = $order->incomes()->latest('date')->first();
            
            if ($order->product_type === 'custom') {
                // For custom products: use income type for payment status
                if ($latestIncome) {
                    switch ($latestIncome->type) {
                        case 'Lunas':
                            $paymentStatus = 'Paid';
                            break;
                        case 'Cicilan':
                            $paymentStatus = 'Partial';
                            break;
                        case 'DP':
                        default:
                            $paymentStatus = 'Partial';
                            break;
                    }
                } else {
                    $paymentStatus = 'Unpaid';
                }
                $remainingAmount = 0; // Don't show remaining amount for custom products
            } else {
                // For fixed products: calculate based on total order value and income type
                $totalOrderValue = $order->total_price * $order->quantity;
                $remainingAmount = $totalOrderValue - $totalPaid;
                
                if ($latestIncome && $latestIncome->type === 'Lunas') {
                    $paymentStatus = 'Paid';
                } elseif ($totalPaid >= $totalOrderValue) {
                    $paymentStatus = 'Paid';
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
                'company_name' => 'CV. Srijaya Indo Furniture',
                'company_address' => 'Office : Jl. Lembah II Rt 01 Rw 02 Kelurahan Sukodono, Kec. Tahunan Kab. Jepara.',
                'company_phone' => '+6282230020606',
                'company_email' => 'cs.srijayafurniture@gmail.com',
                'company_website' => 'https://indosrijayafurniture.com',
                
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
                'seller_name' => 'CV. Srijaya Indo Furniture',
                'terms_conditions' => 'Payment must be made before the due date specified on the invoice. Items that have been ordered and produced cannot be cancelled or returned. Specification changes after production has started will incur additional costs. Production time is calculated after payment is received and final specifications are approved. All disputes will be resolved through mutual agreement or arbitration.',
                'notes_customer' => $request->input('notes_customer'),
                
                // Payment tracking
                'paid_amount' => $totalPaid,
                'remaining_amount' => $remainingAmount,
                'payment_date' => $totalPaid > 0 ? now() : null,
                'payment_status' => $paymentStatus,
                
                // Notes with payment information for custom products
                'notes' => $request->input('notes') . ($hppBreakdown ? "\n\nInformasi Pembayaran:\n" . 
                    ($latestIncome ? "Jenis Pembayaran: {$latestIncome->type}\n" : "") .
                    "Total yang sudah dibayar: Rp " . number_format($totalPaid, 0, ',', '.') . "\n" .
                    "Status: " . $paymentStatus : ''),
            ];

            $invoice = $order->invoices()->create($invoiceData);

            DB::commit();

            $currentTab = $request->input('current_tab', 'invoice');
            $successMessage = "Invoice {$invoiceNumber} berhasil dibuat.";
                
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

        $oldStatus = $invoice->status;
        $newStatus = $request->status;
        
        $invoice->update(['status' => $newStatus]);

        // Note: Order status should only be changed manually via Info Order tab
        // Invoice status changes should not affect order progress

        // Custom success message based on status change
        $successMessage = 'Status invoice berhasil diupdate.';
        if ($newStatus === 'Paid' && $oldStatus !== 'Paid') {
            $successMessage = 'Invoice berhasil ditandai sebagai LUNAS! ✓';
        } elseif ($newStatus === 'Sent' && $oldStatus !== 'Sent') {
            $successMessage = 'Invoice berhasil dikirim! 📧';
        }

        return redirect()->route('invoices.show', $invoice)
            ->with('success', $successMessage);
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
        
        // Prepare logo data for PDF
        $logoBase64 = $this->getLogoAsBase64($invoice);
        
        // Generate PDF using DomPDF with logo data and optimized margins
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice', 'logoBase64'))
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('dpi', 150)
            ->setOption('defaultFont', 'sans-serif')
            ->setPaper('A4', 'portrait')
            ->setOption('margin-top', 3)
            ->setOption('margin-bottom', 3)
            ->setOption('margin-left', 8)
            ->setOption('margin-right', 8);
        
        // Return PDF for download
        return $pdf->download($invoice->invoice_number . '.pdf');
    }

    /**
     * Get logo as base64 encoded string
     */
    private function getLogoAsBase64($invoice)
    {
        // Priority 1: Check if invoice has company_logo from storage
        if (isset($invoice->company_logo) && $invoice->company_logo) {
            $logoPath = storage_path('app/public/' . $invoice->company_logo);
            if (file_exists($logoPath)) {
                return $this->encodeImageToBase64($logoPath);
            }
        }
        
        // Priority 2: Check default logo in public/images
        $defaultLogoPath = public_path('images/idefu.png');
        if (file_exists($defaultLogoPath)) {
            return $this->encodeImageToBase64($defaultLogoPath);
        }
        
        // Priority 3: Check alternative logo formats
        $logoFormats = ['idefu.jpg', 'idefu.jpeg', 'logo.png', 'logo.jpg'];
        foreach ($logoFormats as $logoFile) {
            $logoPath = public_path('images/' . $logoFile);
            if (file_exists($logoPath)) {
                return $this->encodeImageToBase64($logoPath);
            }
        }
        
        // Return null if no logo found
        return null;
    }

    /**
     * Encode image file to base64
     */
    private function encodeImageToBase64($imagePath)
    {
        try {
            $imageData = file_get_contents($imagePath);
            $imageType = pathinfo($imagePath, PATHINFO_EXTENSION);
            
            // Handle different image formats
            $mimeType = match(strtolower($imageType)) {
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'gif' => 'image/gif',
                'svg' => 'image/svg+xml',
                default => 'image/png'
            };
            
            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        } catch (\Exception $e) {
            \Log::error('Error encoding logo to base64: ' . $e->getMessage());
            return null;
        }
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