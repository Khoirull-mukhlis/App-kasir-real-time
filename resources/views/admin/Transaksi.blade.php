@extends('layouts.templateAdmin')

@section('content')
    <div class="container">
        <h1>Semua Transaksi</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th class="text-center">Total Harga</th>
                    <th class="text-center">Bayar</th>
                    <th class="text-center">Kembalian</th>
                    <th class="text-center">Detail Transaksi</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi as $item)
                    <tr>
                        <td class="text-center">{{ $item->id }}</td>
                        <td class="text-center">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                        <td class="text-center">Rp {{ number_format($item->bayar, 0, ',', '.') }}</td>
                        <td class="text-center">Rp {{ number_format($item->kembalian, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @php
                                $detailTransaksi = json_decode($item->detail_transaksi, true);
                            @endphp
                            <ul style="list-style-type: none; padding-left: 0;">
                                @if (is_array($detailTransaksi))
                                    @foreach ($detailTransaksi as $detail)
                                        <li>
                                            <strong>Nama:</strong> {{ $detail['nama'] }},
                                            <strong>Harga:</strong> Rp {{ number_format($detail['harga'], 0, ',', '.') }},
                                            <strong>Jumlah:</strong> {{ $detail['jumlah'] }}
                                        </li>
                                    @endforeach
                                @else
                                    <li>Detail tidak tersedia</li>
                                @endif
                            </ul>
                        </td>
                        <td class="text-center">{{ $item->created_at }}</td>
                        <td class="text-center">
                            <form action="{{ route('transaksi.hapus', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
