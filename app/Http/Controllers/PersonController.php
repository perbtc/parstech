<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class PersonController extends Controller
{
    public function create()
    {
        return view('persons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'type' => 'required|in:customer,supplier,shareholder,employee',
            'national_code' => 'nullable|string|size:10|unique:persons',
            'mobile' => 'nullable|string|max:11',
            'join_date' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            // ایجاد شخص
            $person = Person::create([
                'accounting_code' => $request->accounting_code,
                'company_name' => $request->company_name,
                'title' => $request->title,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'nickname' => $request->nickname,
                'category' => $request->category,
                'type' => $request->type,
                'credit_limit' => $request->credit_limit,
                'price_list' => $request->price_list,
                'tax_type' => $request->tax_type,
                'national_code' => $request->national_code,
                'economic_code' => $request->economic_code,
                'registration_number' => $request->registration_number,
                'branch_code' => $request->branch_code,
                'description' => $request->description,
                'address' => $request->address,
                'country' => $request->country,
                'province' => $request->province,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
                'fax' => $request->fax,
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'phone3' => $request->phone3,
                'email' => $request->email,
                'website' => $request->website,
                'birth_date' => $request->birth_date,
                'marriage_date' => $request->marriage_date,
                'join_date' => $request->join_date,
            ]);

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

    public function index()
    {
        $persons = Person::latest()->paginate(10);
        return view('persons.index', compact('persons'));
    }
    public function sellersIndex()
    {
        return view('persons.sellers.index');
    }

    public function sellersPage()
    {
        return view('persons.sellers.page');
    }
}
