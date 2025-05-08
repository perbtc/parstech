@extends('layouts.app')

@section('title', 'صدور فاکتور جدید')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">صدور فاکتور جدید</h2>

    <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
        @csrf
        <!-- اطلاعات اولیه فاکتور -->
        <div class="row">
            <div class="form-group row align-items-center col-md-12">
                <label class="col-md-2 col-form-label">شماره فاکتور</label>
                <div class="col-md-7">
                    <input type="text" id="invoiceNumber" name="invoiceNumber" class="form-control" value="{{ $nextInvoiceNumber ?? '' }}" readonly>
                </div>
                <div class="col-md-3">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="invoiceNumberSwitch" onchange="toggleInvoiceNumber()">
                        <label class="custom-control-label" for="invoiceNumberSwitch">شماره‌گذاری دستی</label>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="reference">ارجاع</label>
                <input type="text" id="reference" name="reference" class="form-control">
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="date">تاریخ</label>
                <input type="text" id="date" name="date" class="form-control" required autocomplete="off">
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="dueDate">تاریخ سررسید</label>
                <input type="date" id="dueDate" name="dueDate" class="form-control">
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="customer">مشتری</label>
                <input type="text" id="customer" name="customer" class="form-control" required>
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="currency">واحد پول</label>
                <select id="currency" name="currency" class="form-control">
                    <option value="IRR">ریال ایران</option>
                </select>
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="seller">فروشنده</label>
                <input type="text" id="seller" name="seller" class="form-control">
            </div>
            <div class="form-group col-md-4 mb-3">
                <label for="discount">درصد تخفیف (%)</label>
                <input type="number" id="discount" name="discount" class="form-control" step="0.01">
            </div>
        </div>

        <!-- لیست محصولات -->
        <div class="mb-4">
            <label for="productSearch">انتخاب محصولات</label>
            <input type="text" id="productSearch" class="form-control" placeholder="جستجوی محصول...">
            <ul id="productList" class="list-group mt-2"></ul>
        </div>

        <!-- محصولات انتخاب شده -->
        <div class="mb-4">
            <h4>محصولات انتخاب‌شده</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>تصویر</th>
                        <th>کد کالا</th>
                        <th>نام محصول</th>
                        <th>دسته‌بندی</th>
                        <th>موجودی</th>
                        <th>قیمت (اعشاری)</th>
                        <th>تعداد</th>
                        <th>مجموع</th>
                        <th>حذف</th>
                    </tr>
                </thead>
                <tbody id="selectedProducts"></tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success">ثبت فاکتور</button>
    </form>
</div>

<!-- استایل و اسکریپت‌های مورد نیاز -->
<!-- همیشه ابتدا jQuery، بعد persian-date، بعد persian-datepicker لود شود -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<!-- اسکریپت اختصاصی صفحه -->
<script>
    $(document).ready(function() {
        // فعال‌سازی تاریخ شمسی
        $('#date').persianDatepicker({
            initialValue: true,
            initialValueType: 'persian',
            format: 'YYYY/MM/DD',
            autoClose: true,
        });

        // جستجوی محصولات با AJAX
        $('#productSearch').on('input', function () {
            let query = $(this).val();
            if (query.length < 2) return;
            fetch(`/api/products?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let productList = $('#productList');
                    productList.empty();
                    data.forEach(product => {
                        let li = $('<li>').addClass('list-group-item');
                        li.html(`
                            <img src="${product.image}" alt="${product.name}" width="50">
                            ${product.name} - ${product.code} (${product.category})
                            <button type="button" class="btn btn-primary btn-sm float-right add-product-btn">افزودن</button>
                        `);
                        li.find('.add-product-btn').on('click', function () {
                            addProduct(product);
                        });
                        productList.append(li);
                    });
                });
        });

        // محاسبه مجموع هر محصول
        $('#selectedProducts').on('input', 'input[name*="[quantity]"], input[name*="[price]"]', function () {
            let row = $(this).closest('tr');
            let price = parseFloat(row.find('input[name$="[price]"]').val()) || 0;
            let quantity = parseInt(row.find('input[name$="[quantity]"]').val()) || 1;
            row.find('.total').text((price * quantity).toFixed(2));
        });

        // حذف ردیف محصول
        $('#selectedProducts').on('click', '.remove-product-btn', function () {
            $(this).closest('tr').remove();
        });
    });

    // اضافه کردن محصول به جدول انتخاب‌شده‌ها
    function addProduct(product) {
        // جلوگیری از تکرار
        if ($('#selectedProducts').find(`input[name="products[${product.id}][price]"]`).length) {
            alert('این محصول قبلاً اضافه شده است.');
            return;
        }
        let tr = $(`
            <tr>
                <td><img src="${product.image}" alt="${product.name}" width="50"></td>
                <td>${product.code}</td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td class="${product.stock < 10 ? 'text-danger' : ''}">${product.stock}</td>
                <td><input type="number" class="form-control" name="products[${product.id}][price]" value="${product.price}" step="0.01"></td>
                <td><input type="number" class="form-control" name="products[${product.id}][quantity]" value="1" min="1"></td>
                <td class="total">${product.price}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product-btn">حذف</button></td>
            </tr>
        `);
        $('#selectedProducts').append(tr);
    }

    // فعال/غیرفعال کردن شماره فاکتور دستی
    function toggleInvoiceNumber() {
        const checkbox = document.getElementById('invoiceNumberSwitch');
        const input = document.getElementById('invoiceNumber');
        if(checkbox.checked) {
            input.removeAttribute('readonly');
            input.value = '';
            input.focus();
        } else {
            input.setAttribute('readonly', true);
            input.value = '{{ $nextInvoiceNumber ?? '' }}';
        }
    }
</script>
@endsection
