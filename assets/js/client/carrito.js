"use strict";
/* Carrito de Compras - Confirmación de Solicitud */
document.addEventListener("DOMContentLoaded", function () {

    /* Confirmación previa a la solicitud */
    const formCheckout = document.getElementById("form-carrito-checkout");
    const btnRequest = document.getElementById("btn-request");

    if (formCheckout && btnRequest) {

        formCheckout.addEventListener("submit", function(e) {
            var conf = confirm("¿Confirmar y enviar reserva para las fechas seleccionadas?\n\nRecuerda que no hay cargos sorpresa.");

            if (!conf) {
                e.preventDefault();
                return;
            }
            // El form se envía normalmente si el usuario confirma
            btnRequest.disabled = true;
            btnRequest.textContent = "Procesando...";
        });
    }
});
