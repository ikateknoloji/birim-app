<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        // Kullanıcıdan gerekli bilgileri alma ve doğrulama
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Şifre doğrulama eklemek için "confirmed" kuralını ekledik
        ], [
            'name.required' => 'Ad alanı zorunludur.',
            'email.required' => 'E-posta alanı zorunludur.',
            'email.email' => 'Lütfen geçerli bir e-posta adresi girin.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlıdır.',
            'password.required' => 'Şifre alanı zorunludur.',
            'password.min' => 'Şifre en az :min karakter olmalıdır.',
            'password.confirmed' => 'Şifreler uyuşmuyor.', // Şifreler uyuşmazsa bu hata mesajını gösterir
        ]);
    
        // Doğrulama başarısız ise hataları döndür
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Kullanıcıyı oluştur
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    
        // Başarılı yanıt döndürme (token dahil etmeden)
        return response()->json(['message' => 'Kullanıcı başarıyla kaydedildi'], 201);
    }    

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response()->json(
                [
                    "message" => "Invalid login details",
                ],
                401
            );
        }

        $user = User::where("email", $request["email"])->firstOrFail();

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "access_token" => $token,
            "token_type" => "Bearer",
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
