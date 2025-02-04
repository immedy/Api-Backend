<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMobileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $validasiData = $request->only(['username','password']);
        if (!$token = Auth()->attempt($validasiData)){
            return response()->json(['Erorr' => "Tidak Di ijinkan"], 400);
        }
        
        $user = Auth::user();
        $device_id = $request->device_id;

        $userWithDeviceId = User::where('device_id', $device_id)->first();
        if ($userWithDeviceId && $userWithDeviceId->id != $user->id){
            return response()->json(['Error' => 'device sudah terdaftar'], 403);
        }
        if (empty($user->device_id)){
            $user->device_id = $device_id;
            $user->save();
        } else {
            if ($user->device_id != $device_id){
                return response()->json(['error' => 'device sudah terdaftar'], 403);
            }
        }
        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id'            => Auth()->user()->id,
                'pegawai_id'    => Auth()->user()->pegawai_id,
                'username'      => Auth()->user()->username,
                'password'      => Auth()->user()->password,
                'created_at'    => Auth()->user()->created_at,
                'updated_at'    => Auth()->user()->updated_at,
                'token'         => $token
            ],
            'Message' => "Authentikasi Sukses"
        ]);
    }
}
