<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Province;
class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::latest()->paginate(10);
        return view('persons.index', compact('persons'));
    }

public function create()
{
    $provinces = Province::orderBy('name')->get();
    return view('persons.create', compact('provinces'));
}

    public function store(Request $request)
    {
        $request->validate([
            'accounting_code' => 'required|string|max:255|unique:persons,accounting_code',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:customer,supplier,shareholder,employee',
            'national_code' => 'nullable|string|size:10|unique:persons',
            'mobile' => 'nullable|string|max:11',
            'join_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $person = Person::create($request->all());

            // ذخیره حساب‌های بانکی
            if ($request->has('bank_accounts')) {
                $bankAccounts = [];
                for ($i = 0; $i < count($request->bank_accounts['bank_name']); $i++) {
                    if (!empty($request->bank_accounts['bank_name'][$i])) {
                        $bankAccounts[] = [
                            'bank_name' => $request->bank_accounts['bank_name'][$i],
                            'account_number' => $request->bank_accounts['account_number'][$i],
                            'card_number' => $request->bank_accounts['card_number'][$i],
                            'iban' => $request->bank_accounts['iban'][$i],
                        ];
                    }
                }
                $person->bankAccounts()->createMany($bankAccounts);
            }

            DB::commit();
            return redirect()->route('persons.index')
                ->with('success', 'شخص جدید با موفقیت ایجاد شد.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در ثبت اطلاعات: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Person $person)
    {
        return view('persons.show', compact('person'));
    }

    public function edit(Person $person)
    {
        return view('persons.edit', compact('person'));
    }

    public function update(Request $request, Person $person)
    {
        $request->validate([
            'accounting_code' => 'required|string|max:255|unique:persons,accounting_code,' . $person->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:customer,supplier,shareholder,employee',
            'national_code' => 'nullable|string|size:10|unique:persons,national_code,' . $person->id,
            'mobile' => 'nullable|string|max:11',
            'join_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $person->update($request->all());

            // به‌روزرسانی حساب‌های بانکی
            if ($request->has('bank_accounts')) {
                // حذف حساب‌های قبلی
                $person->bankAccounts()->delete();

                // افزودن حساب‌های جدید
                $bankAccounts = [];
                for ($i = 0; $i < count($request->bank_accounts['bank_name']); $i++) {
                    if (!empty($request->bank_accounts['bank_name'][$i])) {
                        $bankAccounts[] = [
                            'bank_name' => $request->bank_accounts['bank_name'][$i],
                            'account_number' => $request->bank_accounts['account_number'][$i],
                            'card_number' => $request->bank_accounts['card_number'][$i],
                            'iban' => $request->bank_accounts['iban'][$i],
                        ];
                    }
                }
                $person->bankAccounts()->createMany($bankAccounts);
            }

            DB::commit();
            return redirect()->route('persons.index')
                ->with('success', 'اطلاعات شخص با موفقیت به‌روزرسانی شد.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در به‌روزرسانی اطلاعات: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Person $person)
    {
        try {
            DB::beginTransaction();

            // حذف حساب‌های بانکی مرتبط
            $person->bankAccounts()->delete();

            // حذف شخص
            $person->delete();

            DB::commit();
            return redirect()->route('persons.index')
                ->with('success', 'شخص با موفقیت حذف شد.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'خطا در حذف اطلاعات: ' . $e->getMessage());
        }
    }

    public function customers()
    {
        $persons = Person::where('type', 'customer')->latest()->paginate(10);
        return view('persons.customers', compact('persons'));
    }

    public function suppliers()
    {
        $persons = Person::where('type', 'supplier')->latest()->paginate(10);
        return view('persons.suppliers', compact('persons'));
    }

    public function sellersIndex()
    {
        $persons = Person::where('type', 'seller')->latest()->paginate(10);
        return view('persons.sellers.index', compact('persons'));
    }

    public function sellersPage()
    {
        return view('persons.sellers.page');
    }

public function getNextCode()
{
    try {
        // فقط کدهایی که مثل person-12345 هستند را انتخاب کن
        $lastPerson = \App\Models\Person::where('accounting_code', 'REGEXP', '^person-[0-9]+$')
            ->orderByRaw('CAST(SUBSTRING(accounting_code, 8) AS UNSIGNED) DESC')
            ->first();

        $nextNumber = 10001;
        if ($lastPerson) {
            $lastNumber = intval(substr($lastPerson->accounting_code, 7));
            $nextNumber = $lastNumber + 1;
        }
        $nextCode = 'person-' . $nextNumber;
        return response()->json(['code' => $nextCode]);
    } catch (\Exception $e) {
        \Log::error('Error in getNextCode: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
