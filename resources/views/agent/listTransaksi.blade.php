<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex bg-gray-100">

    <!-- Sidebar -->
    <div class="w-64 bg-blue-600 text-white h-screen fixed p-6">
        <h2 class="text-2xl font-semibold text-center mb-6">
            <a href="{{ route('agent.dashboard') }}" class="text-white hover:text-gray-300">Dashboard</a>
        </h2>
        <ul class="space-y-4">
            <li>
                <a href="{{ route('agent.kasir') }}" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                    Kasir
                </a>
            </li>
            <li>
                <a href="{{ route('transaksi.semua') }}" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                    Transaksi
                </a>
            </li>
        </ul>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="w-full p-3 bg-gray-100 text-gray-800 rounded text-center font-semibold border border-gray-300 hover:bg-gray-300 transition">
                Log Out
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="ml-64 w-full p-6">
        <div class="bg-blue-600 text-white p-4 rounded-lg shadow mb-6">
            <h1 class="text-3xl font-semibold text-center">Kasir</h1>
        </div>

        <div class="container mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold text-center mb-6">Semua Transaksi</h2>
            <table class="min-w-full table-auto border-collapse">
                <thead class="bg-green-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Total Harga</th>
                        <th class="px-4 py-2 text-left">Bayar</th>
                        <th class="px-4 py-2 text-left">Kembalian</th>
                        <th class="px-4 py-2 text-left">Detail Transaksi</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Cetak Struk</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $item->id }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($item->bayar, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($item->kembalian, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                @php
                                    $detailTransaksi = json_decode($item->detail_transaksi, true);
                                @endphp
                                <ul class="list-disc pl-5 space-y-1 text-gray-700">
                                    @if (is_array($detailTransaksi))
                                        @foreach ($detailTransaksi as $detail)
                                            <li>
                                                <span class="font-medium">Nama:</span> {{ $detail['nama'] }},
                                                <span class="font-medium">Harga:</span> Rp
                                                {{ number_format($detail['harga'], 0, ',', '.') }},
                                                <span class="font-medium">Jumlah:</span> {{ $detail['jumlah'] }}
                                            </li>
                                        @endforeach
                                    @else
                                        <li>Detail tidak tersedia</li>
                                    @endif
                                </ul>
                            </td>
                            <td class="px-4 py-2">{{ $item->created_at }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ url('/transaksi/cetak-pdf/' . $item->id) }}"
                                    class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                                    target="_blank">
                                    Cetak Struk
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>
