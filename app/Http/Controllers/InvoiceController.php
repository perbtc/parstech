<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function create()
    {
        // اینجا view صدور فاکتور را نمایش بده
        return view('invoices.create');
    }
}
