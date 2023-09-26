<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\VehicleType;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $vehicleTypes = VehicleType::all();
        $brands = Brand::all(); // Marka verilerini almak için ekledik
    
        return response()->json([
            'categories' => $categories,
            'vehicle_types' => $vehicleTypes,
            'brands' => $brands, // Marka verilerini JSON yanıtına ekledik
        ]);
    }
    
    
    
    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255|unique:categories',
        'image' => 'required|image',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $validatedData['image'] = $path;
    }

    $category = Category::create($validatedData);
    return response()->json($category, 201);
   }

   public function destroy(Category $category)
{
    // Kategori var mı kontrol et
    if ($category) {
        // Kategorinin resim dosyasını silelim
        if ($category->image) {
            // Resim dosyasının yolunu bul
            $imagePath = public_path('storage/' . $category->image);

            // Resim dosyasını sil
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Kategoriyi veritabanından sil
        $category->delete();

        // Başarılı bir yanıt döndür
        return response()->json(['message' => 'Kategori ve resim başarıyla silindi'], 200);
    } else {
        // Kategori bulunamadıysa hata mesajı döndür
        return response()->json(['message' => 'Kategori bulunamadı'], 404);
    }
}


}
