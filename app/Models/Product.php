<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['is_top' => 'boolean', 'price_idr' => 'integer', 'sold_count' => 'integer', 'gallery_urls' => 'array'];
}
