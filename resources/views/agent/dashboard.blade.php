<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex h-screen bg-gray-100 font-sans">

    <!-- Sidebar -->
    <div class="w-64 bg-blue-600 text-white flex-shrink-0">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Kasir Dashboard</h2>
            <ul>
                <li class="mb-2">
                    <a href="{{ route('agent.kasir')}}" class="block p-3 rounded hover:bg-blue-700 transition">
                        Kasir
                    </a>
                </li>
                <li class="mb-4">
                    <a href="{{ route('transaksi.semua')}}" class="block p-3 rounded hover:bg-blue-700 transition">
                        Detail Transaksi
                    </a>
                </li>
            </ul>
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit" class="w-full p-3 bg-gray-100 text-gray-800 rounded text-center font-semibold border border-gray-300 hover:bg-gray-300 transition">
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <div class="bg-blue-500 text-white p-4">
            <h1 class="text-center text-xl font-semibold">Selamat Datang di Dashboard</h1>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-4">
            <!-- Card 1 -->
            <a href="{{ route('agent.kasir')}}" class="block bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-2">Menu Kasir</h3>
                <p class="text-gray-700">Gunakan menu ini untuk mengelola transaksi pelanggan.</p>
            </a>
            <!-- Card 2 -->
            <a href="{{ route('transaksi.semua')}}" class="block bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-2">Riwayat Transaksi</h3>
                <p class="text-gray-700">Melihat data transaksi yang telah dilakukan.</p>
            </a>
        </div>
    </div>

</body>

</html>
