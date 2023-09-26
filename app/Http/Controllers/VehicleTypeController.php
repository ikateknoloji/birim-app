<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleType;

class VehicleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicleTypes = VehicleType::all();
        return response()->json($vehicleTypes);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type' => 'required|string|max:255|unique:vehicle_types',
        ], [
            'type.required' => 'Tip alanı gereklidir.',
            'type.string' => 'Tip alanı bir metin olmalıdır.',
            'type.max' => 'Tip alanı en fazla 255 karakter olmalıdır.',
            'type.unique' => 'Bu tipde bir araç zaten mevcut.',
        ]);

        $vehicleType = VehicleType::create($validatedData);
        return response()->json($vehicleType, 201);
    }
    public function destroy(VehicleType $vehicleType)
{
    // Veritabanında araç türünü bulunamazsa
    if (!$vehicleType) {
        return response()->json(['message' => 'Araç türü bulunamadı'], 404);
    }

    // Araç türünü sil
    $vehicleType->delete();

    return response()->json(['message' => 'Araç türü başarıyla silindi'], 200);
}

}
