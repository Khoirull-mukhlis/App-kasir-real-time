<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="flex bg-gray-100 font-sans">

    <!-- Sidebar -->
    <div class="w-64 bg-blue-600 text-white h-screen fixed">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-center mb-6">
                <a href="{{ route('agent.dashboard') }}">Dashboard</a>
            </h2>
            <ul>
                <li class="mb-2">
                    <a href="{{ route('agent.kasir') }}" class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                        Kasir
                    </a>
                </li>
                <li>
                    <a href="{{ route('transaksi.semua') }}"
                        class="block px-4 py-2 rounded hover:bg-blue-700 transition">
                        Transaksi
                    </a>
                </li>
            </ul>
            <form method="POST" action="{{ route('logout') }}" class="mt-6">
                @csrf
                <button type="submit"
                    class="w-full p-3 bg-gray-100 text-gray-800 rounded text-center font-semibold border border-gray-300 hover:bg-gray-300 transition">
                    Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 ml-64 p-6 space-y-4">
        <!-- Header -->
        <div class="bg-blue-500 text-white p-4 rounded shadow">
            <h1 class="text-xl font-semibold text-center">Kasir</h1>
        </div>

        <!-- Success/Error Alerts -->
        @if ($message = Session::get('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded border border-green-300">
                <strong>Success!</strong> {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded border border-red-300">
                <strong>Error!</strong> {{ $message }}
            </div>
        @endif

        <!-- Input Section -->
        <div class="bg-white p-4 rounded shadow">
            <input type="text" id="search-barang" placeholder="Kode Barang atau Nama Barang"
                class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300">

            <!-- Search Results -->
            <ul id="search-results"
                class="mt-2 max-h-48 overflow-y-auto bg-white border border-gray-300 rounded shadow-lg hidden">
                <!-- Product items will be appended here -->
            </ul>
        </div>

        <!-- Table -->
        <table class="w-full bg-white rounded shadow border-collapse overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2 text-left">Kode</th>
                    <th class="px-4 py-2 text-left">Nama Barang</th>
                    <th class="px-4 py-2 text-left">Harga</th>
                    <th class="px-4 py-2 text-left">Jumlah</th>
                    <th class="px-4 py-2 text-left">Subtotal</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody id="table-barang" class="divide-y divide-gray-200">
                <!-- Barang akan ditambahkan di sini -->
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="bg-white p-6 rounded shadow flex flex-wrap gap-6 items-center">
            <div>
                <label for="bayar" class="block text-gray-700 font-semibold mb-1">Bayar:</label>
                <input type="number" id="bayar" placeholder="0"
                    class="w-40 p-3 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-300">
            </div>
            <div class="text-gray-700">
                <p>Total Harga: <span id="total-harga" class="font-bold text-blue-500">Rp 0</span></p>
                <p>Kembalian: <span id="kembalian" class="font-bold text-blue-500">Rp 0</span></p>
            </div>
        </div>

        <!-- Simpan Button -->
        <button class="bg-blue-500 text-white px-6 py-3 rounded shadow hover:bg-blue-600 transition"
            onclick="simpanTransaksi()">Simpan</button>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        // $('#search-barang').on('input', function() {
        //     const searchTerm = $(this).val(); // Get the input value

        //     if (searchTerm.trim() !== '') {
        //         $.ajax({
        //             type: 'GET',
        //             url: '/getproduk', // Use your backend API route for searching
        //             data: {
        //                 search: searchTerm // Send the input as a search parameter
        //             },
        //             dataType: 'json',
        //             success: function(data) {
        //                 if (data && data.code && data.nama && data.harga) {
        //                     const produk = data;

        //                     // Check if the product already exists in the table
        //                     const existingRow = $(`#table-barang tr[data-code='${produk.code}']`);
        //                     if (existingRow.length) {
        //                         const currentJumlah = parseInt(existingRow.find('.jumlah').val());
        //                         const newJumlah = currentJumlah + 1;
        //                         const newSubtotal = produk.harga * newJumlah;

        //                         existingRow.find('.jumlah').val(newJumlah);
        //                         existingRow.find('.subtotal').text(`Rp ${newSubtotal}`);
        //                     } else {
        //                         tambahTransaksi(produk.code, produk.nama, produk.harga, 1, produk
        //                         .harga);
        //                     }

        //                     updateTotalHarga();
        //                 } else {
        //                     alert('Produk tidak ditemukan atau data tidak lengkap!');
        //                 }
        //             },
        //         });
        //     }
        // });
        $('#search-barang').on('input', function() {
            const searchTerm = $(this).val(); // Get the input value

            if (searchTerm.trim() !== '') {
                $.ajax({
                    type: 'GET',
                    url: '/getproduk', // Your backend API for searching
                    data: {
                        search: searchTerm
                    },
                    dataType: 'json',
                    success: function(data) {
                        const searchResults = $('#search-results');
                        searchResults.empty(); // Clear previous results

                        if (data && data.length > 0) {
                            searchResults.removeClass('hidden'); // Show search results

                            // Loop through the data and display it
                            data.forEach(produk => {
                                searchResults.append(`
                            <li class="px-4 py-2 hover:bg-blue-100 cursor-pointer"
                                data-code="${produk.code}" data-nama="${produk.nama}" data-harga="${produk.harga}">
                                ${produk.code} - ${produk.nama} (Rp ${produk.harga})
                            </li>
                        `);
                            });
                        } else {
                            searchResults.addClass('hidden'); // Hide if no results found
                        }
                    },
                });
            } else {
                $('#search-results').addClass('hidden'); // Hide search results if input is empty
            }
        });

        // When a product is clicked, add it to the table
        $(document).on('click', '#search-results li', function() {
            const code = $(this).data('code');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');

            // Check if the product already exists in the table
            const existingRow = $(`#table-barang tr[data-code='${code}']`);
            if (existingRow.length) {
                const currentJumlah = parseInt(existingRow.find('.jumlah').val());
                const newJumlah = currentJumlah + 1;
                const newSubtotal = harga * newJumlah;

                existingRow.find('.jumlah').val(newJumlah);
                existingRow.find('.subtotal').text(`Rp ${newSubtotal}`);
            } else {
                tambahTransaksi(code, nama, harga, 1, harga); // Add new row
            }

            updateTotalHarga();
            $('#search-results').addClass('hidden'); // Hide search results after selection
            $('#search-barang').val(''); // Clear input field
        });

        // Function to add the product to the transaction table
        function tambahTransaksi(code, nama, harga, jumlah, subtotal) {
            $('#table-barang').append(`
        <tr data-code="${code}">
            <td class="px-4 py-2">${code}</td>
            <td class="px-4 py-2">${nama}</td>
            <td class="px-4 py-2 harga">Rp ${harga}</td>
            <td class="px-4 py-2"><input type="number" class="jumlah w-16 p-2 border rounded" value="${jumlah}" min="1"></td>
            <td class="px-4 py-2 subtotal">Rp ${subtotal}</td>
            <td class="px-4 py-2"><button class="hapus bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</button></td>
        </tr>
    `);
        }

        $('#bayar').on('input', updateKembalian);

        $(document).on('click', '.hapus', function() {
            $(this).closest('tr').remove();
            updateTotalHarga();
        });

        $(document).on('input', '.jumlah', function() {
            const row = $(this).closest('tr');
            const harga = parseInt(row.find('.harga').text().replace('Rp ', ''));
            const jumlah = parseInt($(this).val());
            const subtotal = harga * jumlah;

            row.find('.subtotal').text(`Rp ${subtotal}`);
            updateTotalHarga();
        });

        function tambahTransaksi(code, nama, harga, jumlah, subtotal) {
            $('#table-barang').append(`
                <tr data-code="${code}">
                    <td class="px-4 py-2">${code}</td>
                    <td class="px-4 py-2">${nama}</td>
                    <td class="px-4 py-2 harga">Rp ${harga}</td>
                    <td class="px-4 py-2"><input type="number" class="jumlah w-16 p-2 border rounded" value="${jumlah}" min="1"></td>
                    <td class="px-4 py-2 subtotal">Rp ${subtotal}</td>
                    <td class="px-4 py-2"><button class="hapus bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</button></td>
                </tr>
            `);
        }

        function updateTotalHarga() {
            let totalHarga = 0;
            $('#table-barang tr').each(function() {
                const subtotal = parseInt($(this).find('.subtotal').text().replace('Rp ', ''));
                totalHarga += subtotal;
            });
            $('#total-harga').text(`Rp ${totalHarga}`);
            updateKembalian();
        }

        function updateKembalian() {
            const bayar = parseInt($('#bayar').val()) || 0;
            const totalHarga = parseInt($('#total-harga').text().replace('Rp ', ''));
            const kembalian = bayar - totalHarga;

            $('#kembalian').text(kembalian >= 0 ? `Rp ${kembalian}` : 'Bayar tidak cukup!');
        }

        function simpanTransaksi() {
            const dataTransaksi = [];
            $('#table-barang tr').each(function() {
                const row = $(this);
                dataTransaksi.push({
                    code: row.data('code'),
                    nama: row.find('td:eq(1)').text(),
                    harga: parseInt(row.find('.harga').text().replace('Rp ', '')),
                    jumlah: parseInt(row.find('.jumlah').val()),
                    subtotal: parseInt(row.find('.subtotal').text().replace('Rp ', ''))
                });
            });

            if (dataTransaksi.length === 0) return alert('Tidak ada data transaksi');

            $.ajax({
                type: 'POST',
                url: '/simpantransaksi',
                data: {
                    dataTransaksi,
                    bayar: parseInt($('#bayar').val()) || 0,
                    totalHarga: parseInt($('#total-harga').text().replace('Rp ', '')),
                    kembalian: parseInt($('#kembalian').text().replace('Rp ', '') || 0)
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    window.open(`/transaksi/cetak-pdf/${response.transaksi_id}`, '_blank');
                    $('#bayar').val('');
                    $('#total-harga').text('Rp 0');
                    $('#kembalian').text('Rp 0');
                    $('#table-barang').empty();
                    alert('Transaksi berhasil disimpan');
                }
            });
        }
    </script>
</body>

</html>
