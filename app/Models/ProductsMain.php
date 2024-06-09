<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsMain extends Model
{
    protected $table = 'products_main';

    protected $fillable = [
        'category_id',
        'brand_id',
        'type_id',
        'name',
        'price',
        'short_description',
        'description',
        'status',
    ];
}