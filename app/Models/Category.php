<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'slug',
        'short_description',
        'description',
        'parent_id',
    ];

    // iliskiler
    /**
     * Laravel iliskiler
     * products tablosunda egerki brand_id varsa belongs lar kullanilir.
     * Yoksa eger has ler kullanilir
     *
     * Ornek: product tablosunda brand_id var iliskisi, belongsto brand::class
     *
     * Ornek2: products tablosunda category_id yok ama categories tablsounda product_id var ise
     * Product modelinde has kullabilmasi gerek
     */

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id')->with('children'); // with ile gelen cocugun cocuklari varsa onlari getiriyor. Recursive fonk gibi
    }

    public function parentCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }
}