document.addEventListener('DOMContentLoaded', function() {
    let thumbs = document.querySelectorAll('.thumb');
    for (let i = 0; i < thumbs.length; i++) {
        const thumb = thumbs[i];
        thumb.addEventListener('click', function() {
            let mainImage = document.querySelector('#main-image');
            let mainImageSrc = mainImage.getAttribute('src');
            let src = this.getAttribute('src');
            mainImage.setAttribute('src', src);
            this.setAttribute('src', mainImageSrc);
        });
    }
});
