@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-7">

                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('index_product') }}" class="text-decoration-none text-muted">Products</a>
                        </li>
                        <li class="breadcrumb-item active">Order Detail</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-semibold text-uppercase">Order Detail</span>
                        @if ($order->is_paid)
                            <span class="badge bg-success">Lunas</span>
                        @elseif ($order->payment_receipt)
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @else
                            <span class="badge bg-danger">Belum Bayar</span>
                        @endif
                    </div>

                    <div class="card-body p-4">

                        {{-- Info Order --}}
                        <p class="small text-muted mb-0">Order ID</p>
                        <p class="fw-semibold mb-1">#{{ $order->id }}</p>
                        <p class="small text-muted mb-3">oleh {{ $order->user->name }}</p>

                        <hr class="my-3">

                        {{-- Daftar Produk --}}
                        @php $total_price = 0; @endphp
                        <p class="small fw-semibold text-uppercase text-muted mb-2">Rincian Produk</p>
                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($order->transactions as $transaction)
                                @php
                                    $subtotal = $transaction->product->price * $transaction->amount;
                                    $total_price += $subtotal;
                                @endphp
                                <li class="list-group-item px-0 d-flex justify-content-between small">
                                    <span>{{ $transaction->product->name }} x{{ $transaction->amount }}</span>
                                    <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                            <li class="list-group-item px-0 d-flex justify-content-between fw-semibold">
                                <span>Total</span>
                                <span>Rp{{ number_format($total_price, 0, ',', '.') }}</span>
                            </li>
                        </ul>

                        {{-- QR Gambar (hanya tampil jika belum bayar & bukan admin) --}}
                        @if (!$order->is_paid && !Auth::user()->is_admin)
                            <hr class="my-3">
                            <p class="small fw-semibold text-uppercase text-muted mb-3">Scan & Bayar</p>
                            <div class="d-flex justify-content-center mb-2">
                                <img src="{{ url('storage/qris.jfif') }}" alt="QRIS GoPay" class="border rounded-3"
                                    style="width: 220px; height: 220px; object-fit: contain;">
                            </div>
                            <p class="text-center text-muted small mb-0">Scan QRIS dengan aplikasi apapun</p>
                            <p class="text-center small mt-1">
                                Nominal: <strong>Rp{{ number_format($total_price, 0, ',', '.') }}</strong>
                            </p>
                        @endif

                        {{-- Upload Bukti Bayar --}}
                        @if (!$order->is_paid && $order->payment_receipt == null && !Auth::user()->is_admin)
                            <hr class="my-3">
                            <p class="small fw-semibold text-uppercase text-muted mb-2">Upload Bukti Transfer</p>
                            <form action="{{ route('submit_payment_receipt', $order) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="file" name="payment_receipt" class="form-control mb-3" accept="image/*">
                                @if ($errors->any())
                                    <div class="alert alert-danger py-2 small mb-3">
                                        @foreach ($errors->all() as $error)
                                            <div>{{ $error }}</div>
                                        @endforeach
                                    </div>
                                @endif
                                <button type="submit" class="btn btn-dark w-100">
                                    <i class="bi bi-upload me-2"></i>Submit Bukti Pembayaran
                                </button>
                            </form>

                        @elseif (!$order->is_paid && $order->payment_receipt != null)
                            <hr class="my-3">
                            <div class="alert alert-warning py-2 small mb-0">
                                <i class="bi bi-clock-history me-1"></i>
                                Bukti pembayaran sudah dikirim, menunggu verifikasi admin.
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection 