(function() {
  // Definís el ancho inicial para comparar
  let currentBreakpoint = window.innerWidth < 768 ? 'mobile' : 'desktop';
  let resizeTimeout;

  window.addEventListener('resize', function() {
    clearTimeout(resizeTimeout);

    resizeTimeout = setTimeout(function() {
      let newBreakpoint = window.innerWidth < 768 ? 'mobile' : 'desktop';

      if (newBreakpoint !== currentBreakpoint) {
        // Actualizás el breakpoint y recargás
        currentBreakpoint = newBreakpoint;
        window.location.reload();
      }
    }, 250); // Espera 250ms después de que el usuario para de hacer resize
  });
})();
