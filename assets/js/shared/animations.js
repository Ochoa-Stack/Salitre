/* Animaciones de Scroll para Salitre */

(function () {
  'use strict';

  /* Opciones del Intersection Observer */
  var OBSERVER_OPTIONS = {
    threshold: 0.05,
    rootMargin: '0px 0px 50px 0px',
  };

  /* El sistema de animaciones de scroll se basa en Intersection Observer para detectar la visibilidad de los elementos */
  function initScrollAnimations() {
    // Respeto a prefers-reduced-motion
    var prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) {
      // Revelamos todo inmediatamente sin animación
      document.querySelectorAll('.fade-in').forEach(function (el) {
        el.classList.add('is-visible');
      });
      return;
    }

    var observer = new IntersectionObserver(onIntersect, OBSERVER_OPTIONS);

    /* Definimos los elementos a animar */
    document.querySelectorAll('.fade-in').forEach(function (el) {
      // Aplicamos delay escalonado si se especifica data-delay - ms
      var delay = el.dataset.delay;
      if (delay) {
        el.style.transitionDelay = delay + 'ms';
      }
      observer.observe(el);
    });

    /* Revelamos inmediatamente los elementos visibles */
    function checkVisibility() {
      var remaining = document.querySelectorAll('.fade-in:not(.is-visible)');
      remaining.forEach(function (el) {
        var rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight + 50 && rect.bottom > -50) {
          el.classList.add('is-visible');
          observer.unobserve(el);
        }
      });
      // Si ya no quedan elementos, quitamos el listener de scroll
      if (remaining.length === 0) {
        window.removeEventListener('scroll', onScrollFallback);
      }
    }

    function onScrollFallback() {
      requestAnimationFrame(checkVisibility);
    }

    // Verificamos al cargar
    setTimeout(checkVisibility, 100);
    // Verificamos al hacer scroll (por si el observer falla)
    window.addEventListener('scroll', onScrollFallback, { passive: true });
  }

  /* Cuando un elemento es visible pasa por 'is-visible' */
  function onIntersect(entries, observer) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target); // Solo se anima una vez
      }
    });
  }

  /* Inicialización automática al cargar el DOM y lo exponemos globalmente */
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollAnimations);
  } else {
    initScrollAnimations();
  }

  // Lo exponemos global para uso manual
  window.SalitreAnimations = {
    init: initScrollAnimations,
  };

})();
