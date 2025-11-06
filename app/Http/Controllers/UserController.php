<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            "username" => 'required|unique:users,username',
            "fullname" => 'required',
            "password" => "required"
        ]);

        if ($validateData->fails()) {
            return response()->json([
                "message" => "Data Wajib Diisi!",
                "data" => null
            ], 422);
        }

        $user = User::create([
            "username" => $request->username,
            "fullname" => $request->fullname,
            "password" => $request->password,
            "role" => "admin"
        ]);

        return response()->json([
            "message" => "Daftar Berhasil!",
            "data" => $user
        ]);
    }

    public function login(Request $request)
    {
        $validateData = Validator::make($request->all(), [
            "username" => 'required',
            "password" => "required"
        ]);

        if ($validateData->fails()) {
            return response()->json([
                "message" => "Data Wajib Diisi!",
                "data" => null
            ], 422);
        }

        $user = User::where("username", $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Nama / Kata Sandi Salah!",
                "data" => null
            ], 422);
        }

        $token = $user->createToken("access_token")->plainTextToken;
        return response()->json([
            "message" => "Masuk Berhasil!",
            "data" => $user,
            "token" => $token
        ]);
    }

    public function logout(Request $request)
    {
        $user = PersonalAccessToken::findToken($request->bearerToken());
        $user->delete();
        return response()->json([
            "message" => "Keluar Berhasil!",
            "data" => $user
        ]);
    }

    public function me()
    {
        $user = Auth::user();
        return response()->json([
            "message" => "Data Berhasil Diambil!",
            "data" => $user
        ]);
    }

    public function update(Request $request) 
    {
        $auth = Auth::user();
        $user = User::where("id", $auth->id)->first();

        $user->update([
            "fullname" => $request->fullname ?? $user->fullname,
            "username" => $request->username ?? $user->username,
            "password" => $user->password,
            "role" => $user->role,
        ]);

        return response()->json([
            "message" => "Data Berhasil Diubah!",
            "data" => null
        ]);
    }
}
