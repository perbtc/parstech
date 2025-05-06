<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // نمایش لیست کالاها
        return view('products.index');
    }

    public function create()
    {
        // فرم افزودن کالای جدید
        return view('products.create');
    }

    // سایر متدهای resource را در صورت نیاز اضافه کن
}
