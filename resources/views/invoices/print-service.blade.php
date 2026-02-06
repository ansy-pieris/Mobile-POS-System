<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            padding: 20px; 
            max-width: 800px; 
            margin: 0 auto; 
            background: #fff;
            color: #333;
            font-size: 13px;
        }
        
        /* Header with Logo */
        .header { 
            text-align: center; 
            margin-bottom: 25px; 
            border-bottom: 3px solid #7c3aed; 
            padding-bottom: 20px; 
        }
        .logo-container {
            margin-bottom: 10px;
        }
        .logo-container img {
            max-height: 80px;
            max-width: 200px;
            object-fit: contain;
        }
        .shop-name { 
            font-size: 26px; 
            font-weight: bold; 
            color: #6d28d9;
            margin-bottom: 5px; 
        }
        .shop-info { 
            font-size: 12px; 
            color: #666; 
            line-height: 1.6;
        }
        .service-badge {
            display: inline-block;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Invoice Info Section */
        .invoice-info { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 25px; 
            gap: 20px;
        }
        .info-box {
            flex: 1;
            padding: 15px;
            background: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #7c3aed;
        }
        .info-box.customer {
            border-left-color: #10b981;
        }
        .info-box.payment {
            border-left-color: #f59e0b;
        }
        .section-title { 
            font-weight: bold; 
            margin-bottom: 8px; 
            font-size: 12px; 
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }
        .invoice-number { 
            font-size: 18px; 
            font-weight: bold; 
            color: #6d28d9; 
        }
        .info-text {
            font-size: 13px;
            line-height: 1.6;
        }
        
        /* Service Table */
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 25px 0; 
        }
        .table th { 
            background: #6d28d9; 
            color: white;
            padding: 12px 10px; 
            text-align: left; 
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table th:first-child {
            border-radius: 8px 0 0 0;
        }
        .table th:last-child {
            border-radius: 0 8px 0 0;
        }
        .table td { 
            padding: 12px 10px; 
            border-bottom: 1px solid #e2e8f0; 
            font-size: 13px;
            vertical-align: top;
        }
        .table tbody tr:hover {
            background: #f8fafc;
        }
        .table tbody tr:last-child td {
            border-bottom: 2px solid #e2e8f0;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .service-type-badge {
            display: inline-block;
            background: #ede9fe;
            color: #6d28d9;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .discount-text {
            color: #dc2626;
            font-size: 11px;
        }
        
        /* Summary Section */
        .summary { 
            margin: 25px 0;
            display: flex;
            justify-content: flex-end;
        }
        .summary-box {
            width: 300px;
            background: #faf5ff;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #e9d5ff;
        }
        .summary-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 8px 0; 
            font-size: 14px; 
        }
        .summary-row.total { 
            border-top: 2px solid #7c3aed; 
            margin-top: 10px; 
            padding-top: 15px; 
            font-size: 20px; 
            font-weight: bold;
            color: #6d28d9;
        }
        
        /* Service Notes */
        .service-notes {
            margin: 20px 0;
            padding: 15px;
            background: #faf5ff;
            border: 1px solid #e9d5ff;
            border-radius: 8px;
        }
        .service-notes-title {
            font-weight: bold;
            font-size: 14px;
            color: #6d28d9;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Terms & Conditions */
        .terms-section {
            margin: 30px 0;
            padding: 20px;
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 8px;
        }
        .terms-title {
            font-weight: bold;
            font-size: 14px;
            color: #b45309;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .terms-content {
            font-size: 12px;
            color: #78350f;
            line-height: 1.8;
            white-space: pre-line;
        }
        
        /* Developer Credit */
        .developer-credit {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #e2e8f0;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
        .developer-credit strong {
            color: #64748b;
        }
        
        /* Print Buttons */
        .no-print { 
            text-align: center; 
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .print-button { 
            background: linear-gradient(135deg, #7c3aed, #6d28d9); 
            color: white; 
            padding: 12px 24px; 
            border: none; 
            border-radius: 8px; 
            cursor: pointer; 
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .print-button:hover { 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        .back-button {
            background: #64748b;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .back-button:hover {
            background: #475569;
        }
        
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .header { border-bottom-color: #000; }
            .table th { background: #6d28d9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .info-box { background: #f5f5f5 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .service-badge { background: #6d28d9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <!-- Print Controls (hidden when printing) -->
    <div class="no-print">
        <button onclick="window.print()" class="print-button">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Service Invoice
        </button>
        <a href="{{ route('dashboard') }}" class="back-button">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Header with Company Logo -->
    <div class="header">
        @if($settings && $settings->logo)
            <div class="logo-container">
                <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->shop_name ?? 'Company' }} Logo">
            </div>
        @endif
        <div class="shop-name">{{ $settings->shop_name ?? 'Mobile Phone Shop' }}</div>
        <div class="shop-info">
            @if($settings)
                @if($settings->address){{ $settings->address }}@endif
                @if($settings->location) | {{ $settings->location }}@endif
                <br>
                @if($settings->phone)Phone: {{ $settings->phone }}@endif
                @if($settings->email) | Email: {{ $settings->email }}@endif
            @endif
        </div>
        <div class="service-badge">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle; margin-right: 5px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Service Invoice
        </div>
    </div>

    <!-- Invoice, Customer & Payment Info -->
    <div class="invoice-info">
        <div class="info-box">
            <div class="section-title">Invoice Details</div>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            <div class="info-text" style="margin-top: 8px;">
                Date: {{ \Carbon\Carbon::parse($invoice->issued_date)->format('d M Y') }}<br>
                Time: {{ \Carbon\Carbon::parse($invoice->created_at)->format('h:i A') }}
            </div>
        </div>
        <div class="info-box customer">
            <div class="section-title">Customer Details</div>
            <div class="info-text">
                <strong>{{ $invoice->customer_name ?? ($invoice->customer->name ?? 'Walk-in Customer') }}</strong><br>
                @if($invoice->customer_phone ?? ($invoice->customer->phone ?? null))
                    Phone: {{ $invoice->customer_phone ?? $invoice->customer->phone }}<br>
                @endif
                @if($invoice->customer_nic)
                    NIC: {{ $invoice->customer_nic }}<br>
                @endif
            </div>
        </div>
        <div class="info-box payment">
            <div class="section-title">Payment & Sales</div>
            <div class="info-text">
                <strong>{{ strtoupper($invoice->payment_method) }}</strong><br>
                Technician: {{ $invoice->user->name ?? 'N/A' }}
            </div>
        </div>
    </div>

    <!-- Service Items Table -->
    <table class="table">
        <thead>
            <tr>
                <th width="8%">#</th>
                <th width="20%">Service Type</th>
                <th width="37%">Service Description</th>
                <th width="15%" class="text-right">Charge</th>
                <th width="20%" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                @php
                    $discountAmount = $item->discount ?? 0;
                    if (($item->discount_type ?? 'value') === 'percent') {
                        $discountAmount = ($item->price * $discountAmount) / 100;
                    }
                    $itemTotal = $item->total ?? ($item->price - $discountAmount);
                    
                    // Service type labels
                    $serviceLabels = [
                        'screen_repair' => 'Screen Repair',
                        'battery_replacement' => 'Battery Replacement',
                        'charging_port' => 'Charging Port',
                        'software_update' => 'Software Update',
                        'data_recovery' => 'Data Recovery',
                        'water_damage' => 'Water Damage',
                        'speaker_repair' => 'Speaker Repair',
                        'camera_repair' => 'Camera Repair',
                        'button_repair' => 'Button Repair',
                        'other' => 'Other Service',
                    ];
                    $serviceLabel = $serviceLabels[$item->service_type] ?? ($item->service_type ?? 'Service');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <span class="service-type-badge">{{ $serviceLabel }}</span>
                    </td>
                    <td>
                        {{ $item->description }}
                    </td>
                    <td class="text-right">
                        Rs.{{ number_format($item->price, 2) }}
                        @if($discountAmount > 0)
                            <br><span class="discount-text">-Rs.{{ number_format($discountAmount, 2) }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        <strong>Rs.{{ number_format($itemTotal, 2) }}</strong>
                        @if($discountAmount > 0)
                            <br><span class="discount-text">Disc: -Rs.{{ number_format($discountAmount, 2) }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-box">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rs.{{ number_format($invoice->total_amount + $invoice->discount, 2) }}</span>
            </div>
            @if($invoice->discount > 0)
                <div class="summary-row" style="color: #dc2626;">
                    <span>Total Discount:</span>
                    <span>-Rs.{{ number_format($invoice->discount, 2) }}</span>
                </div>
            @endif
            <div class="summary-row total">
                <span>TOTAL:</span>
                <span>Rs.{{ number_format($invoice->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Service Notes -->
    <div class="service-notes">
        <div class="service-notes-title">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Service Notes
        </div>
        <ul style="font-size: 12px; color: #6d28d9; line-height: 1.8; margin-left: 20px;">
            <li>Please retain this invoice for any future service claims.</li>
            <li>Service warranty is valid for 7 days from the service date.</li>
            <li>Physical or water damage after service is not covered under warranty.</li>
            <li>For any issues, please contact us with your invoice number.</li>
        </ul>
    </div>

    <!-- Terms & Conditions -->
    @if($settings && $settings->terms_conditions)
        <div class="terms-section">
            <div class="terms-title">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Terms & Conditions
            </div>
            <div class="terms-content">{{ $settings->terms_conditions }}</div>
        </div>
    @endif

    <!-- Developer Credit -->
    <div class="developer-credit" style="margin-top: 40px; padding: 20px; background: #f8fafc; border-radius: 8px; text-align: center; font-size: 12px; color: #475569; border: 1px solid #e2e8f0;">
        <strong style="color: #6d28d9; font-size: 13px;">This invoice has been created by Nexora Solutions</strong><br>
        <span style="color: #64748b; font-size: 11px;">Email: nexorasolution.lk@gmail.com</span>
    </div>
</body>
</html>
