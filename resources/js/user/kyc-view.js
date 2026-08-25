(function(){
'use strict';

function openLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){
    if (e.key === 'Escape') closeLightbox();
});

document.addEventListener('click', function(e){
    var lb = e.target.closest('[data-action="lightbox-close"]');
    if (lb) {
        closeLightbox();
        return;
    }
    var opener = e.target.closest('[data-action="open-lightbox"]');
    if (opener) {
        e.preventDefault();
        openLightbox(opener.getAttribute('data-src'));
        return;
    }
    var reveal = e.target.closest('[data-action="reveal-account"]');
    if (reveal) {
        var acc = document.getElementById('accNum');
        if (acc) acc.style.filter = 'none';
        var accReveal = document.getElementById('accReveal');
        if (accReveal) accReveal.style.display = 'none';
    }
});

})();