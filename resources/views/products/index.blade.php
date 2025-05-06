@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h2>لیست کالاها</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ردیف</th>
                <th>نام کالا</th>
                <th>کد کالا</th>
                <th>دسته‌بندی</th>
                <th>موجودی</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            {{-- اینجا لیست کالاها نمایش داده می‌شود --}}
        </tbody>
    </table>
    <a href="{{ route('products.create') }}" class="btn btn-primary">افزودن کالای جدید</a>
</div>
@endsection
