@extends('layouts.app')

@section('title', 'صدور فاکتور جدید')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">صدور فاکتور جدید</h2>

    <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
        @csrf
        <!-- اطلاعات اولیه فاکتور -->
        <div class="row">
            <div class="form-group row align-items-center">
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
            <div class="col-md-4 mb-3">
                <label for="reference">ارجاع</label>
                <input type="text" id="reference" name="reference" class="form-control">
            </div>
            <div class="form-group">
                <label for="date">تاریخ</label>
                <input type="text" id="date" name="date" class="form-control" required autocomplete="off">
            </div>
            <div class="col-md-4 mb-3">
                <label for="dueDate">تاریخ سررسید</label>
                <input type="date" id="dueDate" name="dueDate" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label for="customer">مشتری</label>
                <input type="text" id="customer" name="customer" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="currency">واحد پول</label>
                <select id="currency" name="currency" class="form-control">
                    <option value="IRR">ریال ایران</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="seller">فروشنده</label>
                <input type="text" id="seller" name="seller" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
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

<script>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function() {
        $('#date').persianDatepicker({
            initialValue: true,
            initialValueType: 'persian',
            format: 'YYYY/MM/DD',
            autoClose: true,
        });
    });

    // جستجوی محصولات با AJAX
    document.getElementById('productSearch').addEventListener('input', function () {
        let query = this.value;
        if (query.length < 2) return;
        fetch(`/api/products?search=${query}`)
            .then(response => response.json())
            .then(data => {
                let productList = document.getElementById('productList');
                productList.innerHTML = '';
                data.forEach(product => {
                    let li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.innerHTML = `
                        <img src="${product.image}" alt="${product.name}" width="50">
                        ${product.name} - ${product.code} (${product.category})
                        <button class="btn btn-primary btn-sm float-right" onclick="addProduct(${JSON.stringify(product)})">افزودن</button>
                    `;
                    productList.appendChild(li);
                });
            });
    });

    // اضافه کردن محصول به لیست انتخاب‌شده‌ها
    function addProduct(product) {
        let selectedProducts = document.getElementById('selectedProducts');
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td><img src="${product.image}" alt="${product.name}" width="50"></td>
            <td>${product.code}</td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td class="${product.stock < 10 ? 'text-danger' : ''}">${product.stock}</td>
            <td><input type="number" class="form-control" name="products[${product.id}][price]" value="${product.price}" step="0.01"></td>
            <td><input type="number" class="form-control" name="products[${product.id}][quantity]" value="1" min="1"></td>
            <td class="total">${product.price}</td>
            <td><button class="btn btn-danger btn-sm" onclick="this.closest('tr').remove()">حذف</button></td>
        `;
        selectedProducts.appendChild(tr);
    }

    // محاسبه مجموع هر محصول
    document.addEventListener('input', function (event) {
        if (event.target.closest('tr') && event.target.name.includes('quantity')) {
            let row = event.target.closest('tr');
            let price = parseFloat(row.querySelector('input[name$="[price]"]').value);
            let quantity = parseInt(event.target.value);
            row.querySelector('.total').innerText = (price * quantity).toFixed(2);
        }
    });

    function toggleInvoiceNumber() {
    const checkbox = document.getElementById('invoiceNumberSwitch');
    const input = document.getElementById('invoiceNumber');
    if(checkbox.checked) {
        input.removeAttribute('readonly');
        input.value = '';
        input.focus();
    } else {
        input.setAttribute('readonly', true);
        input.value = '{{ $nextInvoiceNumber ?? '' }}'; // مقدار پیش‌فرض از سرور
    }
}
</script>
@endsection
