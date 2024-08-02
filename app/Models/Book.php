<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'title', 'description'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, BookCategory::class, 'book_id', 'category_id');
    }

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

}
