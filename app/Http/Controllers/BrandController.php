<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return response()->json($brands);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:brands', // unique kuralı ekledik
        ]);
    
        $brand = Brand::create($validatedData);
        return response()->json($brand, 201);
    }
    
    public function destroy($id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            $brand->delete();
            return response()->json(['message' => 'Brand deleted successfully']);
        } else {
            return response()->json(['error' => 'Brand not found'], 404);
        }
    }
}
