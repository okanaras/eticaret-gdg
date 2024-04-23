@extends('layouts.front')

@section('title', 'Urun Detay Sayfasi')

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
                                <div class="swiper-slide big-image">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ asset('assets/images/product1.jpeg') }}" />
                                    </div>
                                </div>
                                <div class="swiper-slide big-image">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ asset('assets/images/product2.webp') }}" />
                                    </div>
                                </div>
                                <div class="swiper-slide big-image">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ asset('assets/images/product3.jpeg') }}" />
                                    </div>
                                </div>
                                <div class="swiper-slide big-image">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ asset('assets/images/product4.jpeg') }}" />
                                    </div>
                                </div>
                                <div class="swiper-slide big-image">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ asset('assets/images/product5.jpeg') }}" />
                                    </div>
                                </div>
                                <div class="swiper-slide big-image">
                                    <div class="swiper-zoom-container">
                                        <img src="{{ asset('assets/images/product6.jpeg') }}" />
                                    </div>
                                </div>
                            </div>

                            <!-- If we need navigation buttons -->
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                        <div thumbsSlider="" class="swiper-container thumb-sliders swiper-thumbs">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide ">
                                    <img class="thumb-image" src="{{ asset('assets/images/product1.jpeg') }}" />
                                </div>
                                <div class="swiper-slide ">
                                    <img class="thumb-image" src="{{ asset('assets/images/product2.webp') }}" />
                                </div>
                                <div class="swiper-slide ">
                                    <img class="thumb-image" src="{{ asset('assets/images/product3.jpeg') }}" />
                                </div>
                                <div class="swiper-slide ">
                                    <img class="thumb-image" src="{{ asset('assets/images/product4.jpeg') }}" />
                                </div>
                                <div class="swiper-slide ">
                                    <img class="thumb-image" src="{{ asset('assets/images/product5.jpeg') }}" />
                                </div>
                                <div class="swiper-slide ">
                                    <img class="thumb-image" src="{{ asset('assets/images/product6.jpeg') }}" />
                                </div>
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
                    <h4 class="fw-bold-600">Niteball</h4>
                    <div class="price text-orange fw-bold-600">199,99 TL</div>
                    <hr class="mt5">
                    <h6>Unisex Sneaker</h6>
                    <hr>
                    <h6>Adidas</h6>
                    <hr>
                    <p class="product-short-description">
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Repellendus, dolor porro sed
                        distinctio
                        est, neque labore expedita atque dolorem optio a. Omnis eligendi non numquam.
                    </p>
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
                                        <option value="36">36</option>
                                        <option value="37">37</option>
                                        <option value="38">38</option>
                                        <option value="39">39</option>
                                        <option value="40">40</option>
                                        <option value="41">41</option>
                                        <option value="42">42</option>
                                        <option value="43">43</option>
                                        <option value="44">44</option>
                                        <option value="45">45</option>
                                        <option value="46">46</option>
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
                                    <h6>Lorem ipsum dolor sit amet.</h6>
                                    <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Accusantium doloremque
                                        dolor deserunt repellat fugit maiores, labore provident adipisci illum ullam
                                        modi repellendus possimus consequatur ex quia illo minima quibusdam numquam.
                                        Inventore explicabo deserunt voluptate? Non, quia architecto itaque ullam nisi
                                        quas. Suscipit similique porro veniam ducimus, error enim vero quisquam.</p>
                                    <hr>
                                    <strong>Lorem, ipsum.</strong>
                                    <hr>
                                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Laudantium assumenda
                                        saepe velit tempora numquam, quos quas sequi ducimus fugiat placeat excepturi
                                        ipsum ipsam accusantium facilis, unde nostrum mollitia earum? Iure pariatur ex
                                        maiores nostrum! Nisi eos, odio architecto mollitia dolores quae vel eveniet
                                        ipsam laudantium suscipit voluptatibus iure tenetur alias eum autem eius magni
                                        molestiae maxime! Ducimus sint, dignissimos quam incidunt, ipsam ratione quia
                                        officiis quibusdam natus ad hic perferendis!</p>
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
