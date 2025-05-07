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
    return view('products.index', compact('products'));
}
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
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'code'        => 'required|unique:products,code',
            'image'       => 'nullable|image|max:2048',
            'code' => 'required|unique:products,code',
            'name'=>'required|max:191',
            'code'=>'required|unique:products,code',
            'category_id'=>'required|exists:categories,id',
            'brand_id'=>'nullable|exists:brands,id',
            'image'=>'nullable|image|max:2048',
            'gallery.*'=>'nullable|image|max:2048',
            'video'=>'nullable|file|mimetypes:video/mp4,video/x-m4v,video/*',
            'short_desc'=>'nullable|max:500',
            'description'=>'nullable',
            'stock'=>'nullable|integer|min:0',
            'min_stock'=>'nullable|integer|min:0',
            'unit'=>'nullable|string|max:20',
            'barcode'=>'nullable|string|max:100',
            'is_active'=>'nullable'
        ]);
        if (empty($validated['code'])) {
            $validated['code'] = Product::generateProductCode();
        }
        Product::create($validated);
        $data = $request->only([
            'name','code','category_id','brand_id','short_desc','description',
            'stock','min_stock','unit','barcode'
        ]);
        $data['is_active'] = $request->has('is_active');

        // Uploads
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        \App\Models\Product::create($validated);
        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ذخیره شد.');
        if($request->hasFile('gallery')){
            $gallery_paths = [];
            foreach($request->file('gallery') as $gallery_img){
                $gallery_paths[] = $gallery_img->store('products/gallery','public');
            }
            $data['gallery'] = $gallery_paths;
        }
        if($request->hasFile('video')){
            $data['video'] = $request->file('video')->store('products/video','public');
        }

        // برند تصویر
        if($request->hasFile('brand_image') && $request->brand_id){
            $brand = Brand::find($request->brand_id);
            $brand->image = $request->file('brand_image')->store('brands','public');
            $brand->save();
        }

        Product::create($data);

        return redirect()->route('products.create')->with('success','محصول با موفقیت ثبت شد!');
    }
}
