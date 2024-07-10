@extends('layouts.front')

@section('title', ucfirst($product->name))

@push('css')
@endpush

@section('body')
    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="product-image-wrapper position-relative">
                        <div class="swiper-container big-slider">
                            <div class="swiper-wrapper">
                                @foreach ($product->variantImages as $image)
                                    <div class="swiper-slide big-image">
                                        <div class="swiper-zoom-container">
                                            <img src="{{ asset($image->path) }}" />
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- If we need navigation buttons -->
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                        <div thumbsSlider="" class="swiper-container thumb-sliders swiper-thumbs">
                            <div class="swiper-wrapper">
                                @foreach ($product->variantImages as $image)
                                    <div class="swiper-slide ">
                                        <img class="thumb-image" src="{{ asset($image->path) }}" />
                                    </div>
                                @endforeach

                            </div>
                            <div class="thumb-sliders-buttons text-center">
                                <span class="thumb-prev me-4">
                                    <i class="bi bi-arrow-left"></i>
                                </span>
                                <span class="thumb-next">
                                    <i class="bi bi-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 product-detail position-relative">
                    <h4 class="fw-bold-600">{{ $product->name }}</h4>
                    <div class="price text-orange fw-bold-600">{{ number_format($product->final_price, 2) }} TL</div>
                    <hr class="mt5">
                    <h6>{{ $product->productsMain->category->name }}</h6>
                    <hr>
                    <h6>{{ $product->productsMain->brand->name }}</h6>
                    <hr>
                    <p class="product-short-description">{{ $product->productsMain->short_description }}</p>
                    <div class="shopping">
                        <div class="row">
                            <div class="col-md-1 text-center">
                                <i class="bi bi-heart text-orange"></i>
                            </div>
                            <div class="col-md-5">
                                <div class="piece-wrapper">
                                    <div class="input-group">
                                        <span class="piece-decrease"> - </span>
                                        <input type="text" class="piece" value="0" disabled>
                                        <span class="piece-increase"> + </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select id="footSize" class="form-control text-center">
                                        <option disabled selected>Beden</option>
                                        @foreach ($product->sizeStock as $size)
                                            <option value="{{ $size->id }}">{{ $size->size }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="col-md-12">
                                <a href="" class="btn bg-orange add-to-card w-100 text-white">Sepete Ekle</a>
                            </div>
                        </div>
                    </div>
                    <div class="discount-rate">
                        %20 <span>Indirim</span>
                    </div>
                </div>

                <div class="col-md-12 mt-4">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Urun Hakkinda
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    {!! $product->productsMain->description !!}
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Teslimat
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia dolore dolorem ut
                                    excepturi unde blanditiis voluptas. Saepe iure cum sint quibusdam? Ratione alias
                                    omnis, recusandae reiciendis ut ducimus praesentium minus esse tempore tempora
                                    quaerat fuga quis sunt cum quas eum corrupti soluta ipsa voluptas exercitationem!
                                    Repellat nemo neque doloremque, voluptas expedita dicta animi non ipsa consequatur
                                    quidem beatae, voluptate nulla unde ducimus soluta vero accusamus, illo recusandae!
                                    Veniam vero consequuntur aliquid blanditiis, dolorem, dolores molestias
                                    necessitatibus saepe molestiae quia dignissimos dolore at perferendis asperiores
                                    magni commodi, libero est incidunt ducimus. Ex, nemo explicabo fugiat aperiam
                                    inventore tempore excepturi possimus cum.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('js')
    <script src="{{ asset('assets/js/product-detail.js') }}"></script>
@endpush
