<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran #{{ $payment->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 15px; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .address { font-size: 10px; margin-bottom: 10px; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .table th { text-align: left; padding: 3px 0; }
        .table td { padding: 3px 0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 15px; text-align: center; font-size: 10px; }
        .barcode { margin: 10px auto; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ config('app.name') }}</div>
        <div class="address">
            Jl. Contoh No. 123, Kota Anda<br>
            Telp: (021) 12345678
        </div>
        <div class="divider"></div>
        <div style="font-weight: bold;">STRUK PEMBAYARAN</div>
    </div>

    <table class="table">
        <tr>
            <th width="30%">No. Pembayaran</th>
            <td>: {{ $payment->id }}</td>
        </tr>
        <tr>
            <th>Tanggal</th>
            <td>: {{ $payment->paid_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Kasir</th>
            <td>: {{ auth()->user()->name }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="table">
        <tr>
            <th width="30%">Pelanggan</th>
            <td>: {{ $order->customer->name }}</td>
        </tr>
        <tr>
            <th>No. Pesanan</th>
            <td>: {{ $order->id }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="table">
        <tr>
            <th width="70%">Metode Pembayaran</th>
            <td class="text-right">{{ strtoupper($payment->method) }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td class="text-right">
                <strong>{{ strtoupper($payment->status) }}</strong>
            </td>
        </tr>
        <tr>
            <th>Total Pembayaran</th>
            <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    <div class="barcode">
        <!-- Anda bisa menambahkan barcode generator di sini -->
        <div style="font-family: 'Libre Barcode 128', cursive; font-size: 24px;">
            *PAY{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}*
        </div>
    </div>

    <div class="footer">
        Terima kasih telah berbelanja di {{ config('app.name') }}<br>
        Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan
    </div>
</body>
</html>