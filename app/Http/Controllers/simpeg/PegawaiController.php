<?php

namespace App\Http\Controllers\simpeg;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function getpegawai (Request $request)
    {
        $user = Auth()->user();
        $data = [
            'id' => $user->id,
            'pegawai_id' =>  $user->pegawai_id,
            'nama_lengkap' => $user->Pegawai->gelar_depan . ' ' . $user->Pegawai->nama . ' ' . $user->Pegawai->gelar_belakang,
            'nip' => $user->Pegawai->nip,
            'jenis_absen' => $user->Pegawai->jenis_absen,
        ];
        return response()->json($data);
    }
}
