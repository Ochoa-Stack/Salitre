/* Definición de variables y funciones principales - navbar, menú hamburguesa, dropdown */
document.addEventListener('DOMContentLoaded', () => {
    
    // Compactamos el navbar al hacer scroll para mejorar la visibilidad del contenido
    const nav = document.querySelector('.nav-main');
    window.addEventListener('scroll', () => {
        nav?.classList.toggle('scrolled', window.scrollY > 80);
    }, { passive: true });
    // Agreagamos referencias comunes para el manejo del menú y dropdown
    const burger = document.querySelector('.nav-burger');
    const navMenu = document.querySelector('.nav-menu');
    const navDropdown = document.querySelector('.nav-dropdown');
    const navOverlay = document.querySelector('.nav-overlay');
    // Funciones para abrir/cerrar dropdown y menú móvil - auxiliares
    function openDropdown() {
        navDropdown?.classList.add('active');
        navOverlay?.classList.add('active');
        burger?.setAttribute('aria-expanded', 'true');
    }
    
    function closeDropdown() {
        navDropdown?.classList.remove('active');
        navOverlay?.classList.remove('active');
        burger?.setAttribute('aria-expanded', 'false');
    }
    
    function openMobileMenu() {
        navMenu?.classList.add('open');
        navOverlay?.classList.add('active');
        burger?.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        navMenu?.classList.remove('open');
        navOverlay?.classList.remove('active');
        burger?.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }
    // Definimos el toggle del menú hamburguesa - el comportamiento es adaptativo según el tamaño de pantalla
    burger?.addEventListener('click', (e) => {
        e.stopPropagation();
        
        if (window.innerWidth >= 1280) {
            // Aplicamos el toggle dropdown - solo en desktop
            const isOpen = navDropdown?.classList.contains('active');
            isOpen ? closeDropdown() : openDropdown();
        } else {
            // Aplicamos el toggle sidebar menu - solo en mobile O tablet
            const isOpen = navMenu?.classList.contains('open');
            isOpen ? closeMobileMenu() : openMobileMenu();
        }
    });
    // Cerramos el dropdown o menú móvil al hacer click fuera de ellos (en el overlay)
    navOverlay?.addEventListener('click', () => {
        closeDropdown();
        closeMobileMenu();
    });
    // Cerramos el dropdown o menú móvil al hacer click en un enlace de navegación
    [...(navDropdown?.querySelectorAll('a') || []), 
     ...(navMenu?.querySelectorAll('a') || [])].forEach(link => {
        link.addEventListener('click', () => {
            closeDropdown();
            closeMobileMenu();
        });
    });
    // Cerramos el dropdown o menú móvil al presionar la tecla Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeDropdown();
            closeMobileMenu();
        }
    });
    // Evitamos que clicks dentro del dropdown o menú cierren el overlay - propagación
    navDropdown?.addEventListener('click', (e) => e.stopPropagation());
    navMenu?.addEventListener('click', (e) => e.stopPropagation());
    // Agregamos smooth scroll para enlaces internos (anclas)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            e.preventDefault();
            const target = document.querySelector(anchor.getAttribute('href'));
            target?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
    
});
