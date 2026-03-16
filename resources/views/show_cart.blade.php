@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index_product') }}" class="text-decoration-none text-muted">Products</a>
                        </li>
                        <li class="breadcrumb-item active">Cart</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <span class="text-muted small fw-semibold text-uppercase">Cart</span>
                    </div>

                    <div class="card-body p-4">

                        {{-- Error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger py-2 small mb-3">
                                @foreach ($errors->all() as $error)
                                    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Empty State --}}
                        @if ($carts->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                                <p class="mb-3">Keranjang kamu masih kosong.</p>
                                <a href="{{ route('index_product') }}" class="btn btn-dark btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Belanja Sekarang
                                </a>
                            </div>

                        @else
                            @php $total_price = 0; @endphp

                            {{-- Cart Items --}}
                            <div class="d-flex flex-column gap-3 mb-4">
                                @foreach ($carts as $cart)
                                    @php $subtotal = $cart->product->price * $cart->amount; @endphp
                                    @php $total_price += $subtotal; @endphp

                                    <div class="card border rounded-3 shadow-sm">
                                        <div class="card-body p-3">
                                            <div class="row g-3 align-items-center">

                                                {{-- Gambar --}}
                                                <div class="col-3 col-md-2">
                                                    <img src="{{ url('storage/' . $cart->product->image) }}"
                                                        alt="{{ $cart->product->name }}" class="img-fluid rounded-2 border"
                                                        style="height: 70px; width: 100%; object-fit: cover;">
                                                </div>

                                                {{-- Info --}}
                                                <div class="col-9 col-md-4">
                                                    <p class="fw-semibold mb-0 small">{{ $cart->product->name }}</p>
                                                    <p class="text-muted small mb-0">
                                                        Rp{{ number_format($cart->product->price, 0, ',', '.') }} / item
                                                    </p>
                                                </div>

                                                {{-- Update Amount --}}
                                                <div class="col-md-4">
                                                    <form action="{{ route('update_cart', $cart) }}" method="POST"
                                                        class="d-flex gap-2 align-items-center">
                                                        @method('patch')
                                                        @csrf
                                                        <div class="input-group" style="max-width: 140px;">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                onclick="changeQty(this, -1)">−</button>
                                                            <input type="number" name="amount" value="{{ $cart->amount }}" min="1"
                                                                max="{{ $cart->product->stock }}"
                                                                class="form-control form-control-sm text-center qty-input">
                                                            <button type="button" class="btn btn-outline-secondary btn-sm"
                                                                onclick="changeQty(this, 1)">+</button>
                                                        </div>
                                                        <button type="submit" class="btn btn-outline-dark btn-sm">
                                                            <i class="bi bi-arrow-clockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                                {{-- Subtotal + Delete --}}
                                                <div class="col-md-2 d-flex flex-column align-items-end gap-2">
                                                    <p class="fw-semibold small mb-0">
                                                        Rp{{ number_format($subtotal, 0, ',', '.') }}
                                                    </p>
                                                    <form action="{{ route('delete_cart', $cart) }}" method="POST">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Total & Checkout --}}
                            <div class="border-top pt-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-0">Total Pembayaran</p>
                                    <p class="fs-5 fw-semibold mb-0">Rp{{ number_format($total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('index_product') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-arrow-left me-1"></i>Lanjut Belanja
                                    </a>
                                    <form action="{{ route('checkout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-dark">
                                            <i class="bi bi-bag-check me-2"></i>Checkout
                                        </button>
                                    </form>
                                </div>
                            </div>

                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function changeQty(btn, delta) {
            const input = btn.closest('.input-group').querySelector('.qty-input');
            const max = parseInt(input.max);
            input.value = Math.min(max, Math.max(1, parseInt(input.value) + delta));
        }
    </script>
@endsection