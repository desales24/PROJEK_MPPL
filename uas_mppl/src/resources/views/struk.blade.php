<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
        
        body { 
            font-family: 'Poppins', sans-serif; 
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .struk { 
            width: 100%;
            max-width: 380px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .struk::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, #4f46e5, #10b981);
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .header h2 {
            color: #1f2937;
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            color: #6b7280;
            margin: 0;
            font-size: 14px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            display: block;
        }
        
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 20px 0;
        }
        
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
        }
        
        .item-label {
            color: #6b7280;
            font-weight: 400;
        }
        
        .item-value {
            color: #1f2937;
            font-weight: 500;
        }
        
        .amount {
            font-size: 22px;
            font-weight: 600;
            color: #10b981;
            text-align: right;
            margin: 20px 0;
        }
        
        .payment-method {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 5px;
        }
        
        .payment-icon {
            width: 24px;
            height: 24px;
        }
        
        .qris-image {
            width: 200px;
            height: 200px;
            margin: 10px auto;
            display: block;
            border: 1px solid #e5e7eb;
            padding: 10px;
            border-radius: 8px;
            background: white;
        }
        
        .bank-info {
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }
        
        .bank-info p {
            margin: 5px 0;
            font-size: 14px;
        }
        
        .bank-info .bank-name {
            font-weight: 600;
            color: #1f2937;
        }
        
        .bank-info .account-number {
            font-family: monospace;
            font-size: 16px;
            letter-spacing: 1px;
            color: #1e40af;
        }
        
        .payment-instruction {
            background: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            text-align: left;
            margin-top: 15px;
            font-size: 13px;
            color: #4b5563;
        }
        
        .payment-instruction p {
            margin: 5px 0;
        }
        
        .footer {
            text-align: center;
            margin-top: 25px;
            color: #6b7280;
            font-size: 12px;
        }
        
        .print-btn {
            background: #4f46e5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: all 0.3s;
        }
        
        .print-btn:hover {
            background: #4338ca;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .print-btn {
                display: none;
            }
        }
    </style>
    <!-- Include QR Code library -->
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
</head>
<body>
    <div class="struk">
        <!-- Logo can be replaced with your company logo -->
        <svg class="logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M8 14C8 14 9.5 16 12 16C14.5 16 16 14 16 14M9 9H9.01M15 9H15.01" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        
        <div class="header">
            <h2>Struk Pembayaran</h2>
            <p>Terima kasih telah berbelanja</p>
        </div>
        
        <div class="divider"></div>
        
        <div class="item">
            <span class="item-label">No. Pembayaran</span>
            <span class="item-value">#{{ $payment->id }}</span>
        </div>
        
        <div class="item">
            <span class="item-label">Pelanggan</span>
            <span class="item-value">{{ $payment->order->customer->name }}</span>
        </div>
        
        <div class="item">
            <span class="item-label">No. Pesanan</span>
            <span class="item-value">#{{ $payment->order->id }}</span>
        </div>
        
        <div class="item">
            <span class="item-label">Tanggal</span>
            <span class="item-value">{{ $payment->paid_at->format('d M Y H:i') }}</span>
        </div>
        
        <div class="divider"></div>
        
        <div class="item">
            <span class="item-label">Metode Pembayaran</span>
            <div class="payment-method">
                @if($payment->method === 'tunai')
                    <svg class="payment-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 6V18C3 19.6569 4.34315 21 6 21H18C19.6569 21 21 19.6569 21 18V6C21 4.34315 19.6569 3 18 3H6C4.34315 3 3 4.34315 3 6Z" stroke="#1f2937" stroke-width="2"/>
                        <path d="M3 11H21" stroke="#1f2937" stroke-width="2"/>
                        <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#1f2937" stroke-width="2"/>
                    </svg>
                    <span>Tunai</span>
                @elseif(in_array($payment->method, ['kartu debit', 'kartu kredit']))
                    <svg class="payment-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 10H21M7 15H8M12 15H13M6 19H18C19.6569 19 21 17.6569 21 16V8C21 6.34315 19.6569 5 18 5H6C4.34315 5 3 6.34315 3 8V16C3 17.6569 4.34315 19 6 19Z" stroke="#1f2937" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>{{ ucfirst($payment->method) }}</span>
                @elseif($payment->method === 'qris')
                    <svg class="payment-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3 17V7C3 5.89543 3.89543 5 5 5H19C20.1046 5 21 5.89543 21 7V17C21 18.1046 20.1046 19 19 19H5C3.89543 19 3 18.1046 3 17Z" stroke="#1f2937" stroke-width="2"/>
                        <path d="M7 12H12M17 12H12M12 12V7V17" stroke="#1f2937" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <span>QRIS</span>
                @endif
            </div>
        </div>
        
        @if(in_array($payment->method, ['kartu debit', 'kartu kredit']))
            <div class="bank-info">
                <p class="bank-name">Rekening Pembayaran</p>
                <p>Bank: <strong>BANK MANDIRI</strong></p>
                <p>Nomor Rekening: <span class="account-number">123 4567 8900</span></p>
                <p>Atas Nama: <strong>TOKO ANDA SEJAHTERA</strong></p>
                @if($payment->card_number)
                    <p style="margin-top: 10px;">No. Kartu: <span class="account-number">{{ $payment->card_number }}</span></p>
                @endif
            </div>
        @endif
        
        <div class="amount">
            Rp {{ number_format($payment->amount, 0, ',', '.') }}
        </div>
        
        <div class="item">
            <span class="item-label">Status</span>
            @php
                $statusColor = $payment->status === 'lunas' ? '#10b981' : ($payment->status === 'gagal' ? '#ef4444' : '#f59e0b');
            @endphp
            <span style="color: '{{ $statusColor }}'; font-weight: 500;">
                {{ ucfirst($payment->status) }}
            </span>
        </div>
        
        @if($payment->method === 'qris')
            <div class="divider"></div>
            <div style="text-align: center;">
                <p style="margin-bottom: 5px; color: #6b7280;">Kode Pembayaran QRIS</p>
                
                <!-- QR Code will be generated here -->
                <div id="qrcode" class="qris-image"></div>
                
                <div class="payment-instruction">
                    <p><strong>Instruksi Pembayaran:</strong></p>
                    <p>1. Buka aplikasi mobile banking/e-wallet</p>
                    <p>2. Pilih menu QRIS</p>
                    <p>3. Scan kode QR di atas</p>
                    <p>4. Konfirmasi pembayaran</p>
                </div>
            </div>
            
            <script>
                // Generate QR Code
                const qrContent = `QRIS\n` +
                                 `Merchant: TOKO ANDA\n` +
                                 `Amount: Rp {{ number_format($payment->amount, 0, ',', '.') }}\n` +
                                 `Transaction ID: {{ $payment->id }}`;
                
                new QRCode(document.getElementById("qrcode"), {
                    text: qrContent,
                    width: 180,
                    height: 180,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });
            </script>
        @endif
        
        <div class="footer">
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
            <p>Terima kasih atas kunjungan Anda</p>
        </div>
        
        <button class="print-btn" onclick="window.print()">Cetak Struk</button>
    </div>
</body>
</html>