@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">افزودن شخص جدید</h3>
                </div>
                <form action="{{ route('persons.store') }}" method="POST" id="person-form">
                    @csrf
                    <div class="card-body">
                        <!-- اطلاعات اصلی -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">اطلاعات اصلی</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>کد حسابداری</label>
                                            <input type="text" name="accounting_code" class="form-control" value="{{ old('accounting_code') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>شرکت</label>
                                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>عنوان</label>
                                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>نام <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                            @error('first_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>نام خانوادگی <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                            @error('last_name')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>نام مستعار</label>
                                            <input type="text" name="nickname" class="form-control" value="{{ old('nickname') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>دسته‌بندی</label>
                                            <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>نوع <span class="text-danger">*</span></label>
                                            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                                <option value="">انتخاب کنید</option>
                                                <option value="customer" {{ old('type') == 'customer' ? 'selected' : '' }}>مشتری</option>
                                                <option value="supplier" {{ old('type') == 'supplier' ? 'selected' : '' }}>تامین کننده</option>
                                                <option value="shareholder" {{ old('type') == 'shareholder' ? 'selected' : '' }}>سهامدار</option>
                                                <option value="employee" {{ old('type') == 'employee' ? 'selected' : '' }}>کارمند</option>
                                            </select>
                                            @error('type')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بخش عمومی -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">اطلاعات عمومی</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>اعتبار مالی (ریال)</label>
                                            <input type="number" name="credit_limit" class="form-control" value="{{ old('credit_limit', 0) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>لیست قیمت</label>
                                            <input type="text" name="price_list" class="form-control" value="{{ old('price_list') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>نوع مالیات</label>
                                            <input type="text" name="tax_type" class="form-control" value="{{ old('tax_type') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>کد ملی</label>
                                            <input type="text" name="national_code" class="form-control @error('national_code') is-invalid @enderror" value="{{ old('national_code') }}" maxlength="10">
                                            @error('national_code')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>کد اقتصادی</label>
                                            <input type="text" name="economic_code" class="form-control" value="{{ old('economic_code') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>شماره ثبت</label>
                                            <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>کد شعبه</label>
                                            <input type="text" name="branch_code" class="form-control" value="{{ old('branch_code') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>توضیحات</label>
                                            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بخش آدرس -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">آدرس</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>آدرس</label>
                                            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>کشور</label>
                                            <input type="text" name="country" class="form-control" value="{{ old('country', 'ایران') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>استان</label>
                                            <input type="text" name="province" class="form-control" value="{{ old('province') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>شهر</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>کد پستی</label>
                                            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}" maxlength="10">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بخش تماس -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">اطلاعات تماس</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>تلفن</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>موبایل</label>
                                            <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>فکس</label>
                                            <input type="text" name="fax" class="form-control" value="{{ old('fax') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>تلفن 1</label>
                                            <input type="text" name="phone1" class="form-control" value="{{ old('phone1') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>تلفن 2</label>
                                            <input type="text" name="phone2" class="form-control" value="{{ old('phone2') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>تلفن 3</label>
                                            <input type="text" name="phone3" class="form-control" value="{{ old('phone3') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>ایمیل</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>وب سایت</label>
                                            <input type="url" name="website" class="form-control" value="{{ old('website') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بخش حساب بانکی -->
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">حساب‌های بانکی</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="add-bank-account">
                                    <i class="fas fa-plus"></i> افزودن حساب بانکی
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="bank-accounts"></div>
                            </div>
                        </div>

                        <!-- تاریخ‌ها -->
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">تاریخ‌ها</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاریخ تولد</label>
                                            <input type="text" name="birth_date" class="form-control datepicker" value="{{ old('birth_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاریخ ازدواج</label>
                                            <input type="text" name="marriage_date" class="form-control datepicker" value="{{ old('marriage_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>تاریخ عضویت</label>
                                            <input type="text" name="join_date" class="form-control datepicker" value="{{ old('join_date', date('Y-m-d')) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">ذخیره</button>
                        <a href="{{ route('persons.index') }}" class="btn btn-secondary">انصراف</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@latest/dist/css/persian-datepicker.min.css">
<style>
    /* RTL Styles */
    body {
        direction: rtl;
        text-align: right;
    }
    .card-header h5 {
        margin-bottom: 0;
    }
    .invalid-feedback {
        text-align: right;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/persian-date@latest/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@latest/dist/js/persian-datepicker.min.js"></script>
<script>
$(document).ready(function() {
    // تنظیمات تاریخ شمسی
    $('.datepicker').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false
    });

    // افزودن حساب بانکی
    $('#add-bank-account').click(function() {
        const bankAccountHtml = `
            <div class="bank-account-row border rounded p-3 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>بانک</label>
                            <input type="text" name="bank_accounts[bank_name][]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>شماره حساب</label>
                            <input type="text" name="bank_accounts[account_number][]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>شماره کارت</label>
                            <input type="text" name="bank_accounts[card_number][]" class="form-control card-number" maxlength="16">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>شماره شبا</label>
                            <input type="text" name="bank_accounts[iban][]" class="form-control iban" maxlength="26">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-danger btn-block remove-bank-account">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#bank-accounts').append(bankAccountHtml);
    });

    // حذف حساب بانکی
    $(document).on('click', '.remove-bank-account', function() {
        $(this).closest('.bank-account-row').remove();
    });

    // اعتبارسنجی کد ملی
    $('input[name="national_code"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 10) value = value.slice(0, 10);
        $(this).val(value);
    });

    // فرمت شماره کارت
    $(document).on('input', '.card-number', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 16) value = value.slice(0, 16);
        value = value.replace(/(\d{4})/g, '$1-').replace(/-$/, '');
        $(this).val(value);
    });

    // فرمت شماره شبا
    $(document).on('input', '.iban', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 26) value = value.slice(0, 26);
        if (value.length > 0) value = 'IR' + value;
        $(this).val(value);
    });

    // اعتبارسنجی فرم
    $('#person-form').on('submit', function(e) {
        let nationalCode = $('input[name="national_code"]').val();
        if (nationalCode && !validateNationalCode(nationalCode)) {
            e.preventDefault();
            alert('کد ملی وارد شده معتبر نیست');
        }
    });

    // تابع اعتبارسنجی کد ملی
    function validateNationalCode(code) {
        if (!/^\d{10}$/.test(code)) return false;

        const check = parseInt(code[9]);
        let sum = 0;
        for (let i = 0; i < 9; i++) {
            sum += parseInt(code[i]) * (10 - i);
        }
        const remainder = sum % 11;
        return (remainder < 2 && check == remainder) || (remainder >= 2 && check == 11 - remainder);
    }
});
</script>
@endpush
