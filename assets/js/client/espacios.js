"use strict";
/* Espacios - Funcionalidades comunes para el catálogo y detalle */
document.addEventListener("DOMContentLoaded", function () {
    /* Filtros del catálogo */
    const filterBtns = document.querySelectorAll(".filter-btn");
    const cards = document.querySelectorAll(".catalog-card");

    if (filterBtns.length > 0 && cards.length > 0) {
        filterBtns.forEach(function (btn) {
            btn.addEventListener("click", function () {
                // Quitar clase active
                filterBtns.forEach(b => {
                    b.classList.remove("active");
                    b.setAttribute("aria-selected", "false");
                });
                // Ponemos el active al botón clickeado
                this.classList.add("active");
                this.setAttribute("aria-selected", "true");
                
                const type = this.getAttribute("data-tipo").toLowerCase();

                // Filtramos las cards según el tipo seleccionado
                cards.forEach(card => {
                    const cardType = card.getAttribute("data-tipo").toLowerCase();
                    // Reiniciamos las animaciones de 'fade'
                    card.classList.remove('is-visible');
                    card.style.display = "none";
                    
                    if (type === "todos" || cardType === type) {
                        card.style.display = "flex";
                        // Declaramos un pequeño timeout para forzar re-flow y reactivar transición de la card
                        setTimeout(() => {
                            card.classList.add("is-visible");
                        }, 50);
                    }
                });
            });
        });
    }
    /* Galería de imágenes en detalle */
    const thumbs = document.querySelectorAll(".detalle-galeria__thumb");
    const mainImg = document.getElementById("main-gallery-img");

    if (thumbs.length > 0 && mainImg) {
        thumbs.forEach(thumb => {
            thumb.addEventListener("click", function () {
                // Remover active class
                thumbs.forEach(t => t.classList.remove("active"));
                this.classList.add("active");

                // Actualizamos la imagen principal
                const imgElement = this.querySelector("img");
                const fullSrc = imgElement.getAttribute("data-full");
                
                // Aplicamos un efecto de fade simple al cambiar la imagen
                mainImg.style.opacity = 0;
                setTimeout(() => {
                    mainImg.src = fullSrc;
                    mainImg.style.opacity = 1;
                }, 150);
            });
        });
    }
    /* Lógica de fechas y cálculo de total en detalle */
    const fechaEntrada = document.getElementById("fecha_entrada");
    const fechaSalida = document.getElementById("fecha_salida");
    const btnCart = document.getElementById("btn-add-cart");
    // 
    const previewBox = document.getElementById("booking-total-preview");
    const labelNights = document.getElementById("booking-nights");
    const labelSubtotal = document.getElementById("booking-subtotal");
    // Inicialmente deshabilitamos el botón de carrito y la vista previa
    if (fechaEntrada && fechaSalida) {
        
        // Al interactuar con la fecha de entrada
        fechaEntrada.addEventListener("change", function() {
            if (this.value) {
                // Habilitamos salida y seteamos como MIN el día siguiente
                const entDate = new Date(this.value);
                // Sumamos 1 dia para la salida base
                const minSalida = new Date(entDate);
                minSalida.setDate(minSalida.getDate() + 1);
                // Aplicamos el formato YYYY-MM-DD
                const minString = minSalida.toISOString().split("T")[0];
                fechaSalida.min = minString;
                fechaSalida.disabled = false;
                // Si la salida guardada previamente es ilógica, la resetamos
                if (fechaSalida.value && fechaSalida.value <= this.value) {
                    fechaSalida.value = minString;
                }
            } else {
                fechaSalida.disabled = true;
                fechaSalida.value = "";
            }
            calcularTotal();
        });

        fechaSalida.addEventListener("change", calcularTotal);

        function calcularTotal() {
            if (!fechaEntrada.value || !fechaSalida.value) {
                btnCart.disabled = true;
                previewBox.style.display = "none";
                return;
            }

            const inDate = new Date(fechaEntrada.value);
            const outDate = new Date(fechaSalida.value);
            // Evitamos timezones mess 
            const diffTime = outDate.getTime() - inDate.getTime();
            const days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (days > 0 && window.ESPACIO_PRECIO) {
                const subT = days * parseFloat(window.ESPACIO_PRECIO);
                // Actualizamos la interfaz
                labelNights.textContent = days;
                // Formateamos el número
                labelSubtotal.textContent = subT.toLocaleString("en-US", {minimumFractionDigits: 2});
                
                previewBox.style.display = "block";
                btnCart.disabled = false;
            } else {
                btnCart.disabled = true;
                previewBox.style.display = "none";
            }
        }
    }
});
