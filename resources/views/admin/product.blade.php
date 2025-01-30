@extends('layouts.templateAdmin')

@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Container Fluid-->
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Simple Tables</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="./">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active" aria-current="page">Simple Tables</li>
            </ol>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#TambahProduk">
                Tambah produk
            </button>
        </div>

        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Simple Tables -->
                <div class="card">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tabel Produk</h6>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
        <!--Row-->
        <div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-full">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">No</th>
                            <th class="text-center">Code</th>
                            <th class="text-center">Nama Produk</th>
                            <th class="text-center">Harga Produk</th>
                            <th class="text-center">Stok Produk</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($produks as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + $produks->firstItem() - 1 }}</td>
                                <td class="text-center">{{ $item->code }}</td>
                                <td class="text-center">{{ $item->nama }}</td>
                                <td class="text-center">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td class="text-center">{{ $item->stok }} PCS</td>
                                <td class="align-middle text-center">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#editProdukModal{{ $item->id }}">
                                        Update
                                    </button>
                                    <form action="{{ route('produks.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination Links -->
                <div class="d-flex justify-content-center mt-5">
                    {{ $produks->appends(request()->except('page'))->links('vendor.pagination.bootstrap-4') }}
                </div>
            </div>
            {{-- Add Product Modal --}}
            <div class="modal fade" id="TambahProduk" tabindex="-1" role="dialog" aria-labelledby="select2ModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="select2ModalLabel">Tambah Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('produks.store') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control" id="harga" name="harga" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" class="form-control" id="stok" name="stok" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal edit -->
        @foreach ($produks as $item)
            <div class="modal fade" id="editProdukModal{{ $item->id }}" tabindex="-1"
                aria-labelledby="editBarangModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBarangModalLabel{{ $item->id }}">Edit Produk</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('produks.update', $item->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-3">
                                    <label for="code_{{ $item->id }}">Code Produk</label>
                                    <input type="text" id="code_{{ $item->id }}" name="code"
                                        class="form-control @error('code') is-invalid @enderror"
                                        value="{{ old('code', $item->code) }}">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="nama_{{ $item->id }}">Nama Produk</label>
                                    <input type="text" id="nama_{{ $item->id }}" name="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $item->nama) }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="harga_{{ $item->id }}">Harga Produk</label>
                                    <input type="number" id="harga_{{ $item->id }}" name="harga"
                                        class="form-control @error('harga') is-invalid @enderror"
                                        value="{{ old('harga', $item->harga) }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="stok_{{ $item->id }}">stok Produk</label>
                                    <input type="number" id="stok_{{ $item->id }}" name="stok"
                                        class="form-control @error('stok') is-invalid @enderror"
                                        value="{{ old('stok', $item->stok) }}" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!---Container Fluid-->
@endsection
