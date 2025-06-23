<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #f9fafb;
            --accent: #10b981;
            --text: #1f2937;
            --light-text: #6b7280;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .receipt-container {
            max-width: 380px;
            width: 100%;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            background: white;
            margin-bottom: 30px;
        }
        
        .receipt-header {
            background: linear-gradient(135deg, var(--primary), #6366f1);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }
        
        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .receipt-logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .receipt-logo img {
            width: 40px;
            height: 40px;
        }
        
        .receipt-body {
            padding: 25px;
        }
        
        .receipt-info {
            margin-bottom: 25px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed #e5e7eb;
        }
        
        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .info-label {
            color: var(--light-text);
            font-weight: 500;
        }
        
        .info-value {
            color: var(--text);
            font-weight: 600;
            text-align: right;
        }
        
        .amount {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            margin: 20px 0;
        }
        
        .payment-method {
            background-color: var(--secondary);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        .payment-method h3 {
            margin-top: 0;
            color: var(--primary);
            font-size: 16px;
        }
        
        .qr-container {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px auto;
            text-align: center;
            max-width: 200px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .qr-container p {
            margin-top: 10px;
            font-size: 14px;
            color: var(--light-text);
        }
        
        .receipt-footer {
            text-align: center;
            padding: 20px;
            color: var(--light-text);
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        
        .print-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
            margin-top: 20px;
        }
        
        .print-btn:hover {
            background-color: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(79, 70, 229, 0.3);
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .status-paid {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .receipt-container, .receipt-container * {
                visibility: visible;
            }
            .receipt-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                box-shadow: none;
                border-radius: 0;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <div class="receipt-logo">
                <img src="https://cdn-icons-png.flaticon.com/512/245/245399.png" alt="Logo">
            </div>
            <h1>Struk Pembayaran</h1>
        </div>
        
        <div class="receipt-body">
            <div class="receipt-info">
                <div class="info-row">
                    <span class="info-label">No. Transaksi</span>
                    <span class="info-value">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pelanggan</span>
                    <span class="info-value">{{ $payment->order->customer->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal</span>
                    <span class="info-value">{{ $payment->paid_at?->format('d M Y H:i') }}</span>
                </div>
            </div>
            
            <div class="amount">
                Rp {{ number_format($payment->amount, 0, ',', '.') }}
            </div>
            
            <div class="payment-method">
                <h3>Metode Pembayaran</h3>
                <div class="info-row">
                    <span class="info-label">Jenis</span>
                    <span class="info-value">{{ ucfirst($payment->method) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $payment->status === 'lunas' ? 'paid' : ($payment->status === 'belum bayar' ? 'pending' : 'failed') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </span>
                </div>
                
                @if ($payment->method === 'qris')
                    <div class="qr-container">
                        {!! QrCode::size(150)->generate('https://bayar-resto.com/qris/'.str_pad($payment->id, 8, '0', STR_PAD_LEFT)) !!}
                        <p>Scan QR Code untuk pembayaran</p>
                    </div>
                @elseif ($payment->method === 'kartu kredit')
                    <div class="info-row">
                        <span class="info-label">Nomor Kartu</span>
                        <span class="info-value">**** **** **** 1234</span>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="receipt-footer">
            Terima kasih telah berbelanja di Resto Kami<br>
            {{ date('Y') }} &copy; Nama Restoran Anda
        </div>
    </div>
    
    <button class="print-btn" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Struk
    </button>
    
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>