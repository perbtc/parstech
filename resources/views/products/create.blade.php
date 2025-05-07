@extends('layouts.master')

@section('title', 'افزودن محصول جدید')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">افزودن محصول جدید</h2>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="product-form">
        @csrf
        <!-- اطلاعات پایه -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">اطلاعات پایه</div>
            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">نام محصول <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">دسته‌بندی کالا <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-control" required>
                        <option value="">انتخاب کنید...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if(old('category_id')==$cat->id) selected @endif>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- برند -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                برند
                <a href="{{ route('brands.create') }}" target="_blank" class="btn btn-light btn-sm">برند جدید</a>
            </div>
            <div class="card-body row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">انتخاب برند</label>
                    <select name="brand_id" class="form-control">
                        <option value="">بدون برند</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" @if(old('brand_id')==$brand->id) selected @endif>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">تصویر برند</label>
                    <input type="file" name="brand_image" class="form-control" accept="image/*">
                </div>
            </div>
        </div>

        <!-- رسانه (تصویر و ویدیو) -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">تصاویر و ویدیو</div>
            <div class="card-body">
                <form action="{{ route('products.upload') }}" class="dropzone" id="product-dropzone">
                    @csrf
                    <div class="dz-message">تصاویر و ویدیوهای محصول را اینجا بکشید و رها کنید یا کلیک کنید</div>
                </form>
            </div>
        </div>

        <!-- انبار و موجودی  -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">انبار و موجودی</div>
            <div class="card-body row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">موجودی اولیه</label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">حداقل موجودی هشدار</label>
                    <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 0) }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">قیمت خرید</label>
                    <input type="number" name="buy_price" class="form-control" value="{{ old('buy_price') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">توضیح خرید</label>
                    <input type="text" name="buy_description" class="form-control" value="{{ old('buy_description') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">قیمت فروش</label>
                    <input type="number" name="sell_price" class="form-control" value="{{ old('sell_price') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">توضیح فروش</label>
                    <input type="text" name="sell_description" class="form-control" value="{{ old('sell_description') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">محل خرید / سایت خرید</label>
                    <input type="text" name="buy_location" class="form-control" value="{{ old('buy_location') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">شماره موبایل</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">شماره تلفن</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">واحد اندازه‌گیری</label>
                    <button type="button" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#unitModal">انتخاب واحد</button>
                    <input type="hidden" name="unit" id="selected-unit" value="{{ old('unit') }}">
                    <span id="unit-selected-view" class="d-block mt-2"></span>
                </div>
            </div>
        </div>

        <!-- سایر -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">سایر</div>
            <div class="card-body row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">بارکد محصول</label>
                    <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
                    <button type="button" class="btn btn-outline-primary mt-2" onclick="generateBarcode('barcode')">ساخت بارکد</button>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">بارکد محصول (فروشگاه)</label>
                    <input type="text" name="store_barcode" class="form-control" value="{{ old('store_barcode') }}">
                    <button type="button" class="btn btn-outline-secondary mt-2" onclick="generateBarcode('store_barcode')">ساخت بارکد فروشگاه</button>
                </div>
            </div>
        </div>

        <!-- دکمه ثبت -->
        <div class="text-end">
            <button type="submit" class="btn btn-success btn-lg px-4">ثبت محصول</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg px-4">انصراف</a>
        </div>
    </form>
</div>

<!-- Modal: انتخاب واحد اندازه‌گیری -->
<div class="modal fade" id="unitModal" tabindex="-1" aria-labelledby="unitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">واحدهای اندازه‌گیری</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="units-list">
                    @foreach($units as $unit)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $unit->title }}</span>
                            <div>
                                <button type="button" class="btn btn-sm btn-success select-unit-btn" data-unit="{{ $unit->title }}">انتخاب</button>
                                <button type="button" class="btn btn-sm btn-warning edit-unit-btn" data-id="{{ $unit->id }}">ویرایش</button>
                                <button type="button" class="btn btn-sm btn-danger delete-unit-btn" data-id="{{ $unit->id }}">حذف</button>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <hr>
                <form id="add-unit-form" class="d-flex gap-2">
                    <input type="text" class="form-control" id="unit-title" placeholder="افزودن واحد جدید">
                    <button type="submit" class="btn btn-primary">افزودن</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Dropzone CSS/JS + Bootstrap Modal -->
<link rel="stylesheet" href="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone.css" />
<script src="https://unpkg.com/dropzone@6.0.0-beta.2/dist/dropzone-min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@section('scripts')
<script>
    // Dropzone تنظیمات
    Dropzone.options.productDropzone = {
        paramName: "file",
        maxFilesize: 5,
        acceptedFiles: "image/*,video/*",
        maxFiles: 10,
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
        dictDefaultMessage: "تصاویر و ویدیوهای محصول را اینجا بکشید و رها کنید یا کلیک کنید",
        success: function(file, response) {
            // response را ذخیره کن یا نمایش بده
        }
    };

    // انتخاب واحد اندازه‌گیری
    document.querySelectorAll('.select-unit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const unit = this.getAttribute('data-unit');
            document.getElementById('selected-unit').value = unit;
            document.getElementById('unit-selected-view').textContent = 'واحد انتخاب شده: ' + unit;
            bootstrap.Modal.getInstance(document.getElementById('unitModal')).hide();
        });
    });

    // افزودن واحد جدید (نمونه ساده، AJAX باید سمت سرور اضافه شود)
    document.getElementById('add-unit-form').addEventListener('submit', function (e){
        e.preventDefault();
        // AJAX برای ذخیره واحد جدید...
        alert('واحد جدید اضافه شد (نمونه)');
    });

    // ساخت بارکد (نمونه ساده)
    function generateBarcode(field) {
        document.querySelector('input[name="'+field+'"]').value = 'BARCODE-' + Math.floor(Math.random()*1000000);
    }
</script>
@endsection

@endsection
