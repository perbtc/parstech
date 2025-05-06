@extends('layouts.app')

@section('title', 'ایجاد دسته‌بندی جدید')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">ایجاد دسته‌بندی جدید</h5>
        </div>
        <div class="card-body">

            {{-- دکمه‌های تب‌بندی --}}
            <div class="mb-4 d-flex justify-content-center">
                <button type="button" class="btn btn-outline-primary mx-2" id="btn-person" onclick="showTab('person')">دسته‌بندی اشخاص</button>
                <button type="button" class="btn btn-outline-success mx-2" id="btn-product" onclick="showTab('product')">دسته‌بندی کالا</button>
                <button type="button" class="btn btn-outline-warning mx-2" id="btn-service" onclick="showTab('service')">دسته‌بندی خدمات</button>
            </div>

            {{-- فرم دسته‌بندی اشخاص --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-person" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="person">
                <div class="mb-3 text-center">
                    <img id="img-person" src="{{ asset('img/category-person.png') }}" alt="پیش‌فرض اشخاص" class="img-thumbnail" style="max-width: 150px;">
                </div>
                <div class="mb-3">
                    <label for="person_name" class="form-label">نام دسته‌بندی اشخاص</label>
                    <input type="text" name="name" id="person_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="person_code" class="form-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="person_code" class="form-control" value="per1001" readonly>
                </div>
                <div class="mb-3">
                    <label for="person_image" class="form-label">تصویر (اختیاری)</label>
                    <input type="file" name="image" id="person_image" class="form-control" accept="image/*" onchange="previewImage(this, 'img-person')">
                </div>
                <div class="mb-3">
                    <label for="person_description" class="form-label">توضیحات</label>
                    <textarea name="description" id="person_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-primary">ثبت دسته‌بندی اشخاص</button>
                </div>
            </form>

            {{-- فرم دسته‌بندی کالا --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-product" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="product">
                <div class="mb-3 text-center">
                    <img id="img-product" src="{{ asset('img/category-product.png') }}" alt="پیش‌فرض کالا" class="img-thumbnail" style="max-width: 150px;">
                </div>
                <div class="mb-3">
                    <label for="product_name" class="form-label">نام دسته‌بندی کالا</label>
                    <input type="text" name="name" id="product_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="product_code" class="form-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="product_code" class="form-control" value="pro1001" readonly>
                </div>
                <div class="mb-3">
                    <label for="product_image" class="form-label">تصویر (اختیاری)</label>
                    <input type="file" name="image" id="product_image" class="form-control" accept="image/*" onchange="previewImage(this, 'img-product')">
                </div>
                <div class="mb-3">
                    <label for="product_description" class="form-label">توضیحات</label>
                    <textarea name="description" id="product_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-success">ثبت دسته‌بندی کالا</button>
                </div>
            </form>

            {{-- فرم دسته‌بندی خدمات --}}
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data" id="form-service" style="display: none;">
                @csrf
                <input type="hidden" name="category_type" value="service">
                <div class="mb-3 text-center">
                    <img id="img-service" src="{{ asset('img/category-service.png') }}" alt="پیش‌فرض خدمات" class="img-thumbnail" style="max-width: 150px;">
                </div>
                <div class="mb-3">
                    <label for="service_name" class="form-label">نام دسته‌بندی خدمات</label>
                    <input type="text" name="name" id="service_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="service_code" class="form-label">کد دسته‌بندی</label>
                    <input type="text" name="code" id="service_code" class="form-control" value="ser1001" readonly>
                </div>
                <div class="mb-3">
                    <label for="service_image" class="form-label">تصویر (اختیاری)</label>
                    <input type="file" name="image" id="service_image" class="form-control" accept="image/*" onchange="previewImage(this, 'img-service')">
                </div>
                <div class="mb-3">
                    <label for="service_description" class="form-label">توضیحات</label>
                    <textarea name="description" id="service_description" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3 text-end">
                    <button type="submit" class="btn btn-warning">ثبت دسته‌بندی خدمات</button>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- اسکریپت برای نمایش فرم مربوط به هر تب و پیش‌نمایش تصویر --}}
<script>
    function showTab(type) {
        document.getElementById('form-person').style.display = (type === 'person') ? 'block' : 'none';
        document.getElementById('form-product').style.display = (type === 'product') ? 'block' : 'none';
        document.getElementById('form-service').style.display = (type === 'service') ? 'block' : 'none';

        document.getElementById('btn-person').classList.toggle('active', type === 'person');
        document.getElementById('btn-product').classList.toggle('active', type === 'product');
        document.getElementById('btn-service').classList.toggle('active', type === 'service');
    }

    // پیش فرض: نمایش فرم کالا
    document.addEventListener("DOMContentLoaded", function() {
        showTab('product');
    });

    function previewImage(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById(imgId).src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
