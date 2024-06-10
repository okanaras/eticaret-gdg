<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductTypes::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Product::class, 'main_product_id', 'id');
    }


    /**
     * ! ONEMLI NOT :
     * * products_main tablosunda egerki product_id olsaydi belongstoMany kullanibilinirdi.
     *   public function variants(): BelongsToMany
     *   {
     *       return $this->belongsToMany(Product::class);
     *   }
     *   // seklinde
     *
     *      Ornek2 : products_main tablosunda brand_id var o yuzden ->> 'return $this->belongsTo(Brand::class);' kullanbildik.

     * * products_main tablosunda product_id olmadigi icin hasMany kullandik yukarda
     *
     *  *********************************************************************************************************
     *
     */
}
