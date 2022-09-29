// Initialize Swiper
var swiper = new Swiper(".mySwiper", {
    cssMode: false,
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    freeMode: true,
    mousewheel: false,
    keyboard: true,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true
    },
});

