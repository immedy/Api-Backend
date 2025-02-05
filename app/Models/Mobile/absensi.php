<?php

namespace App\Models\Mobile;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class absensi extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'absensis';
    protected $guarded = ['id'];
}
