(function(){
'use strict';

var pageData = JSON.parse(document.getElementById('eventViewData').textContent);
var percentage = pageData.percentage;
var partPct = pageData.partPct;

/* ── THEME ── */
var html   = document.documentElement;
var toggle = document.getElementById('themeToggle');
var saved  = localStorage.getItem('theme') || 'light';
if (saved === 'dark') { html.setAttribute('data-theme','dark'); toggle.checked = true; }
toggle.addEventListener('change', function(){
    var t = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);
});

/* ── HAMBURGER ── */
var sidebar   = document.getElementById('sidebar');
var hamburger = document.getElementById('hamburger');
hamburger.addEventListener('click', function(e){
    e.stopPropagation();
    sidebar.classList.toggle('open');
});
document.addEventListener('click', function(e){
    if (window.innerWidth <= 860 && !sidebar.contains(e.target) && e.target !== hamburger)
        sidebar.classList.remove('open');
});

/* ── ANIMATE PROGRESS BARS ON SCROLL INTO VIEW ── */
function animateBars(){
    var pf = document.getElementById('progFill');
    var ptf = document.getElementById('partFill');
    if (pf) {
        setTimeout(function(){ pf.style.width = percentage + '%'; }, 400);
    }
    if (ptf) {
        setTimeout(function(){ ptf.style.width = partPct + '%'; }, 500);
    }
}

if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){ if(e.isIntersecting){ animateBars(); obs.disconnect(); } });
    }, { threshold: 0.2 });
    var card = document.querySelector('.card');
    if (card) obs.observe(card);
} else {
    setTimeout(animateBars, 600);
}

})();