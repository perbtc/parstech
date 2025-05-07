@extends('layouts.app')

@section('title', 'افزودن محصول جدید')

@section('content')
<link rel="stylesheet" href="{{ asset('css/product-create.css') }}">
<script src="{{ asset('js/product-create.js') }}" defer></script>

<div class="container product-add-container">
    <div class="product-add-card">
        <h2 class="add-product-title">افزودن محصول جدید</h2>

        @if(session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" id="product-create-form">
            @csrf

            <div class="form-section section-product-info">
                <div class="section-title" style="background:#e3f0ff;">اطلاعات پایه</div>
                <div class="row">
                    <div class="col-md-7">
                        <label class="form-label">نام محصول <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                        <label class="form-label mt-3">دسته‌بندی <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" @if(old('category_id')==$cat->id) selected @endif>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label d-flex align-items-center gap-2">
                            کد محصول
                            <span class="switch-label">قفل کد</span>
                            <label class="switch">
                                <input type="checkbox" id="code_lock" checked>
                                <span class="slider round"></span>
                            </label>
                        </label>
                        <input type="text" name="code" id="product_code" class="form-control" value="{{ $nextProductCode ?? 'Product-10001' }}" readonly required>
                        <small class="form-text text-muted">در صورت غیرفعال بودن قفل، می‌توانید کد دلخواه وارد کنید.</small>
                        <label class="form-label mt-3">تاریخ افزودن</label>
                        <input type="text" class="form-control" value="{{ jdate(now())->format('Y/m/d') }}" disabled>
                    </div>
                </div>
            </div>

            <div class="form-section section-brand">
                <div class="section-title" style="background:#e8f5e9;">برند</div>
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <label class="form-label">برند</label>
                        <select name="brand_id" class="form-control">
                            <option value="">بدون برند</option>
                            
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">تصویر برند</label>
                        <input type="file" name="brand_image" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="form-section section-media">
                <div class="section-title" style="background:#fffde7;">رسانه</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">تصویر اصلی محصول</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">گالری تصاویر</label>
                        <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label class="form-label">ویدیو محصول</label>
                        <input type="file" name="video" class="form-control" accept="video/mp4,video/x-m4v,video/*">
                    </div>
                </div>
            </div>

            <div class="form-section section-details">
                <div class="section-title" style="background:#f1f5f9;">جزئیات محصول</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">توضیح کوتاه</label>
                        <textarea name="short_desc" class="form-control" rows="2">{{ old('short_desc') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">توضیح کامل</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-section section-inventory">
                <div class="section-title" style="background:#ffe0e0;">انبار و موجودی</div>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">موجودی اولیه</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">حداقل موجودی هشدار</label>
                        <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">واحد اندازه‌گیری</label>
                        <select name="unit" class="form-control">
                            <option value="عدد" @if(old('unit')=='عدد') selected @endif>عدد</option>
                            <option value="کیلوگرم" @if(old('unit')=='کیلوگرم') selected @endif>کیلوگرم</option>
                            <option value="متر" @if(old('unit')=='متر') selected @endif>متر</option>
                            <option value="لیتر" @if(old('unit')=='لیتر') selected @endif>لیتر</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section section-extra">
                <div class="section-title" style="background:#e1f5fe;">سایر</div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">بارکد</label>
                        <input type="text" name="barcode" class="form-control" value="{{ old('barcode') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">وضعیت محصول</label><br>
                        <label class="switch">
                            <input type="checkbox" name="is_active" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="switch-label">فعال</span>
                    </div>
                </div>
            </div>

            <div class="form-section text-end mt-4">
                <button type="submit" class="btn btn-success btn-lg px-4">ثبت محصول</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-lg px-4">انصراف</a>
            </div>
        </form>
    </div>
</div>
@endsection
