@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-9">

                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index_product') }}" class="text-decoration-none text-muted">Products</a>
                        </li>
                        <li class="breadcrumb-item active text-dark">{{ $product->name }}</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <span class="text-muted small fw-semibold text-uppercase">Product Detail</span>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4 align-items-start">

                            {{-- Gambar --}}
                            <div class="col-md-5">
                                <img src="{{ url('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="img-fluid rounded-3 border w-100" style="max-height: 320px; object-fit: cover;">
                            </div>

                            {{-- Info --}}
                            <div class="col-md-7">

                                {{-- Badge stok --}}
                                @if ($product->stock == 0)
                                    <span class="badge bg-danger mb-2">Habis</span>
                                @elseif ($product->stock <= 5)
                                    <span class="badge bg-warning text-dark mb-2">Stok Terbatas</span>
                                @else
                                    <span class="badge bg-success mb-2">Tersedia</span>
                                @endif

                                <h2 class="fw-semibold mb-1">{{ $product->name }}</h2>
                                <p class="text-muted mb-3">{{ $product->description }}</p>
                                <h3 class="fw-semibold mb-1">Rp{{ number_format($product->price, 0, ',', '.') }}</h3>

                                <hr class="my-3">

                                <p class="text-muted small mb-3">
                                    <i class="bi bi-box-seam me-1"></i>
                                    {{ $product->stock }} item tersisa
                                </p>

                                {{-- Error --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger py-2 small mb-3">
                                        @foreach ($errors->all() as $error)
                                            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Buyer: Add to Cart --}}
                                @if (!Auth::user()->is_admin)
                                    <form action="{{ route('add_to_cart', $product) }}" method="POST">
                                        @csrf
                                        <label class="form-label small text-muted">Jumlah</label>
                                        <div class="input-group mb-3" style="max-width: 160px;">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="changeQty(-1)">−</button>
                                            <input type="number" id="qtyInput" name="amount" class="form-control text-center"
                                                value="1" min="1" max="{{ $product->stock }}">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="changeQty(1)">+</button>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-dark px-4" {{ $product->stock == 0 ? 'disabled' : '' }}>
                                                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                                            </button>
                                            <a href="{{ route('index_product') }}" class="btn btn-outline-secondary">
                                                <i class="bi bi-arrow-left"></i>
                                            </a>
                                        </div>
                                    </form>

                                    {{-- Admin: Edit --}}
                                @else
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('edit_product', $product) }}" method="GET">
                                            <button type="submit" class="btn btn-dark px-4">
                                                <i class="bi bi-pencil me-2"></i>Edit Product
                                            </button>
                                        </form>
                                        <a href="{{ route('index_product') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-arrow-left"></i>
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function changeQty(delta) {
            const input = document.getElementById('qtyInput');
            input.value = Math.min(parseInt(input.max), Math.max(1, parseInt(input.value) + delta));
        }
    </script>
@endsection