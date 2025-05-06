@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h2>صدور فاکتور جدید</h2>
    <form>
        <!-- فرم ثبت فاکتور اینجا قرار می‌گیرد -->
        <div class="form-group">
            <label for="customer">مشتری</label>
            <input type="text" id="customer" name="customer" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="items">کالاها</label>
            <input type="text" id="items" name="items" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">ثبت فاکتور</button>
    </form>
</div>
@endsection
