<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'main_product_id',
        'name',
        'variant_name',
        'slug',
        'additional_price',
        'final_price',
        'extra_description',
        'status',
        'publish_date',
    ];

    public function getRouteKey(): string
    // buradaki gelistirme web.php de {product} default olarak id yi baz aliyor.
    // Biz daha once route bindingi ogrenmek icin burada slug a cevirdik.
    // Ama daha kullanisli hali ilgili modelden obje olusturutup o objenin setKeyName fonk una gondermektir.
    // or : $product = new Product; $product->setKeyName('slug'); daha sonra $product::query()... || Seklinde dinamik kullanmak gerekir. Aksi takdir de her zaman slug baz alinacak
    {
        return 'slug';
    }

    public function variantImages(): HasMany
    {
        return $this->hasMany(ProductImages::class);
    }

    public function featuredImage(): HasOne
    {
        return $this->hasOne(ProductImages::class)->where('is_featured', 1);
    }

    public function sizeStock(): HasMany
    {
        return $this->hasMany(SizeStock::class);
    }

    public function activeProductsMain(): BelongsTo
    {
        return $this->belongsTo(ProductsMain::class, 'main_product_id', 'id')->where('status', 1);
    }

    public function productsMain(): BelongsTo
    {
        return $this->belongsTo(ProductsMain::class, 'main_product_id', 'id');
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discounts::class, 'discount_products', 'product_id', 'discount_id')
            ->withPivot('deleted_at');
    }

    // local scope
    public function scopeWithRelations($query)
    {
        return $query->with(['productsMain', 'productsMain.category', 'productsMain.brand', 'variantImages']);
    }

    // global scope
    protected static function booted()
    {
        // static::addGlobalScope('activeProudctMain', function (Builder $builder) {
        //     $builder->with(['productsMain', 'productsMain.category', 'productsMain.brand', 'variantImages']);
        // });
    }

    /**
     * NOT : local scope ile global scope arasindaki fark local scope modele bind edilmiyor. Manuel olarak query edilmesi gerekir.
     * ORNK: $product = $product->query()->withRelations()->where('slug', $request->slug)->firstOrFail();
     *
     * Global Scope ise model her cagrildiginda iliskiler bastan bind edilir.
     */
}