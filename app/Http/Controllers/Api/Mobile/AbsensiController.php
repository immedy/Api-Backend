<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Mobile\absensi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function getUser(Request $request)
    {
        $user = auth()->user(); // Mendapatkan objek user yang terautentikasi
        $userData = [
            'id' => $user->id,
            'pegawai_id' =>  $user->pegawai_id,
            'nama_lengkap' => $user->Pegawai->gelar_depan . '. ' . $user->Pegawai->nama . ', ' . $user->Pegawai->gelar_belakang,
            'nip' => $user->Pegawai->nip,
            'jenis_absen' => $user->Pegawai->jenis_absen,
        ];

        return response()->json($userData);
    }

    public function scanQRcodeAbsenMasuk(Request $request)
    {
        $user = auth()->user();
        $pegawaiId = $user->pegawai_id;
        $jenisAbsen = $user->Pegawai->jenis_absen;
        $today = Carbon::today()->format('Y-m-d');
        $qrCode = $request->input('qrcode');
        $dateqrcodemasuk = $today . "qrcodeabsenmasuk";
        if ($qrCode === $dateqrcodemasuk ) {
            $tanggalabsen = now()->format('Y-m-d');
            $jamabsen = now()->format('H:i:s');
            $absensi = new absensi();
            $absensi->pegawai_id = $pegawaiId;
            $absensi->absensi_tanggal = $tanggalabsen;
            $absensi->absensi_waktu = $jamabsen;
            $absensi->jenis_absen = $jenisAbsen;
            $absensi->status_absen = 0;
            $absensi->save();

            return response()->json([
                'success' => true,
                'data' => $absensi,
                'message' => 'Absensi Masuk berhasil'
            ], 200);
        } else {
            return response()->json(['message' => 'QR code tidak valid'], 400);
        }
    }

    public function scanQRcodeAbsenPulang(Request $request)
    {
        $user = auth()->user();
        $pegawaiId = $user->pegawai_id;
        $jenisAbsen = $user->Pegawai->jenis_absen;
        $qrCode = $request->input('qrcode');
        $today = Carbon::today()->format('Y-m-d');
        $dateqrcodepulang = $today . "qrcodeabsenpulang";
        if ($qrCode === $dateqrcodepulang) {
            $tanggalabsen = now()->format('Y-m-d');
            $jamabsen = now()->format('H:i:s');
            $absensi = new absensi ();
            $absensi->pegawai_id = $pegawaiId;
            $absensi->absensi_tanggal = $tanggalabsen;
            $absensi->absensi_waktu = $jamabsen;
            $absensi->jenis_absen = $jenisAbsen;
            $absensi->status_absen = 1;
            $absensi->save();

            return response()->json([
                'success' => true,
                'data' => $absensi,
                'message' => 'Absensi Pulang Berhasil'
            ], 200);
        } else {
            return response()->json(['message' => 'QR code tidak valid'], 400);
        }
    }

    public function auth()
    {
        return $this->user()->can('update', $this->user());
    }

    public function rules()
    {
        $user = $this->user()->id;
        return [
            'device_id' => 'required|string|unique:users,device_id,' . $user,
        ];
    }
    public function addDeviceId(Request $request)
    {

        $user = $request->user();

        $validated = $request->validate([
            'device_id' => 'required|string|unique:users,device_id,' . $user->id,
        ], [
            'unique_device_id_check' => 'This device already exists for the user.',
        ]);

        $user->device_id = $validated['device_id'];
        $user->save();

        return response()->json([
            'success' => true,
            'device_id' => $user->device_id,
            'message' => 'Device ID updated successfully',
        ], 200);
    }
    public function listEmployee()
    {
        $user = Auth()->user();
        $employee = absensi::where('pegawai_id', $user->pegawai_id)->latest()->paginate(40);
    
        // Ubah status_absen dari 0 dan 1 menjadi "Masuk" dan "Pulang"
        $employee->getCollection()->transform(function ($item) {
            $item->status_absen = $item->status_absen == 0 ? 'Masuk' : 'Pulang';
            $item->absensi_tanggal = Carbon::parse($item->absensi_tanggal)->isoFormat('dddd ,D-MMMM-YYYY');
            return $item;
        });
    
        return response()->json($employee);

    }

}
