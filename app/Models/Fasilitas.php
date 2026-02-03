<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'facilities';

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return asset('assets/img/no-image.png'); // optional
    }
}
