@extends('layouts.master')
@section('title', 'لیست محصولات')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-index.css') }}">
@endsection
@section('scripts')
    <script src="{{ asset('js/products-index.js') }}"></script>
@endsection

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">لیست محصولات</h4>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>تصویر</th>
                            <th>کد محصول</th>
                            <th>نام محصول</th>
                            <th>دسته‌بندی</th>
                            <th>برند</th>
                            <th>قیمت فروش</th>
                            <th>موجودی</th>
                            <th>بارکد</th>
                            <th>ویدیو</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="تصویر" style="width: 60px; height: 60px; object-fit:cover; border-radius:10px;">
                                @else
                                    <span class="text-muted">ندارد</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="fw-bold text-primary">
                                    {{ $product->code }}
                                </a>
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category?->name ?? '-' }}</td>
                            <td>{{ $product->brand?->name ?? '-' }}</td>
                            <td>{{ number_format($product->sell_price) }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->barcode }}</td>
                            <td>
                                @if($product->video)
                                    <video src="{{ asset('storage/'.$product->video) }}" controls style="width:60px; height:40px;"></video>
                                @else
                                    <span class="text-muted">ندارد</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-info">نمایش</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
