<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('vehicle_types', 'products.vehicle_type_id', '=', 'vehicle_types.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'vehicle_types.type as vehicle_type');
    
        if ($request->has('category_id')) {
            $query->where('products.category_id', $request->input('category_id'));
        }
    
        if ($request->has('vehicle_type_id')) {
            $query->where('products.vehicle_type_id', $request->input('vehicle_type_id'));
        }
    
        $products = $query->paginate(10);
    
        return response()->json($products);
    }

        public function search(Request $request)
    {
        $query = DB::table('products')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('vehicle_types', 'products.vehicle_type_id', '=', 'vehicle_types.id')
            ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'vehicle_types.type as vehicle_type');

        if ($request->has('search')) {
            $query->where(function($query) use ($request) {
                $query->where('products.oem_code', 'like', '%' . $request->input('search') . '%')
                      ->orWhere('products.product_code', 'like', '%' . $request->input('search') . '%');
            });
        }

        $products = $query->paginate(10);

        return response()->json($products);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image',
            'motor_model' => 'required|string|max:255',
            'oem_code' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
        ],
        [
            'name.required' => 'Ürün ismi alanı gereklidir.',
            'name.string' => 'Ürün ismi alanı bir metin olmalıdır.',
            'name.max' => 'Ürün ismi alanı en fazla 255 karakter olmalıdır.',
            'description.required' => 'Açıklama alanı gereklidir.',
            'description.string' => 'Açıklama alanı bir metin olmalıdır.',
            'image.required' => 'Resim alanı gereklidir.',
            'image.image' => 'Resim alanı bir resim dosyası olmalıdır.',
            'motor_model.required' => 'Motor modeli alanı gereklidir.',
            'motor_model.string' => 'Motor modeli alanı bir metin olmalıdır.',
            'motor_model.max' => 'Motor modeli alanı en fazla 255 karakter olmalıdır.',
            'oem_code.required' => 'OEM kodu alanı gereklidir.',
            'oem_code.string' => 'OEM kodu alanı bir metin olmalıdır.',
            'oem_code.max' => 'OEM kodu alanı en fazla 255 karakter olmalıdır.',
            'category_id.required' => 'Kategori ID alanı gereklidir.',
            'category_id.exists' => "Belirtilen kategori ID'si mevcut değil.",
            'brand_id.required' => "Marka ID'si alanı gereklidir.",
            'brand_id.exists' => "Belirtilen marka ID'si mevcut değil.",
            'vehicle_type_id.required' => "Araç tipi ID'si alanı gereklidir.",
            'vehicle_type_id.exists' => "Belirtilen araç tipi ID'si mevcut değil.",
        ]);
        
        $faker = Faker::create();

        $validatedData['product_code'] = $faker->unique()->bothify('???######'); 
        // Varsayılan product_code değeri olarak şu anki zamanı kullan
        $validatedData['stock_entry_date'] = time(); // Varsayılan stock_entry_date değeri

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $path;
        
            // Resmin tam URL'sini al
            $url = asset('storage/' . $path);
            $validatedData['image_url'] = $url;
        }
        
        $product = Product::create($validatedData);
        return response()->json($product, 201);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'motor_model' => 'required|string|max:255',
                'oem_code' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'vehicle_type_id' => 'required|exists:vehicle_types,id',
            ]);
            
    
            // Ürünü güncelle
            $product->update($validatedData);
    
            return response()->json($product, 200);
        } else {
            return response()->json(['error' => 'Ürün bulunamadı'], 404);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,$id)
    {
        $product = Product::findOrFail($id);

        // Resmi depodan (storage) sil
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Ürünü veritabanından sil
        $product->delete();

        return response()->json(null, 204);
    }

    public function updateImage(Request $request, $id)
   {
    $product = Product::find($id);

    if ($product) {
        $validatedData = $request->validate([
            'image' => 'required|image',
        ]);

        if ($request->hasFile('image')) {
            // Eski resmi sil
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Yeni resmi yükle
            $path = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $path;

            // Resmin tam URL'sini al
            $url = asset('storage/' . $path);
            $validatedData['image_url'] = $url;
        }

        // Ürünün resmini güncelle
        $product->update($validatedData);

        return response()->json($url, 200);
        } else {
            return response()->json(['error' => 'Ürün bulunamadı'], 404);
        }
    }


    public function show($id)
    {
    $product = Product::find($id);

    if ($product) {
        return response()->json($product,200);
    } else {
        return response()->json(['error' => 'Ürün bulunamadı'], 404);
    }
    }

    public function productSmall(Request $request){

        $query = DB::table('products')
        ->join('brands', 'products.brand_id', '=', 'brands.id')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->join('vehicle_types', 'products.vehicle_type_id', '=', 'vehicle_types.id')
        ->select('products.*', 'brands.name as brand_name', 'categories.name as category_name', 'vehicle_types.type as vehicle_type');

    if ($request->has('category_id')) {
        $query->where('products.category_id', $request->input('category_id'));
    }

    if ($request->has('vehicle_type_id')) {
        $query->where('products.vehicle_type_id', $request->input('vehicle_type_id'));
    }

    $products = $query->take(6)->get();

    return response()->json($products);
    }
}
