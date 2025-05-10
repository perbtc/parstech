@extends('layouts.app')

@section('title', 'صدور فاکتور جدید')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('css/invoice-create.css') }}">
@endpush
@section('content')
<div class="page-wrapper">
    <div class="sidebar">
        <!-- محتوای سایدبار اینجا -->
        <h3>منو</h3>
        <nav>
            <ul>
                <li><a href="{{ route('dashboard') }}">داشبورد</a></li>
                <li><a href="{{ route('invoices.index') }}">لیست فاکتورها</a></li>
                <li><a href="{{ route('products.index') }}">محصولات</a></li>
                <li><a href="{{ route('persons.customers') }}">مشتریان</a></li>
                <!-- سایر آیتم‌های منو -->
            </ul>
        </nav>
    </div>
    <div class="main-content">
<div class="container py-4">
    <div class="invoice-header">
        <h2 class="mb-4">صدور فاکتور جدید</h2>

        <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm" autocomplete="off">
            @csrf

            <div class="row">
                <!-- شماره فاکتور -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required-field" for="number">شماره فاکتور</label>
                        <div class="input-group">
                            <input type="text" name="number" id="number" class="form-control"
                                value="{{ old('number') }}" readonly required>
                            <div class="input-group-append ms-2">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" id="autoNumberSwitch" name="auto_number" value="1" checked>
                                    <label class="form-check-label small" for="autoNumberSwitch">شماره اتوماتیک</label>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            اگر فعال باشد شماره فاکتور اتوماتیک تولید می‌شود و قابل ویرایش نیست.
                        </small>
                    </div>
                </div>

                <!-- ارجاع -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="reference">ارجاع</label>
                        <input type="text" id="reference" name="reference"
                               class="form-control" placeholder="شماره ارجاع را وارد کنید" value="{{ old('reference') }}">
                        @error('reference')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- تاریخ -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label required-field">تاریخ صدور فاکتور</label>
                    <input type="text" name="date" id="date" class="form-control datepicker"
                        value="{{ old('date', jdate()->format('Y-m-d')) }}" required autocomplete="off">
                    <small class="form-text text-muted">تاریخ صدور فاکتور (ثبت فاکتور)</small>
                </div>
            </div>

                <!-- تاریخ سررسید -->
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label required-field">تاریخ سررسید</label>
                    <input type="text" name="dueDate" id="dueDate" class="form-control datepicker"
                        value="{{ old('dueDate') }}" required autocomplete="off">
                    <small class="form-text text-muted">موعد پرداخت فاکتور</small>
                </div>
            </div>
        </div>

                <!-- مشتری -->

        {{-- مشتری --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label required-field" for="customer_select">مشتری</label>
                    <select id="customer_select" name="customer_id" class="form-control" required>
                        <option value="">انتخاب مشتری...</option>
                        {{-- گزینه‌ها با آژاکس از persons لود می‌شود --}}
                    </select>
                </div>
            </div>
        </div>

                <!-- واحد پول -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="currency">واحد پول</label>
                        <select id="currency" name="currency" class="form-control">
                            <option value="IRR" @selected(old('currency', 'IRR') == 'IRR')>ریال ایران</option>
                            <option value="TOMAN" @selected(old('currency') == 'TOMAN')>تومان</option>
                        </select>
                    </div>
                </div>

                <!-- فروشنده -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="seller">فروشنده</label>
                        <select id="seller" name="seller" class="form-control select2">
                            @if(old('seller'))
                                <option value="{{ old('seller') }}" selected>فروشنده انتخاب شده قبلی</option>
                            @endif
                        </select>
                        @error('seller')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <!-- درصد تخفیف -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="discount">درصد تخفیف (%)</label>
                        <input type="number" id="discount" name="discount"
                               class="form-control" step="0.01" min="0" max="100" value="{{ old('discount', 0) }}">
                        @error('discount')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- جستجوی محصولات -->
            <div class="product-search-container">
                <label for="productSearch">جستجوی محصولات</label>
                <input type="text" id="productSearch" class="form-control"
                       placeholder="نام یا کد محصول را وارد کنید...">
                <div id="productList" class="product-list mt-2" style="display: none;"></div>
            </div>

            <!-- جدول محصولات انتخاب شده -->
            <div class="table-responsive">
                <table class="table table-bordered selected-products-table">
                    <thead>
                        <tr>
                            <th>تصویر</th>
                            <th>کد کالا</th>
                            <th>نام محصول</th>
                            <th>دسته‌بندی</th>
                            <th>موجودی</th>
                            <th>قیمت (ریال)</th>
                            <th>تعداد</th>
                            <th>مجموع</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody id="selectedProducts"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">جمع کل:</td>
                            <td colspan="2"><span id="totalAmount">0</span> ریال</td>
                        </tr>
                        <tr>
                            <td colspan="7">تخفیف:</td>
                            <td colspan="2"><span id="discountAmount">0</span> ریال</td>
                        </tr>
                        <tr>
                            <td colspan="7">مبلغ نهایی:</td>
                            <td colspan="2"><strong><span id="finalAmount">0</span> ریال</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-left mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save ml-2"></i>
                    ثبت فاکتور
                </button>
            </div>
        </form>
    </div>
</div>
    </div><!-- پایان main-content -->
</div><!-- پایان page-wrapper -->
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/invoice-create.js') }}"></script>
@endpush
