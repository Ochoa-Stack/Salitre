"use strict";
/* Carrito — botón de paso a pago */
document.addEventListener("DOMContentLoaded", function () {

    /* El botón "Proceder al pago" es un enlace <a>, no un form.
       Añadimos feedback visual mientras el navegador navega. */
    const btnRequest = document.getElementById("btn-request");

    if (btnRequest) {
        btnRequest.addEventListener("click", function () {
            btnRequest.style.pointerEvents = "none";
            btnRequest.textContent = "Cargando...";
        });
    }
});
