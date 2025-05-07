@extends('layouts.master')

@section('head')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.0/css/fixedColumns.dataTables.min.css"/>
    <link rel="stylesheet" href="{{ asset('css/products-list.css') }}">
@endsection

@section('content')
<div class="wrapper">
    <!-- سایدبار اختصاصی پروژه -->
    @include('layouts.sidebar')

    <!-- محتوای اصلی صفحه -->
    <div class="content-wrapper" style="min-height: 100vh;">
        <section class="content pt-4">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs" id="productTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#products-list" type="button" role="tab" aria-controls="products-list" aria-selected="true">
                                    لیست محصولات
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services-list" type="button" role="tab" aria-controls="services-list" aria-selected="false">
                                    لیست خدمات
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body tab-content" id="productTabsContent">
                        <div class="tab-pane fade show active" id="products-list" role="tabpanel" aria-labelledby="products-tab">
                            <div class="table-responsive">
                                <table id="productsTable" class="table table-bordered table-striped align-middle display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>کد محصول</th>
                                            <th>نام</th>
                                            <th>دسته</th>
                                            <th>برند</th>
                                            <th>قیمت فروش</th>
                                            <th>موجودی</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ ثبت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($products as $i => $item)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->category->name ?? '-' }}</td>
                                            <td>{{ $item->brand->name ?? '-' }}</td>
                                            <td>{{ number_format($item->sell_price) }} تومان</td>
                                            <td>{{ $item->stock }}</td>
                                            <td>
                                                @if($item->is_active)
                                                    <span class="badge bg-success">فعال</span>
                                                @else
                                                    <span class="badge bg-danger">غیرفعال</span>
                                                @endif
                                            </td>
                                            <td>{{ jdate($item->created_at)->format('Y/m/d') }}</td>
                                            <td>
                                                <a href="{{ route('products.show', $item->id) }}" class="btn btn-sm btn-info">نمایش</a>
                                                <a href="{{ route('products.edit', $item->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="services-list" role="tabpanel" aria-labelledby="services-tab">
                            <div class="table-responsive">
                                <table id="servicesTable" class="table table-bordered table-striped align-middle display nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ردیف</th>
                                            <th>کد خدمت</th>
                                            <th>نام</th>
                                            <th>دسته</th>
                                            <th>قیمت</th>
                                            <th>وضعیت</th>
                                            <th>تاریخ ثبت</th>
                                            <th>عملیات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $i => $item)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->category->name ?? '-' }}</td>
                                            <td>{{ number_format($item->price) }} تومان</td>
                                            <td>
                                                @if($item->is_active)
                                                    <span class="badge bg-success">فعال</span>
                                                @else
                                                    <span class="badge bg-danger">غیرفعال</span>
                                                @endif
                                            </td>
                                            <td>{{ jdate($item->created_at)->format('Y/m/d') }}</td>
                                            <td>
                                                <a href="{{ route('services.show', $item->id) }}" class="btn btn-sm btn-info">نمایش</a>
                                                <a href="{{ route('services.edit', $item->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
    <!-- حتماً اول jQuery بیاد -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="{{ asset('js/products-list.js') }}"></script>
@endsection
