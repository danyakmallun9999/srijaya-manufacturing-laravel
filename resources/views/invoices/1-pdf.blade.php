<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            font-size: 12px;
            line-height: 1.4;
            background: #fff;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }

        .company-info {
            flex: 1;
        }

        .logo-placeholder {
            width: 80px;
            height: 80px;
            background: #2563eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
        }

        .company-address {
            color: #666;
            line-height: 1.5;
        }

        .invoice-header {
            text-align: right;
            flex: 1;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 14px;
            color: #666;
        }

        .billing-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 40px;
        }

        .bill-to,
        .ship-to {
            flex: 1;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .address-info {
            line-height: 1.5;
            color: #333;
        }

        .invoice-details {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
        }

        .detail-label {
            font-weight: bold;
            color: #374151;
        }

        .detail-value {
            color: #1f2937;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .items-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .totals-table {
            min-width: 300px;
        }

        .totals-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .totals-table tr:last-child {
            border-bottom: 2px solid #2563eb;
            font-weight: bold;
            font-size: 14px;
        }

        .totals-table td {
            padding: 8px 15px;
        }

        .total-label {
            text-align: right;
            color: #374151;
        }

        .total-amount {
            text-align: right;
            font-weight: 600;
        }

        .payment-section {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .payment-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .payment-method {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }

        .method-name {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }

        .method-details {
            font-size: 11px;
            color: #6b7280;
        }

        .terms-section {
            margin-bottom: 30px;
        }

        .terms-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .terms-content {
            font-size: 11px;
            color: #4b5563;
            line-height: 1.5;
        }

        .terms-content ul {
            margin-left: 20px;
        }

        .terms-content li {
            margin-bottom: 5px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            gap: 40px;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 50px;
        }

        .signature-line {
            border-top: 2px solid #374151;
            padding-top: 10px;
            font-size: 11px;
            color: #6b7280;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            color: #6b7280;
        }

        @media print {
            .invoice-container {
                padding: 0;
                max-width: none;
            }

            .signature-section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="logo-placeholder">LOGO</div>
                <div class="company-name">SIM MAKE TO ORDER</div>
                <div class="company-address">
                    Jl. Contoh Alamat No. 123<br>
                    Jakarta Pusat 10120<br>
                    Telp: (021) 1234-5678<br>
                    Email: info@simmto.com<br>
                    Website: www.simmto.com
                </div>
            </div>
            <div class="invoice-header">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
            </div>
        </div>

        <!-- Billing Section -->
        <div class="billing-section">
            <div class="bill-to">
                <div class="section-title">BILL TO</div>
                <div class="address-info">
                    <strong>{{ $invoice->order->customer->name ?? 'N/A' }}</strong><br>
                    {{ $invoice->order->customer->address ?? 'Alamat customer akan ditampilkan di sini' }}<br>
                    {{ $invoice->order->customer->city ?? 'Kota' }},
                    {{ $invoice->order->customer->postal_code ?? '12345' }}<br>
                    Telp: {{ $invoice->order->customer->phone ?? '+62 812-3456-7890' }}<br>
                    Email: {{ $invoice->order->customer->email ?? 'customer@email.com' }}
                </div>
            </div>
            <div class="ship-to">
                <div class="section-title">SHIP TO</div>
                <div class="address-info">
                    <strong>{{ $invoice->order->customer->name ?? 'N/A' }}</strong><br>
                    {{ $invoice->shipping_address ?? 'Alamat pengiriman akan ditampilkan di sini' }}<br>
                    {{ $invoice->shipping_city ?? 'Kota Pengiriman' }},
                    {{ $invoice->shipping_postal_code ?? '12345' }}<br>
                    Telp: {{ $invoice->order->customer->phone ?? '+62 812-3456-7890' }}
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="details-grid">
                <div class="detail-item">
                    <span class="detail-label">Tanggal Invoice:</span>
                    <span
                        class="detail-value">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Jatuh Tempo:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">{{ $invoice->status }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">PO Number:</span>
                    <span class="detail-value">{{ $invoice->po_number ?? 'PO-2024-001' }}</span>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="40%">Deskripsi Produk</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="15%" class="text-right">Harga Satuan</th>
                    <th width="15%" class="text-right">Diskon</th>
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <strong>{{ $invoice->order->product_name }}</strong><br>
                        <small style="color: #666;">Spesifikasi:
                            {{ $invoice->order->product_specs ?? 'Custom made product sesuai requirement' }}</small>
                    </td>
                    <td class="text-center">{{ $invoice->order->quantity }} pcs</td>
                    <td class="text-right">Rp
                        {{ number_format($invoice->order->unit_price ?? ($invoice->order->total_price ?? 0), 0, ',', '.') }}
                    </td>
                    <td class="text-right">Rp {{ number_format($invoice->discount_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Totals Section -->
        <div class="totals-section">
            <table class="totals-table">
                <tr>
                    <td class="total-label">Subtotal:</td>
                    <td class="total-amount">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-label">Diskon:</td>
                    <td class="total-amount">Rp {{ number_format($invoice->discount_amount ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-label">PPN (11%):</td>
                    <td class="total-amount">Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-label">Biaya Kirim:</td>
                    <td class="total-amount">Rp {{ number_format($invoice->shipping_cost ?? 0, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="total-label"><strong>TOTAL:</strong></td>
                    <td class="total-amount" style="color: #2563eb;"><strong>Rp
                            {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            </table>
        </div>

        <!-- Payment Methods -->
        <div class="payment-section">
            <div class="payment-title">Metode Pembayaran</div>
            <div class="payment-methods">
                <div class="payment-method">
                    <div class="method-name">Transfer Bank</div>
                    <div class="method-details">
                        Bank BCA<br>
                        No. Rek: 1234567890<br>
                        A.n: SIM Make To Order
                    </div>
                </div>
                <div class="payment-method">
                    <div class="method-name">Transfer Bank</div>
                    <div class="method-details">
                        Bank Mandiri<br>
                        No. Rek: 0987654321<br>
                        A.n: SIM Make To Order
                    </div>
                </div>
                <div class="payment-method">
                    <div class="method-name">E-Wallet</div>
                    <div class="method-details">
                        GoPay: 081234567890<br>
                        OVO: 081234567890<br>
                        Dana: 081234567890
                    </div>
                </div>
            </div>
            <p style="font-size: 11px; color: #666; margin-top: 10px;">
                <strong>Note:</strong> Mohon konfirmasi pembayaran dengan mengirimkan bukti transfer ke WhatsApp: +62
                812-3456-7890
            </p>
        </div>

        <!-- Terms and Conditions -->
        <div class="terms-section">
            <div class="terms-title">Syarat dan Ketentuan</div>
            <div class="terms-content">
                <ul>
                    <li>Pembayaran harus dilakukan sebelum tanggal jatuh tempo yang tertera pada invoice.</li>
                    <li>Keterlambatan pembayaran akan dikenakan denda 2% per bulan dari total tagihan.</li>
                    <li>Barang yang sudah dipesan dan diproduksi tidak dapat dibatalkan atau dikembalikan.</li>
                    <li>Perubahan spesifikasi setelah produksi dimulai akan dikenakan biaya tambahan.</li>
                    <li>Waktu pengerjaan dihitung setelah pembayaran diterima dan spesifikasi final disetujui.</li>
                    <li>Garansi produk berlaku selama 6 bulan sejak tanggal pengiriman.</li>
                    <li>Segala perselisihan akan diselesaikan secara musyawarah atau melalui arbitrase.</li>
                </ul>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Penjual</div>
                <div class="signature-line">
                    <strong>{{ $invoice->seller_name ?? 'Manager SIM Make To Order' }}</strong><br>
                    Tanggal: ___________
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Pembeli</div>
                <div class="signature-line">
                    <strong>{{ $invoice->order->customer->name ?? 'Nama Customer' }}</strong><br>
                    Tanggal: ___________
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Terima kasih telah mempercayakan bisnis Anda kepada kami.</strong></p>
            <p>Invoice ini dibuat secara elektronik dan sah tanpa tanda tangan basah.</p>
            <p>Untuk pertanyaan mengenai invoice ini, hubungi kami di info@simmto.com atau (021) 1234-5678</p>
        </div>
    </div>
</body>

</html>
