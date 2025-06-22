<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            color: #000;
            background-color: #f5f5f5;
            padding: 40px 0;
            display: flex;
            justify-content: center;
        }
        .receipt-box {
            background: #fff;
            border: 2px solid #000;
            padding: 25px 30px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
        }
        .center {
            text-align: center;
        }
        .line {
            border-top: 1px dashed  #000; /* garis solid */
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 4px 0;
        }
        .total-line {
            border-top: 2px solid #000;
            font-weight: bold;
            padding-top: 6px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-style: italic;
        }
        img.qr {
            display: block;
            margin: 10px auto;
            width: 150px;
        }
    </style>
</head>
<body>
    <div class="receipt-box">
        <div class="center">
            <h3>RESTORAN KAMI</h3>
            <small>Jl. Contoh No. 123 - Telp: (021) 123456</small>
        </div>

        <div class="line"></div>

        <p><strong>No. Order:</strong> #{{ $payment->order->id }}</p>
        <p><strong>Pelanggan:</strong> {{ $payment->order->customer->name }}</p>
        <p><strong>Tanggal:</strong> {{ $payment->paid_at->format('d M Y H:i') }}</p>

        <div class="line"></div>

        <table>
            @php $total = 0; @endphp
            @foreach ($payment->order->orderItems as $item)
                @php
                    $subtotal = $item->menu->price * $item->quantity;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td colspan="2">{{ $item->menu->name }}</td>
                </tr>
                <tr>
                    <td>{{ $item->quantity }} x Rp{{ number_format($item->menu->price, 0, ',', '.') }}</td>
                    <td style="text-align:right;">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr class="total-line">
                <td><strong>Total Bayar</strong></td>
                <td style="text-align:right;">Rp{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <p><strong>Metode:</strong> {{ ucfirst($payment->method) }}</p>

        @if(in_array($payment->method, ['debit', 'credit']))
            <p><strong>No Kartu:</strong> {{ $payment->card_number ?? '•••• •••• •••• 1234' }}</p>
        @elseif($payment->method === 'qr')
            <p class="center"><strong>QRIS:</strong></p>
            <img class="qr" src="{{ asset('images/qris-sample.png') }}" alt="QRIS Code">
        @endif

        <div class="line"></div>

        <div class="footer">
            <p>~ Terima kasih ~</p>
            <p>Silakan datang kembali</p>
        </div>
    </div>
</body>
</html>
