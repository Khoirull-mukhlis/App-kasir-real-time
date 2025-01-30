<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Struk Pembelian</h2>
        <p><strong>ID Transaksi:</strong> {{ $transaksi->id }}</p>
        <p><strong>Tanggal:</strong> {{ $transaksi->created_at }}</p>

        <table>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detailTransaksi as $detail)
                    <tr>
                        <td>{{ $detail['nama'] }}</td>
                        <td>Rp {{ number_format($detail['harga'], 0, ',', '.') }}</td>
                        <td>{{ $detail['jumlah'] }}</td>
                        <td>Rp {{ number_format($detail['subtotal'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Total Harga: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
        <p class="total">Bayar: Rp {{ number_format($transaksi->bayar, 0, ',', '.') }}</p>
        <p class="total">Kembalian: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>
    </div>
</body>
</html>
