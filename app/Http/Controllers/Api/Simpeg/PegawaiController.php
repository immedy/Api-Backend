<?php

namespace App\Http\Controllers\Api\Simpeg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    public function getpegawai (Request $request)
    {
        $user = Auth::user(); // Tidak pakai tanda kurung Auth()
    
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    if (!$user->pegawai) {
        return response()->json(['message' => 'Pegawai data not found'], 404);
    }

    $data = [
        'id' => $user->id,
        'pegawai_id' => $user->pegawai_id,
        'username' => $user->username,
        'nama_lengkap' => trim($user->Pegawai->gelar_depan . ' ' . $user->Pegawai->nama . ' ' . $user->Pegawai->gelar_belakang),
        'nip' => $user->Pegawai->nip,
        'jenis_absen' => $user->Pegawai->jenis_absen,
    ];
    
    return response()->json($data);
    }
}
