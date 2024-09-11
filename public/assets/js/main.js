function highlightPage() {
    const page = window.location.pathname;

    if (page) {
        $(`li a[href="${page}"]`).addClass('active');
    }
}

function handleImageError() {
    $('img').each(function () {
        const img = $(this);
        const fallbackImage = '/assets/img/image_placeholder.png';

        img.on('error', function () {
            if (this.src !== fallbackImage) {
                img.attr('src', fallbackImage);
            }
        });

        const imgElement = new Image();

        imgElement.src = img.attr('src');
        imgElement.onerror = function () {
            img.trigger('error');
        };
    });
}

$(document).ready(function () {
    highlightPage();
    handleImageError();
});