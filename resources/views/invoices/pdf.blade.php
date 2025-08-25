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
            font-size: 16px;
            line-height: 1.5;
            background: #fff;
        }

        .invoice-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 8px 15px;
            background: white;
        }

        .header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 4px solid #2563eb;
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
            width: 100px;
            height: 100px;
            background: #2563eb;
            border-radius: 10px;
            text-align: center;
            vertical-align: middle;
            color: white;
            font-weight: bold;
            font-size: 22px;
            line-height: 100px;
        }

        .company-details {
            padding-left: 20px;
            vertical-align: middle;
        }

        .company-name {
            font-size: 32px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 8px;
        }

        .company-address {
            color: #666;
            line-height: 1.5;
            font-size: 15px;
        }

        .invoice-header {
            text-align: right;
            width: 250px;
        }

        .invoice-title {
            font-size: 42px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .invoice-number {
            font-size: 18px;
            color: #666;
        }

        .billing-section {
            margin-bottom: 20px;
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
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e5e7eb;
        }

        .address-info {
            line-height: 1.5;
            color: #333;
            font-size: 15px;
        }

        .invoice-details {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 5px solid #2563eb;
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
            font-size: 15px;
        }

        .detail-value {
            color: #1f2937;
            font-size: 15px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background: #2563eb;
            color: white;
            padding: 15px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 15px;
        }

        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 15px;
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
            min-width: 350px;
        }

        .totals-table tr {
            border-bottom: 1px solid #e5e7eb;
        }

        .totals-table tr:last-child {
            border-bottom: 3px solid #2563eb;
            font-weight: bold;
            font-size: 18px;
        }

        .totals-table td {
            padding: 10px 15px;
            font-size: 15px;
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
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .payment-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .payment-method {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .method-name {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
            font-size: 15px;
        }

        .method-details {
            font-size: 14px;
            color: #6b7280;
        }

        .terms-section {
            margin-bottom: 20px;
        }

        .terms-title {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
        }

        .terms-content {
            font-size: 14px;
            color: #4b5563;
            line-height: 1.5;
        }

        .terms-content ul {
            margin-left: 20px;
        }

        .terms-content li {
            margin-bottom: 6px;
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
            font-size: 16px;
        }

        .signature-line {
            border-top: 2px solid #374151;
            padding-top: 8px;
            font-size: 14px;
            color: #6b7280;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            font-size: 13px;
            color: #6b7280;
        }

        @media print {
            .invoice-container {
                padding: 5px 10px;
                max-width: none;
            }

            .signature-section {
                page-break-inside: avoid;
            }

            body {
                margin: 0;
                padding: 0;
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
                                <td style="width: 120px; padding-right: 15px;">
                                    @if ($logoBase64)
                                        <img src="{{ $logoBase64 }}" alt="Logo Perusahaan"
                                            style="width: 100px; height: 100px; object-fit: contain; border-radius: 10px;">
                                    @else
                                        <div class="logo-placeholder">
                                            IDEFU
                                        </div>
                                    @endif
                                </td>
                                <td class="company-details">
                                    <div class="company-name">{{ $invoice->company_name ?? 'Idefu Furniture' }}</div>
                                    <div class="company-address">
                                        {{ $invoice->company_address ?? 'Office : Jl. Hugeng Imam Santoso Km.09 NGabul Tahunan Jepara, Central Java Indonesia. Workshop : Bawu Rt 10/02 Batealit Jepara.' }}<br>
                                        Telp: {{ $invoice->company_phone ?? '+6285741555089' }} | Email:
                                        {{ $invoice->company_email ?? 'idesign@idefu.co.id' }} |
                                        {{ $invoice->company_website ?? 'idefu.co.id' }}
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
                            {{ $invoice->order->customer->address ?? 'Alamat customer akan ditampilkan di sini' }}
                        </div>
                    </td>
                    <td>
                        <div class="section-title">SHIP TO</div>
                        <div class="address-info">
                            <strong>{{ $invoice->order->customer->name ?? 'N/A' }}</strong><br>
                            {{ $invoice->shipping_address ?? ($invoice->order->customer->address ?? 'Alamat pengiriman akan ditampilkan di sini') }}<br>
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
                    <span class="detail-label">Status Pembayaran:</span>
                    <span class="detail-value">{{ $invoice->payment_status_display }}</span>
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
                    <th width="15%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>
                        <strong style="font-size: 16px;">{{ $invoice->order->product_name }}</strong><br>
                        @if ($invoice->order->product_type === 'custom')
                            <small style="color: #666; font-size: 13px;">Spesifikasi:
                                {{ $invoice->order->product_specification ?? 'Custom made product sesuai requirement' }}</small>
                            <br><small style="color: #666; font-size: 13px;">Tipe: Produk Custom</small>
                        @else
                            @if ($invoice->order->product)
                                <small style="color: #666; font-size: 13px;">Model:
                                    {{ $invoice->order->product->model ?? '-' }}</small><br>
                                <small style="color: #666; font-size: 13px;">Jenis Kayu:
                                    {{ $invoice->order->product->wood_type ?? '-' }}</small><br>
                                <small style="color: #666; font-size: 13px;">Detail:
                                    {{ $invoice->order->product->details ?? '-' }}</small>
                            @else
                                <small style="color: #666; font-size: 13px;">Produk Tetap</small>
                            @endif
                        @endif
                    </td>
                    <td class="text-center" style="font-size: 15px;">{{ $invoice->order->quantity }} pcs</td>
                    <td class="text-right" style="font-size: 15px;">Rp
                        {{ number_format($invoice->order->product_type === 'custom' ? $invoice->subtotal / $invoice->order->quantity : $invoice->order->total_price ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="text-right" style="font-size: 15px; font-weight: bold;">Rp
                        {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
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
                    <td class="total-label">Biaya Pengiriman:</td>
                    <td class="total-amount">Rp {{ number_format($invoice->shipping_cost ?? 0, 0, ',', '.') }}</td>
                </tr>
                @if ($invoice->order->product_type !== 'custom')
                    <tr>
                        <td class="total-label"><strong>TOTAL:</strong></td>
                        <td class="total-amount" style="color: #2563eb;"><strong>Rp
                                {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td class="total-label"><strong>HARGA AKAN DIHITUNG:</strong></td>
                        <td class="total-amount" style="color: #dc2626; font-style: italic;"><strong>Akan dihitung
                                setelah produksi selesai.</strong></td>
                    </tr>
                @endif
            </table>
        </div>

        @if ($invoice->order->product_type === 'custom')
            <!-- Payment Information for Custom Products -->
            <div class="payment-section">
                <div class="payment-title">Informasi Pembayaran</div>
                <div class="payment-method">
                    <div class="method-name">Status Pembayaran</div>
                    <div class="method-details">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <span style="font-size: 14px;">Total DP yang sudah dibayar:</span>
                            <span style="font-weight: bold; color: #059669; font-size: 14px;">Rp
                                {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 14px;">Status:</span>
                            <span
                                style="font-weight: bold; color: #2563eb; font-size: 14px;">{{ $invoice->payment_status_display }}</span>
                        </div>
                        <div
                            style="margin-top: 12px; padding: 10px; background: #fef3c7; border-radius: 6px; font-size: 13px; color: #92400e;">
                            <strong>Info:</strong> Harga final akan dihitung setelah produksi
                            selesai.
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Payment Methods for Fixed Products -->
            <div class="payment-section">
                <div class="payment-title">Metode Pembayaran</div>
                <div class="payment-method">
                    <div class="method-name">{{ $invoice->payment_method_display ?? 'Transfer Bank BCA' }}</div>
                    @if ($invoice->bank_name && $invoice->account_number)
                        <div class="method-details">
                            Bank: {{ $invoice->bank_name }}<br>
                            No. Rek: {{ $invoice->account_number }}<br>
                            Atas Nama: {{ $invoice->account_holder ?? 'Idefu Furniture' }}
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Terms and Conditions -->
        <div class="terms-section">
            <div class="terms-title">Syarat dan Ketentuan</div>
            <div class="terms-content">
                @if ($invoice->terms_conditions)
                    {!! nl2br(e($invoice->terms_conditions)) !!}
                @else
                    <ul>
                        <li>Pembayaran harus dilakukan sebelum tanggal jatuh tempo yang tertera pada invoice.</li>
                        <li>Barang yang sudah dipesan dan diproduksi tidak dapat dibatalkan atau dikembalikan.</li>
                        <li>Perubahan spesifikasi setelah produksi dimulai akan dikenakan biaya tambahan.</li>
                        <li>Waktu pengerjaan dihitung setelah pembayaran diterima dan spesifikasi final disetujui.</li>
                        <li>Segala perselisihan akan diselesaikan secara musyawarah atau melalui arbitrase.</li>
                    </ul>
                @endif
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td>
                        <div class="signature-title">Penjual</div>
                        <div class="signature-line">
                            <strong>{{ $invoice->seller_name ?? 'Idefu Furniture' }}</strong><br>
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
            <p>Untuk pertanyaan mengenai invoice ini, hubungi kami di idesign@idefu.co.id atau +6285741555089</p>
        </div>
    </div>
</body>

</html>
