document.addEventListener('DOMContentLoaded', function() {
    // تنظیمات Select2
    initializeSelect2();

    // تنظیمات تاریخ شمسی
    initializeDatePickers();

    // مدیریت شماره فاکتور
    initializeInvoiceNumberToggle();

    // مدیریت جستجوی محصولات
    initializeProductSearch();

    // مدیریت محاسبات فاکتور
    function initializeInvoiceCalculations() {
    console.log("Invoice calculations initialized");
    // کدهای مربوط به محاسبات فاکتور را اینجا بنویسید
}
});
function toggleInvoiceNumber() {
    const $invoiceNumber = document.getElementById('invoiceNumber');
    const isManual = document.getElementById('invoiceNumberSwitch').checked;

    if (isManual) {
        // فعال کردن حالت دستی
        $invoiceNumber.readOnly = false;
        $invoiceNumber.value = ''; // خالی کردن شماره فاکتور برای وارد کردن دستی
        $invoiceNumber.focus();
    } else {
        // غیرفعال کردن حالت دستی و بازگرداندن مقدار پیش‌فرض
        $invoiceNumber.readOnly = true;
        $invoiceNumber.value = 'invoices-10001';
    }
}
function initializeSelect2() {
    // تنظیم Select2 برای مشتری
    $('#customer').select2({
        placeholder: 'مشتری را انتخاب کنید',
        ajax: {
            url: '/api/customers/search',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                };
            }
        }
    });

    // تنظیم Select2 برای فروشنده
    $('#seller').select2({
        placeholder: 'فروشنده را انتخاب کنید',
        ajax: {
            url: '/api/sellers/search',
            dataType: 'json',
            delay: 250,
            processResults: function(data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                };
            }
        }
    });
}

function initializeDatePickers() {
    // تنظیم تقویم شمسی برای تاریخ فاکتور
    $('#date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: true
    });

    // تنظیم تقویم شمسی برای تاریخ سررسید
    $('#dueDate').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false
    });
}

function initializeInvoiceNumberToggle() {
    const invoiceNumberInput = document.getElementById('invoiceNumber');
    const invoiceNumberSwitch = document.getElementById('invoiceNumberSwitch');

    invoiceNumberSwitch.addEventListener('change', function() {
        invoiceNumberInput.readOnly = !this.checked;
        if (!this.checked) {
            // بازیابی شماره فاکتور خودکار
            fetch('/api/invoices/next-number')
                .then(response => response.json())
                .then(data => {
                    invoiceNumberInput.value = data.number;
                });
        }
    });
}

function initializeProductSearch() {
    const productSearch = document.getElementById('productSearch');
    const productList = document.getElementById('productList');
    let searchTimeout;

    productSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            productList.style.display = 'none';
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displayProductResults(data);
                });
        }, 300);
    });
}

function displayProductResults(products) {
    const productList = document.getElementById('productList');
    productList.innerHTML = '';

    if (products.length === 0) {
        productList.style.display = 'none';
        return;
    }

    products.forEach(product => {
        const div = document.createElement('div');
        div.className = 'product-item';
        div.innerHTML = `
            <div class="product-info">
                <img src="${product.image}" alt="${product.name}" class="product-image">
                <div>
                    <strong>${product.name}</strong>
                    <div>کد: ${product.code}</div>
                    <div>موجودی: ${product.stock}</div>
                </div>
            </div>
            <div class="product-price">${formatNumber(product.price)} ریال</div>
        `;

        div.addEventListener('click', () => addProductToInvoice(product));
        productList.appendChild(div);
    });

    productList.style.display = 'block';
}

function addProductToInvoice(product) {
    const tbody = document.getElementById('selectedProducts');
    const existingRow = document.querySelector(`tr[data-product-id="${product.id}"]`);

    if (existingRow) {
        // افزایش تعداد محصول موجود
        const quantityInput = existingRow.querySelector('.quantity-input');
        quantityInput.value = parseInt(quantityInput.value) + 1;
        updateRowTotal(existingRow);
    } else {
        // افزودن ردیف جدید
        const tr = document.createElement('tr');
        tr.setAttribute('data-product-id', product.id);
        tr.innerHTML = `
            <td><img src="${product.image}" alt="${product.name}" class="product-thumbnail"></td>
            <td>${product.code}</td>
            <td>${product.name}</td>
            <td>${product.category}</td>
            <td>${product.stock}</td>
            <td class="price">${formatNumber(product.price)}</td>
            <td>
                <input type="number" class="form-control quantity-input" value="1" min="1" max="${product.stock}">
            </td>
            <td class="row-total">${formatNumber(product.price)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-product">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;

        tbody.appendChild(tr);
        initializeRowEvents(tr);
    }

    updateInvoiceTotal();
    document.getElementById('productSearch').value = '';
    document.getElementById('productList').style.display = 'none';
}

function initializeRowEvents(row) {
    const quantityInput = row.querySelector('.quantity-input');
    const removeButton = row.querySelector('.remove-product');

    quantityInput.addEventListener('change', () => {
        updateRowTotal(row);
        updateInvoiceTotal();
    });

    removeButton.addEventListener('click', () => {
        row.remove();
        updateInvoiceTotal();
    });
}

function updateRowTotal(row) {
    const price = parseFloat(row.querySelector('.price').textContent.replace(/,/g, ''));
    const quantity = parseInt(row.querySelector('.quantity-input').value);
    const total = price * quantity;
    row.querySelector('.row-total').textContent = formatNumber(total);
}

function updateInvoiceTotal() {
    const rows = document.querySelectorAll('#selectedProducts tr');
    let subtotal = 0;

    rows.forEach(row => {
        subtotal += parseFloat(row.querySelector('.row-total').textContent.replace(/,/g, ''));
    });

    const discountPercent = parseFloat(document.getElementById('discount').value) || 0;
    const discountAmount = (subtotal * discountPercent) / 100;
    const finalAmount = subtotal - discountAmount;

    document.getElementById('totalAmount').textContent = formatNumber(subtotal);
    document.getElementById('discountAmount').textContent = formatNumber(discountAmount);
    document.getElementById('finalAmount').textContent = formatNumber(finalAmount);
}

function formatNumber(num) {
    return num.toLocaleString('fa-IR');
}
$(document).ready(function() {
    // گرفتن شماره فاکتور اتوماتیک هنگام لود
    function loadNextNumber() {
        $.get('/invoices/next-number', function(data) {
            $('#number').val(data.number);
        });
    }

    // فقط اگر سوییچ اتوماتیک فعال باشد، شماره اتوماتیک را بگیر
    if ($('#autoNumberSwitch').is(':checked')) {
        loadNextNumber();
        $('#number').prop('readonly', true);
    } else {
        $('#number').prop('readonly', false);
    }

    // تغییر سوییچ
    $('#autoNumberSwitch').on('change', function() {
        if ($(this).is(':checked')) {
            loadNextNumber();
            $('#number').prop('readonly', true);
        } else {
            $('#number').val('');
            $('#number').prop('readonly', false);
        }
    });
});
