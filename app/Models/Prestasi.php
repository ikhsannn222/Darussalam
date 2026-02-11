<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    protected $table = 'prestasises';

    protected $fillable = [
        'name',
        'tanggal',
        'tingkat_kejuaraan',
        'description',
        'image',
    ];
}
