
$(document).ready(function () {
        // اضافه کردن سوییچ به فرم

    const $container = $accountingCodeInput.parent();


        // ساخت input و سوییچ
    const switchHtml = `
        <div class="accounting-code-container">
            <input type="text" name="accounting_code" id="accounting_code" class="form-control" required readonly>
            <label class="switch">
                <input type="checkbox" id="autoCodeSwitch" checked>
                <span class="slider"></span>
            </label>
            <span class="accounting-code-label">کد خودکار</span>
        </div>
    `;
    $('#accounting_code_container').html(switchHtml);
    $container.html(switchHtml);

    const $accountingCodeInput = $('#accounting_code');
    const $autoSwitch = $('#autoCodeSwitch');

        function getNextCode() {
        $.ajax({
            url: '/api/persons/next-code',
            method: 'GET',
            success: function(response) {
                if (response.code) {
                    $accountingCodeInput.val(response.code);
                }
            },
            error: function() {
                $accountingCodeInput.val('person-10001');
            }
        });
    }
    if ($autoSwitch.is(':checked')) {
        $accountingCodeInput.prop('readonly', true);
        getNextCode();
    }
        $autoSwitch.change(function() {
        if ($(this).is(':checked')) {
            $accountingCodeInput.prop('readonly', true);
            getNextCode();
        } else {
            $accountingCodeInput.prop('readonly', false);
            $accountingCodeInput.focus();
        }
    });
    // غیرفعال کردن input در حالت خودکار
    $accountingCodeInput.prop('readonly', true);

    // تنظیمات تقویم شمسی
    $('.datepicker').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        persianDigit: false
    });

        // رویداد تغییر سوییچ
    $('#autoCodeSwitch').change(function() {
        const isAuto = $(this).is(':checked');
        $accountingCodeInput.prop('readonly', isAuto);

        if (isAuto) {
            getNextCode();
        } else {
            $accountingCodeInput.val('').focus();
        }
    });

    // افزودن حساب بانکی
    $('#add-bank-account').click(function() {
        const bankAccountHtml = `
            <div class="bank-account-row animate-fade-in">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">بانک</label>
                            <input type="text" name="bank_accounts[bank_name][]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">شماره حساب</label>
                            <input type="text" name="bank_accounts[account_number][]" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">شماره کارت</label>
                            <input type="text" name="bank_accounts[card_number][]" class="form-control card-number" maxlength="16">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">شماره شبا</label>
                            <input type="text" name="bank_accounts[iban][]" class="form-control iban" maxlength="26">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-danger remove-bank-account">
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
        $(this).closest('.bank-account-row').fadeOut(300, function() {
            $(this).remove();
        });
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
        if (value.length > 24) value = value.slice(0, 24);
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

        // نمایش loading
        if ($(this).valid()) {
            $(this).addClass('loading');
            $('button[type="submit"]').prop('disabled', true);
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
