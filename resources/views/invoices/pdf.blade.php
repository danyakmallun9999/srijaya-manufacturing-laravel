{{-- <!DOCTYPE html>
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
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: #2563eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            flex-shrink: 0;
        }

        .company-details {
            flex: 1;
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
            font-size: 11px;
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
            margin-bottom: 25px;
            gap: 30px;
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
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
        }

        .items-table td {
            padding: 10px 8px;
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
            margin-bottom: 20px;
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
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .payment-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
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
            margin-bottom: 20px;
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
            margin-top: 25px;
            gap: 30px;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 40px;
        }

        .signature-line {
            border-top: 2px solid #374151;
            padding-top: 8px;
            font-size: 11px;
            color: #6b7280;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
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
                <div class="company-details">
                    <div class="company-name">SIM MAKE TO ORDER</div>
                    <div class="company-address">
                        Jl. Contoh Alamat No. 123, Jakarta Pusat 10120<br>
                        Telp: (021) 1234-5678 | Email: info@simmto.com | www.simmto.com
                    </div>
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
            <div class="payment-method">
                <div class="method-name">Transfer Bank BCA</div>
                <div class="method-details">
                    No. Rekening: 1234567890 | A.n: SIM Make To Order<br>
                    <strong>Note:</strong> Konfirmasi pembayaran ke WhatsApp: +62 812-3456-7890
                </div>
            </div>
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

</html> --}}



















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
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            padding: 0;
        }

        .company-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .company-info-table td {
            vertical-align: top;
            padding: 0;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            background: #2563eb;
            border-radius: 8px;
            text-align: center;
            vertical-align: middle;
            color: white;
            font-weight: bold;
            font-size: 14px;
            line-height: 60px;
        }

        .company-details {
            padding-left: 15px;
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
            font-size: 11px;
        }

        .invoice-header {
            text-align: right;
            width: 200px;
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
            margin-bottom: 25px;
        }

        .billing-table {
            width: 100%;
            border-collapse: collapse;
        }

        .billing-table td {
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .billing-table td:last-child {
            padding-right: 0;
            padding-left: 20px;
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
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
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
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
        }

        .items-table td {
            padding: 10px 8px;
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
            margin-bottom: 20px;
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
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .payment-title {
            font-size: 14px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
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
            margin-bottom: 20px;
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
            margin-top: 25px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 20px;
        }

        .signature-title {
            font-weight: bold;
            color: #374151;
            margin-bottom: 40px;
        }

        .signature-line {
            border-top: 2px solid #374151;
            padding-top: 8px;
            font-size: 11px;
            color: #6b7280;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
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
            <table class="header-table">
                <tr>
                    <td>
                        <table class="company-info-table">
                            <tr>
                                <td style="width: 60px;">
                                    <div class="logo-placeholder">LOGO</div>
                                </td>
                                <td class="company-details">
                                    <div class="company-name">SIM MAKE TO ORDER</div>
                                    <div class="company-address">
                                        Jl. Contoh Alamat No. 123, Jakarta Pusat 10120<br>
                                        Telp: (021) 1234-5678 | Email: info@simmto.com | www.simmto.com
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td class="invoice-header">
                        <div class="invoice-title">INVOICE</div>
                        <div class="invoice-number"># {{ $invoice->invoice_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Billing Section -->
        <div class="billing-section">
            <table class="billing-table">
                <tr>
                    <td>
                        <div class="section-title">BILL TO</div>
                        <div class="address-info">
                            <strong>{{ $invoice->order->customer->name ?? 'N/A' }}</strong><br>
                            {{ $invoice->order->customer->address ?? 'Alamat customer akan ditampilkan di sini' }}<br>
                            {{ $invoice->order->customer->city ?? 'Kota' }},
                            {{ $invoice->order->customer->postal_code ?? '12345' }}<br>
                            Telp: {{ $invoice->order->customer->phone ?? '+62 812-3456-7890' }}<br>
                            Email: {{ $invoice->order->customer->email ?? 'customer@email.com' }}
                        </div>
                    </td>
                    <td>
                        <div class="section-title">SHIP TO</div>
                        <div class="address-info">
                            <strong>{{ $invoice->order->customer->name ?? 'N/A' }}</strong><br>
                            {{ $invoice->shipping_address ?? 'Alamat pengiriman akan ditampilkan di sini' }}<br>
                            {{ $invoice->shipping_city ?? 'Kota Pengiriman' }},
                            {{ $invoice->shipping_postal_code ?? '12345' }}<br>
                            Telp: {{ $invoice->order->customer->phone ?? '+62 812-3456-7890' }}
                        </div>
                    </td>
                </tr>
            </table>
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
            <div class="payment-method">
                <div class="method-name">Transfer Bank BCA</div>
                <div class="method-details">
                    No. Rekening: 1234567890 | A.n: SIM Make To Order<br>
                    <strong>Note:</strong> Konfirmasi pembayaran ke WhatsApp: +62 812-3456-7890
                </div>
            </div>
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
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-title">Penjual</div>
                        <div class="signature-line">
                            <strong>{{ $invoice->seller_name ?? 'Manager SIM Make To Order' }}</strong><br>
                            Tanggal: ___________
                        </div>
                    </td>
                    <td>
                        <div class="signature-title">Pembeli</div>
                        <div class="signature-line">
                            <strong>{{ $invoice->order->customer->name ?? 'Nama Customer' }}</strong><br>
                            Tanggal: ___________
                        </div>
                    </td>
                </tr>
            </table>
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
