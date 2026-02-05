<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'date',
        'description',
        'image',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return asset('images/default-image.png');
    }
}
