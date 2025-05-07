<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // لیست درختی
    public function index()
    {
        $categories = Category::orderBy('category_type')->orderBy('parent_id')->orderBy('name')->get();

        // ساخت آرایه درختی
        $tree = [];
        $items = [];
        foreach ($categories as $cat) {
            $items[$cat->id] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'code' => $cat->code,
                'category_type' => $cat->category_type,
                'parent_id' => $cat->parent_id,
                'children' => []
            ];
        }
        foreach ($items as $id => &$item) {
            if ($item['parent_id'] && isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][] = &$item;
            } else {
                $tree[] = &$item;
            }
        }
        unset($item);

        return view('categories.index', compact('tree'));
    }

    // نمایش فرم ایجاد
    public function create()
    {
        $personCategories = Category::where('category_type', 'person')->get();
        $productCategories = Category::where('category_type', 'product')->get();
        $serviceCategories = Category::where('category_type', 'service')->get();

        $nextPersonCode = 'per' . (Category::where('category_type', 'person')->count() + 1001);
        $nextProductCode = 'pro' . (Category::where('category_type', 'product')->count() + 1001);
        $nextServiceCode = 'ser' . (Category::where('category_type', 'service')->count() + 1001);

        return view('categories.create', compact(
            'personCategories', 'productCategories', 'serviceCategories',
            'nextPersonCode', 'nextProductCode', 'nextServiceCode'
        ));
    }

    // ذخیره دسته‌بندی
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'code' => 'nullable|string|max:100',
            'category_type' => 'required|in:person,product,service',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['name', 'code', 'category_type', 'parent_id', 'description']);

        // تولید کد در صورت خالی بودن
        if (empty($data['code'])) {
            $prefix = [
                'person' => 'per',
                'product' => 'pro',
                'service' => 'ser',
            ];
            $count = Category::where('category_type', $request->category_type)->count() + 1001;
            $data['code'] = $prefix[$request->category_type] . $count;
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('categories.create')->with('success', 'دسته‌بندی جدید با موفقیت ایجاد شد.');
    }
}
