// Alpine.js CDN loader
(function(){
  if (!window.Alpine) {
    var script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js';
    script.defer = true;
    document.head.appendChild(script);
  }
})();
