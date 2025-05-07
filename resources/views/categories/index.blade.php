@extends('layouts.app')

@section('title', 'لیست دسته‌بندی‌ها')

@section('content')
<link rel="stylesheet" href="{{ asset('css/category-tree.css') }}">

<div class="container category-tree-container mt-4">
    <div class="card">
        <div class="card-header category-tree-header">
            <h5 class="mb-0">لیست درختی دسته‌بندی‌ها</h5>
        </div>
        <div class="card-body category-tree-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="category-tree-wrapper">
                @if(count($tree) > 0)
                    <ul class="category-tree-root">
                        @foreach($tree as $node)
                            @include('categories.partials.tree-node', ['node' => $node])
                        @endforeach
                    </ul>
                @else
                    <div class="text-center text-muted py-4">
                        هیچ دسته‌بندی‌ای یافت نشد.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
