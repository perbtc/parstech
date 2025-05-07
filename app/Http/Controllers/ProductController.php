<?php
namespace App\Http\Controllers;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
{
    $product = \App\Models\Product::with(['category', 'brand'])->findOrFail($id);
    return view('products.show', compact('product'));
}
    public function index()
{
    $products = \App\Models\Product::with(['category', 'brand'])->latest()->paginate(20);
    return view('products.index', compact('products'));}
public function upload(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi|max:5120'
    ]);
    $path = $request->file('file')->store('products', 'public');
    return response()->json(['path' => $path]);
}
public function create()
{
    $categories = \App\Models\Category::where('category_type', 'product')->get();
    $brands = \App\Models\Brand::all();
    $units = \App\Models\Unit::all();
    $default_code = \App\Models\Product::generateProductCode();
    return view('products.create', compact('categories', 'brands', 'units', 'default_code'));
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'code'        => 'required|unique:products,code',
            'image'       => 'nullable|image|max:2048',
            'gallery.*'   => 'nullable|image|max:2048',
            'video'       => 'nullable|file|mimetypes:video/mp4,video/x-m4v,video/*',
            'short_desc'  => 'nullable|max:500',
            'description' => 'nullable',
            'stock'       => 'nullable|integer|min:0',
            'min_stock'   => 'nullable|integer|min:0',
            'unit'        => 'nullable|string|max:20',
            'weight'      => 'nullable|numeric',
            'buy_price'   => 'nullable|numeric',
            'sell_price'  => 'nullable|numeric',
            'discount'    => 'nullable|numeric',
            'barcode'     => 'nullable|string|max:100',
            'store_barcode' => 'nullable|string|max:100',
            'is_active'   => 'nullable'
        ]);

        // ذخیره تصویر شاخص
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // گالری تصاویر
        if ($request->hasFile('gallery')) {
            $gallery_paths = [];
            foreach($request->file('gallery') as $gallery_img){
                $gallery_paths[] = $gallery_img->store('products/gallery','public');
            }
            $validated['gallery'] = json_encode($gallery_paths);
        }

        // ویدیو
        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('products/video','public');
        }

        // فیلد فعال بودن
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        // ویژگی‌ها (attributes)
        if ($request->has('attributes')) {
            $validated['attributes'] = json_encode($request->input('attributes'));
        }

        // ذخیره محصول
        Product::create($validated);

        // بازگشت با نوتیفیکیشن موفقیت
        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ذخیره شد.');
    }
}
