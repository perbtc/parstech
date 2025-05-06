<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لیست دسته‌بندی‌ها</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header>
        <h1>لیست دسته‌بندی‌ها</h1>
    </header>
    <div class="container">
        <a href="{{ route('categories.create') }}" class="button">ایجاد دسته‌بندی جدید</a>
        <table>
            <thead>
                <tr>
                    <th>عنوان</th>
                    <th>توضیحات</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $category->id) }}" class="button">ویرایش</a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="button danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
