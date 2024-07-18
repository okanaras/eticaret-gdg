<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountProducts extends Model
{
    use HasFactory, SoftDeletes;

    // bu egerki tabloda timestamps alanlari yoksa yazilmasi gerekli yoksa store, update ve delete gibi isteklerde hata aliriz benim su asamada var o yuzden commentliyorunm
    // public $timestamps = false;

    protected $fillable = [
        'discount_id',
        'product_id',
    ];
}