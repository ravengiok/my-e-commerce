@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-sm rounded-4">

                    {{-- Header --}}
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <span class="text-muted small fw-semibold text-uppercase">Products</span>
                    </div>

                    {{-- Search & Filter --}}
                    <div class="px-4 py-3 border-bottom bg-white">
                        <form method="GET" action="{{ route('index_product') }}">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="bi bi-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                                            placeholder="Cari produk..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="sort" class="form-select" onchange="this.form.submit()">
                                        <option value="">Urutkan: Default</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga:
                                            Terendah</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                            Harga: Tertinggi</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama: A–Z
                                        </option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama:
                                            Z–A</option>
                                        <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok:
                                            Terbatas</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-dark w-100">Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Product Grid --}}
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse ($products as $product)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100 border rounded-3 shadow-sm">
                                        <img class="card-img-top rounded-top-3" src="{{ url('storage/' . $product->image) }}"
                                            alt="{{ $product->name }}" style="height: 160px; object-fit: cover;">
                                        <div class="card-body d-flex flex-column gap-2 p-3">
                                            <p class="card-title fw-semibold mb-0 small">{{ $product->name }}</p>
                                            <p class="text-muted small mb-0">Rp{{ number_format($product->price, 0, ',', '.') }}
                                            </p>

                                            @if ($product->stock <= 5)
                                                <span class="badge bg-warning text-dark" style="width:fit-content">Stok
                                                    {{ $product->stock }}</span>
                                            @else
                                                <span class="badge bg-success" style="width:fit-content">Tersedia</span>
                                            @endif

                                            <form action="{{ route('show_product', $product) }}" method="GET" class="mt-auto">
                                                <button type="submit" class="btn btn-dark btn-sm w-100">Show detail</button>
                                            </form>

                                            @if (Auth::check() && Auth::user()->is_admin)
                                                <form action="{{ route('delete_product', $product) }}" method="POST">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-outline-danger btn-sm w-100">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center text-muted py-5">
                                    <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                                    Produk tidak ditemukan.
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection