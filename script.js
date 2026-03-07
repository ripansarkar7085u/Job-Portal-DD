
var swiper = new Swiper(".testimonial-slider", {

slidesPerView: 3,
spaceBetween: 30,
loop: true,

autoplay: {
delay: 3000,
},

pagination: {
el: ".swiper-pagination",
clickable: true,
},

breakpoints: {

0: {
slidesPerView: 1
},

768: {
slidesPerView: 2
},

992: {
slidesPerView: 3
}

}

});
