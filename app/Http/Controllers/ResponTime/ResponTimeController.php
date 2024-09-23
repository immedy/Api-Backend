<?php

namespace App\Http\Controllers\ResponTime;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResponTimeController extends Controller
{
    public function getUser()
    {
        $user = Auth()->user();
        $data = [
            'id' => $user->id,
            'username' => $user->username,
        ];
        return response()->json($data);
    }
}
