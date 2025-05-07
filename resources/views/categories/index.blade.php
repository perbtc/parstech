<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لیست دسته‌بندی‌ها</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;700&display=swap');
        :root {
            --main: #3f51b5;
            --main-dark: #2c387e;
            --danger: #e53935;
            --danger-dark: #b71c1c;
            --success: #43a047;
            --success-dark: #1b5e20;
            --gray: #f5f7fa;
            --border: #e0e0e0;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Vazirmatn', Tahoma, Arial, sans-serif;
            background: var(--gray);
            margin: 0;
            color: #222;
        }
        header {
            background: var(--main);
            color: #fff;
            padding: 32px 0 20px 0;
            text-align: center;
            border-radius: 0 0 32px 32px;
            box-shadow: 0 2px 8px rgba(63,81,181,0.08);
        }
        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            padding: 40px 32px 32px 32px;
            position: relative;
        }
        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
        }
        .search-box input {
            font-size: 1rem;
            padding: 8px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            width: 220px;
            transition: border-color 0.2s;
        }
        .search-box input:focus { border-color: var(--main); }
        .actions-bar .btn {
            background: var(--main);
            color: #fff;
            border: none;
            padding: 10px 22px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            font-weight: bold;
            text-decoration: none;
            margin-right: 6px;
        }
        .actions-bar .btn:hover { background: var(--main-dark); }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 6px rgba(63,81,181,0.04);
        }
        th, td {
            padding: 14px 10px;
            text-align: center;
        }
        th {
            background: var(--gray);
            color: var(--main-dark);
            font-weight: bold;
            border-bottom: 2px solid var(--border);
        }
        tr:nth-child(even) { background: #fafbfc; }
        tr:hover { background: #f0f6ff; }
        .btn-edit, .btn-delete {
            padding: 7px 18px;
            border-radius: 7px;
            border: none;
            cursor: pointer;
            font-size: 0.97rem;
            margin: 0 3px;
            font-family: inherit;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-edit { background: var(--success); color: #fff; }
        .btn-edit:hover { background: var(--success-dark);}
        .btn-delete { background: var(--danger); color: #fff; }
        .btn-delete:hover { background: var(--danger-dark);}
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 28px;
            gap: 4px;
        }
        .pagination button {
            background: var(--main);
            color: #fff;
            border: none;
            padding: 6px 16px;
            border-radius: 6px;
            margin: 0 2px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.2s;
        }
        .pagination button.active, .pagination button:hover {
            background: var(--main-dark);
        }
        /* اعلان ساده */
        .alert {
            position: fixed;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 300px;
            max-width: 600px;
            background: #fff;
            color: #222;
            box-shadow: 0 2px 12px rgba(0,0,0,0.12);
            border-radius: 10px;
            z-index: 9999;
            padding: 16px 24px;
            display: none;
            font-size: 1.09rem;
        }
        .alert.success { border-right: 6px solid var(--success); }
        .alert.danger { border-right: 6px solid var(--danger); }
        /* Modal */
        .modal-bg {
            display: none;
            position: fixed;
            top: 0; right: 0; left: 0; bottom: 0;
            background: rgba(0,0,0,0.28);
            z-index: 9998;
            align-items: center;
            justify-content: center;
        }
        .modal {
            background: #fff;
            border-radius: 12px;
            padding: 32px 24px 24px 24px;
            min-width: 320px;
            max-width: 96vw;
            box-shadow: 0 8px 32px rgba(51,51,51,0.16);
            text-align: center;
        }
        .modal h2 { margin: 0 0 20px 0; color: var(--danger-dark);}
        .modal-buttons {
            margin-top: 28px;
            display: flex;
            justify-content: center;
            gap: 18px;
        }
        .modal .btn { padding: 10px 28px; }
        @media (max-width: 600px) {
            .container { padding: 14px 3vw 18px 3vw;}
            .actions-bar { flex-direction: column; gap: 13px;}
            table { font-size: 0.93rem;}
            th, td { padding: 7px 5px;}
        }
    </style>
</head>
<body dir="rtl">
    <header>
        <h1>مدیریت دسته‌بندی‌ها</h1>
        <p style="margin-top: 8px; font-size: 1.1rem; color:#e1e6fa;opacity:0.9">لیست و مدیریت کامل دسته‌بندی‌های سیستم</p>
    </header>
    <div class="alert" id="alert"></div>
    <div class="container">
        <!-- نوار اکشن بالا -->
        <div class="actions-bar">
            <form class="search-box" onsubmit="return false;">
                <input type="text" id="searchInput" placeholder="جستجوی دسته‌بندی...">
            </form>
            <a href="{{ route('categories.create') }}" class="btn">افزودن دسته‌بندی جدید</a>
        </div>
        <!-- جدول دسته‌بندی‌ها -->
        <div style="overflow-x:auto;">
        <table id="categoriesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>عنوان دسته‌بندی</th>
                    <th>توضیحات</th>
                    <th>تاریخ ایجاد</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                @forelse ($categories as $key => $category)
                <tr data-name="{{ $category->name }}" data-desc="{{ $category->description }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>{{ jdate($category->created_at)->format('Y/m/d') }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category->id) }}" class="btn-edit">ویرایش</a>
                        <button type="button" class="btn-delete" onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')">حذف</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="color: #c00;">هیچ دسته‌بندی‌ای وجود ندارد.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        </div>
        <!-- صفحه‌بندی -->
        @if (method_exists($categories, 'links'))
        <div class="pagination" id="pagination">
            {{ $categories->links('vendor.pagination.simple-bootstrap-4') }}
        </div>
        @endif
    </div>
    <!-- Modal حذف -->
    <div class="modal-bg" id="modalBg">
        <div class="modal">
            <h2>حذف دسته‌بندی</h2>
            <div id="modalText"></div>
            <div class="modal-buttons">
                <form id="deleteForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">حذف</button>
                </form>
                <button class="btn" onclick="closeModal()">انصراف</button>
            </div>
        </div>
    </div>
    <script>
        // جستجوی دسته‌بندی‌ها
        document.getElementById('searchInput').addEventListener('input', function () {
            let filter = this.value.trim();
            let trs = document.querySelectorAll('#categoryTableBody tr');
            trs.forEach(tr => {
                let name = tr.getAttribute('data-name') || '';
                let desc = tr.getAttribute('data-desc') || '';
                if (name.includes(filter) || desc.includes(filter)) {
                    tr.style.display = '';
                } else {
                    tr.style.display = 'none';
                }
            });
        });
        // اعلان
        function showAlert(msg, type='success') {
            let alert = document.getElementById('alert');
            alert.className = 'alert ' + type;
            alert.innerText = msg;
            alert.style.display = 'block';
            setTimeout(() => { alert.style.display = 'none'; }, 2600);
        }
        // Modal حذف
        let modalBg = document.getElementById('modalBg');
        let modalText = document.getElementById('modalText');
        let deleteForm = document.getElementById('deleteForm');
        function confirmDelete(id, name) {
            modalBg.style.display = 'flex';
            modalText.innerHTML = `<b style="color:#d32f2f">آیا مطمئنید می‌خواهید <u>${name}</u> حذف شود؟</b>`;
            deleteForm.action = `/categories/${id}`;
        }
        function closeModal() {
            modalBg.style.display = 'none';
            modalText.innerHTML = '';
            deleteForm.action = '';
        }
        modalBg.addEventListener('click', function(e){
            if(e.target === modalBg) closeModal();
        });
        // اگر حذف موفق انجام شد (از سشن)
        @if (session('success'))
            showAlert("{{ session('success') }}", 'success');
        @elseif (session('error'))
            showAlert("{{ session('error') }}", 'danger');
        @endif
    </script>
</body>
</html>
