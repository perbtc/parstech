@extends('layouts.app')

@section('title', 'صدور فاکتور جدید')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <style>
        .invoice-header {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .product-search-container {
            position: relative;
            margin-bottom: 30px;
        }

        .product-list {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s;
        }

        .product-item:hover {
            background-color: #f8f9fa;
        }

        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-left: 15px;
        }

        .product-info {
            flex-grow: 1;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-code {
            color: #6c757d;
            font-size: 0.9em;
        }

        .selected-products-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .selected-products-table thead {
            background-color: #f8f9fa;
        }

        .selected-products-table th {
            padding: 15px;
            font-weight: 600;
        }

        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }

        .custom-switch {
            padding-right: 2.25rem;
        }

        .btn-remove {
            color: #dc3545;
            border: none;
            background: none;
            padding: 5px;
            transition: transform 0.2s;
        }

        .btn-remove:hover {
            transform: scale(1.1);
        }

        .low-stock {
            color: #dc3545;
            font-weight: bold;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <div class="invoice-header">
        <h2 class="mb-4">صدور فاکتور جدید</h2>

        <form method="POST" action="{{ route('invoices.store') }}" id="invoiceForm">
            @csrf

            <div class="row">
                <!-- شماره فاکتور -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="invoiceNumber">شماره فاکتور</label>
                        <div class="input-group">
                            <input type="text" id="invoiceNumber" name="invoiceNumber"
                                   class="form-control" value="{{ $nextInvoiceNumber ?? '' }}" readonly>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input"
                                               id="invoiceNumberSwitch" onchange="toggleInvoiceNumber()">
                                        <label class="custom-control-label"
                                               for="invoiceNumberSwitch">شماره‌گذاری دستی</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ارجاع -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="reference">ارجاع</label>
                        <input type="text" id="reference" name="reference"
                               class="form-control" placeholder="شماره ارجاع را وارد کنید">
                    </div>
                </div>

                <!-- تاریخ -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="date">تاریخ</label>
                        <input type="text" id="date" name="date"
                               class="form-control" required autocomplete="off">
                    </div>
                </div>

                <!-- تاریخ سررسید -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="dueDate">تاریخ سررسید</label>
                        <input type="text" id="dueDate" name="dueDate"
                               class="form-control" autocomplete="off">
                    </div>
                </div>

                <!-- مشتری -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="customer">مشتری</label>
                        <select id="customer" name="customer" class="form-control select2" required>
                            <option value="">انتخاب مشتری...</option>
                        </select>
                    </div>
                </div>

                <!-- واحد پول -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="currency">واحد پول</label>
                        <select id="currency" name="currency" class="form-control">
                            <option value="IRR">ریال ایران</option>
                            <option value="TOMAN">تومان</option>
                        </select>
                    </div>
                </div>

                <!-- فروشنده -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="seller">فروشنده</label>
                        <select id="seller" name="seller" class="form-control select2">
                            <option value="">انتخاب فروشنده...</option>
                        </select>
                    </div>
                </div>

                <!-- درصد تخفیف -->
                <div class="col-md-4 mb-3">
                    <div class="form-group">
                        <label for="discount">درصد تخفیف (%)</label>
                        <input type="number" id="discount" name="discount"
                               class="form-control" step="0.01" min="0" max="100">
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
                    <thead class="thead-light">
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
                            <td colspan="7" class="text-left">جمع کل:</td>
                            <td colspan="2">
                                <span id="totalAmount">0</span> ریال
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-left">تخفیف:</td>
                            <td colspan="2">
                                <span id="discountAmount">0</span> ریال
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" class="text-left">مبلغ نهایی:</td>
                            <td colspan="2">
                                <strong><span id="finalAmount">0</span> ریال</strong>
                            </td>
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
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // تنظیمات تقویم شمسی
            const datePickerConfig = {
                initialValue: true,
                initialValueType: 'persian',
                format: 'YYYY/MM/DD',
                autoClose: true,
                persianDigit: true,
                observer: true,
                calendar: {
                    persian: {
                        locale: 'fa'
                    }
                },
                toolbox: {
                    calendarSwitch: {
                        enabled: false
                    }
                },
                onSelect: function() {
                    $(this.model.inputElement).trigger('change');
                }
            };

            // راه‌اندازی تقویم‌های شمسی
            $('#date, #dueDate').persianDatepicker(datePickerConfig);

            // راه‌اندازی Select2 برای انتخاب مشتری و فروشنده
            $('.select2').select2({
                theme: 'bootstrap4',
                dir: 'rtl',
                language: 'fa',
                placeholder: 'انتخاب کنید...',
                allowClear: true,
                ajax: {
                    url: function() {
                        const type = $(this).attr('id');
                        return `/api/${type}s/search`;
                    },
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.items,
                            pagination: {
                                more: data.hasMore
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });

            // جستجوی محصولات
            let searchTimeout;
            $('#productSearch').on('input', function() {
                const query = $(this).val();
                const $productList = $('#productList');

                clearTimeout(searchTimeout);

                if (query.length < 2) {
                    $productList.hide().empty();
                    return;
                }

                searchTimeout = setTimeout(() => {
                    fetch(`/api/products/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(products => {
                            $productList.empty();

                            if (products.length === 0) {
                                $productList.append(`
                                    <div class="p-3 text-center text-muted">
                                        محصولی یافت نشد
                                    </div>
                                `);
                            } else {
                                products.forEach(product => {
                                    const productHtml = `
                                        <div class="product-item">
                                            <img src="${product.image || '/images/no-image.png'}"
                                                 class="product-image"
                                                 alt="${product.name}">
                                            <div class="product-info">
                                                <div class="product-name">${product.name}</div>
                                                <div class="product-code">${product.code}</div>
                                            </div>
                                            <button type="button"
                                                    class="btn btn-primary btn-sm"
                                                    onclick="addProduct(${JSON.stringify(product)})">
                                                افزودن
                                            </button>
                                        </div>
                                    `;
                                    $productList.append(productHtml);
                                });
                            }

                            $productList.show();
                        });
                }, 300);
            });

            // بستن لیست محصولات با کلیک خارج از آن
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.product-search-container').length) {
                    $('#productList').hide();
                }
            });

            // محاسبه مجددٔ مبالغ با تغییر تخفیف
            $('#discount').on('input', calculateTotals);
        });

        // تابع افزودن محصول به لیست
        function addProduct(product) {
            const $selectedProducts = $('#selectedProducts');
            const existingRow = $selectedProducts.find(`[data-product-id="${product.id}"]`);

            if (existingRow.length) {
                const quantityInput = existingRow.find('input[name$="[quantity]"]');
                quantityInput.val(parseInt(quantityInput.val()) + 1).trigger('change');
                return;
            }

            const rowHtml = `
                <tr data-product-id="${product.id}">
                    <td>
                        <img src="${product.image || '/images/no-image.png'}"
                             alt="${product.name}"
                             width="50" height="50"
                             style="object-fit: cover; border-radius: 4px;">
                    </td>
                    <td>${product.code}</td>
                    <td>${product.name}</td>
                    <td>${product.category}</td>
                    <td class="${product.stock < 10 ? 'low-stock' : ''}">${product.stock}</td>
                    <td>
                        <input type="number"
                               class="form-control text-left"
                               name="products[${product.id}][price]"
                               value="${product.price}"
                               step="1000"
                               min="0"
                               onchange="calculateRowTotal(this)">
                    </td>
                    <td>
                        <input type="number"
                               class="form-control text-left"
                               name="products[${product.id}][quantity]"
                               value="1"
                               min="1"
                               max="${product.stock}"
                               onchange="calculateRowTotal(this)">
                    </td>
                    <td class="row-total">${product.price}</td>
                    <td>
                        <button type="button"
                                class="btn-remove"
                                onclick="removeProduct(this)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;

            $selectedProducts.append(rowHtml);
            calculateTotals();
            $('#productList').hide();
            $('#productSearch').val('');
        }

        // تابع حذف محصول از لیست
        function removeProduct(button) {
            Swal.fire({
                title: 'آیا مطمئن هستید؟',
                text: "این محصول از لیست حذف خواهد شد",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'بله، حذف شود',
                cancelButtonText: 'انصراف'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(button).closest('tr').remove();
                    calculateTotals();
                }
            });
        }

        // محاسبه مجموع هر ردیف
        function calculateRowTotal(input) {
            const row = $(input).closest('tr');
            const price = parseFloat(row.find('input[name$="[price]"]').val()) || 0;
            const quantity = parseInt(row.find('input[name$="[quantity]"]').val()) || 0;
            const total = price * quantity;
            row.find('.row-total').text(total.toLocaleString('fa-IR'));
            calculateTotals();
        }

        // محاسبه مجموع کل
        function calculateTotals() {
            let total = 0;
            $('#selectedProducts tr').each(function() {
                total += parseInt($(this).find('.row-total').text().replace(/,/g, '')) || 0;
            });

            const discount = parseFloat($('#discount').val()) || 0;
            const discountAmount = (total * discount) / 100;
            const finalAmount = total - discountAmount;

            $('#totalAmount').text(total.toLocaleString('fa-IR'));
            $('#discountAmount').text(discountAmount.toLocaleString('fa-IR'));
            $('#finalAmount').text(finalAmount.toLocaleString('fa-IR'));
        }

        // تغییر حالت شماره فاکتور
        function toggleInvoiceNumber() {
            const $invoiceNumber = $('#invoiceNumber');
            const isManual = $('#invoiceNumberSwitch').is(':checked');

            $invoiceNumber.prop('readonly', !isManual);
            if (!isManual) {
                $invoiceNumber.val('{{ $nextInvoiceNumber ?? "" }}');
            } else {
                $invoiceNumber.val('').focus();
            }
        }

        // اعتبارسنجی فرم قبل از ارسال
        $('#invoiceForm').on('submit', function(e) {
            e.preventDefault();

            if (!$('#selectedProducts tr').length) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'لطفاً حداقل یک محصول به فاکتور اضافه کنید'
                });
                return;
            }

            if (!$('#customer').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطا',
                    text: 'لطفاً مشتری را انتخاب کنید'
                });
                return;
            }

            Swal.fire({
                title: 'در حال ثبت فاکتور',
                text: 'لطفاً صبر کنید...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            this.submit();
        });
    </script>
@endsection
