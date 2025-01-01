<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'section_id',
        'product_name',
        'description',
    ];

    // protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
