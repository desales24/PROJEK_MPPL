<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        .struk { max-width: 400px; border: 1px solid #ccc; padding: 20px; }
        .struk h2 { margin-top: 0; }
        .item { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="struk">
        <h2>Struk Pembayaran</h2>
        <p><strong>No. Pembayaran:</strong> {{ $payment->id }}</p>
        <p><strong>Nama Pelanggan:</strong> {{ $payment->order->customer->name }}</p>
        <p><strong>No. Pesanan:</strong> #{{ $payment->order->id }}</p>
        <p><strong>Metode Pembayaran:</strong> {{ ucfirst($payment->method) }}</p>
        <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
        <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
        <p><strong>Tanggal Bayar:</strong> {{ $payment->paid_at->format('d M Y H:i') }}</p>

        @if($payment->method === 'kartu kredit')
            <p><strong>Nomor Kartu:</strong> {{ $payment->card_number ?? '-' }}</p>
        @elseif($payment->method === 'qris')
            <p><strong>QRIS:</strong></p>
            <img src="{{ asset('storage/' . $payment->proof_of_payment) }}" alt="QRIS" style="max-width:100%;">
        @endif

        <br>
        <button onclick="window.print()">Cetak</button>
    </div>
</body>
</html>
