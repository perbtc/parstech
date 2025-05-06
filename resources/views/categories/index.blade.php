@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-list-ul fa-lg ms-2"></i>
                        <span>لیست دسته‌بندی‌ها</span>
                    </div>
                    <a href="{{ route('categories.create') }}" class="btn btn-light btn-sm border">
                        <i class="fa fa-plus"></i> دسته‌بندی جدید
                    </a>
                </div>
                <div class="card-body p-0">
                    @if(session('success'))
                        <div class="alert alert-success m-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if($categories->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:60px">#</th>
                                        <th>نام دسته‌بندی</th>
                                        <th>توضیحات</th>
                                        <th style="width: 120px;">عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $cat)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $cat->name }}</td>
                                            <td>{{ Str::limit($cat->description, 40) }}</td>
                                            <td>
                                                <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-outline-primary" title="ویرایش">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف این مورد مطمئن هستید؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" title="حذف"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">
                            {{ $categories->links() }}
                        </div>
                    @else
                        <div class="alert alert-info m-3">
                            هیچ دسته‌بندی‌ای ثبت نشده است.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
