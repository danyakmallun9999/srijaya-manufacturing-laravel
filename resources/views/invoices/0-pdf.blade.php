<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            text-align: right;
            margin-bottom: 30px;
        }
        .total-row {
            margin-bottom: 8px;
        }
        .total-label {
            font-weight: bold;
            margin-right: 20px;
        }
        .total-amount {
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">SIM MAKE TO ORDER</div>
        <div class="invoice-title">INVOICE</div>
    </div>

    <div class="invoice-info">
        <div class="info-row">
            <span class="info-label">Invoice #:</span>
            <span>{{ $invoice->invoice_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Invoice:</span>
            <span>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jatuh Tempo:</span>
            <span>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span>{{ $invoice->status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Customer:</span>
            <span>{{ $invoice->order->customer->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Produk:</span>
            <span>{{ $invoice->order->product_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jumlah:</span>
            <span>{{ $invoice->order->quantity }} pcs</span>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jumlah</th>
                <th>Harga Satuan</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoice->order->product_name }}</td>
                <td>{{ $invoice->order->quantity }} pcs</td>
                <td>Rp {{ number_format($invoice->order->total_price ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Subtotal:</span>
            <span class="total-amount">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Pajak:</span>
            <span class="total-amount">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span class="total-label">Total:</span>
            <span class="total-amount">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="footer">
        <p>Terima kasih telah mempercayakan bisnis Anda kepada kami.</p>
        <p>Pembayaran dapat dilakukan melalui rekening bank atau metode pembayaran lainnya.</p>
    </div>
</body>
</html> 