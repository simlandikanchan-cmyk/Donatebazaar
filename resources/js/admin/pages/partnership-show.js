'use strict';
(function(){
  var a = document.querySelector('.alert-ok');
  if (!a) return;
  setTimeout(function(){
    a.style.transition = 'opacity .4s, transform .4s';
    a.style.opacity = '0';
    a.style.transform = 'translateY(-6px)';
    setTimeout(function(){ a.remove(); }, 400);
  }, 4000);
})();
