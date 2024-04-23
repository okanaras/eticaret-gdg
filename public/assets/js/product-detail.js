const swiperThumbnail = new Swiper(".thumb-sliders", {
  speed: 400,
  navigation: {
    nextEl: ".thumb-next",
    prevEl: ".thumb-prev",
  },
  slidesPerView: 4,
  breakpoints: {
    // ekran olculerine gore kac tane slider gosterecek
    320: { slidesPerView: 3, spaceBetween: 20 },
    640: { slidesPerView: 3 },
    768: { slidesPerView: 4 },
    1024: { slidesPerView: 4 },
  },
});

const swiperBig = new Swiper(".big-slider", {
  speed: 400,
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  zoom: true,
  effect: "fade",
  fadeEffect: {
    crossFade: true,
  },
  slidesPerView: 1,
  thumbs: {
    swiper: swiperThumbnail,
  },

  // effect: "coverflow",
  // coverflowEffect: {
  //   rotate: 50,
  //   stretch: 0,
  //   depth: 100,
  //   modifier: 1,
  //   slideShadows: true,
  // },
});

$(document).ready(() => {
  // tiklanan thumb img e bir sonrakine kaydirma kodu
  $(".thumb-image").click(() => {
    // var index = $(this).parent().attr("data-swiper-slide-index"); // bu attr bulunmadigi icin asagidaki kodu yazdik

    var index = swiperThumbnail.clickedIndex; // tiklanan img indexi

    if (index == swiperThumbnail.slides.length - 1) {
      swiperThumbnail.slideTo(0, 500);
    } else {
      swiperThumbnail.slideTo(index, 500);
    }
  });
});
