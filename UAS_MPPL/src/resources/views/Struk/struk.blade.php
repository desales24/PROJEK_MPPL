<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f5f5f5;
        }
        .struk {
            background: white;
            border: 1px solid #ccc;
            padding: 25px;
            width: 450px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .info p {
            margin: 5px 0;
        }
        .items {
            margin-top: 15px;
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            border-bottom: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items th {
            background-color: #f0f0f0;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 15px;
        }
        .barcode {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="struk">
        <h2>Struk Pembayaran</h2>
        <div class="info">
            <p><strong>ID Order:</strong> {{ $payment->order_id }}</p>
            <p><strong>Nama Pemesan:</strong> {{ $payment->order->customer->name }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ strtoupper($payment->method) }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ $payment->paid_at?->format('d-m-Y H:i') }}</p>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payment->order->orderItems as $item)
                    <tr>
                        <td>{{ $item->menu->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total: Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>

        <!-- resources/views/Struk/struk-view.blade.php -->
        <div class="barcode">
            <p>Scan QR untuk membayar:</p>
            {!! QrCode::size(180)->generate($qrData) !!}
        </div>
    </div>
</body>
</html>
