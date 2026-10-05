/**
 * v_filtros.js — Filtros de productos y búsqueda de clientes
 *
 * FIXES aplicados:
 *  - Tres bloques $(document).ready() separados fusionados en uno solo
 *  - amountSection estaba referenciado pero no definido en este scope → removido
 *  - obtenerDatosClienteSeleccionado movida a este archivo (antes era llamada sin estar definida)
 *  - alert() de debug eliminados
 */

$(document).ready(function () {

    // ── Mapa de clientes cargados en el datalist (para lookup rápido por nombre) ──
    // Se llena cuando el servidor responde a filtro_cliente.php
    const mapaClientes = {};
    let clienteSeleccionado = { id: null, nombre: null, credito: null };

    // ── Referencias DOM ──────────────────────────────────────────────────────────
    const publicoGeneralBtn = document.getElementById('publicoGeneralBtn');

    // ─────────────────────────────────────────────────────────────────────────────
    // 1. FILTRO DE PRODUCTOS POR TEXTO
    // ─────────────────────────────────────────────────────────────────────────────
    $("#search").on("keyup keypress", function () {
        const searchText = $(this).val().toLowerCase();

        $.ajax({
            type:     "POST",
            url:      "filtro.php",
            data:     { search: searchText },
            dataType: "html",
            success: function (response) {
                $(".product-container").html(response);
            },
            error: function () {
                console.error("Error al buscar productos.");
            }
        });
    });

    // ─────────────────────────────────────────────────────────────────────────────
    // 2. FILTRO DE PRODUCTOS POR CATEGORÍA
    // ─────────────────────────────────────────────────────────────────────────────
    $("#categoria").on("change", function () {
        const categoriaID = $(this).val();

        $.ajax({
            type:     "POST",
            url:      "filtrar_categorias.php",
            data:     { categoria_id: categoriaID },
            dataType: "html",
            success: function (response) {
                $(".product-container").html(response);
            },
            error: function () {
                console.error("Error al filtrar los productos.");
            }
        });
    });

    // ─────────────────────────────────────────────────────────────────────────────
    // 3. BÚSQUEDA Y SELECCIÓN DE CLIENTES
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Busca en el datalist #clientes si el valor escrito coincide exactamente
     * con alguna opción cargada, y devuelve sus datos (id, credito).
     * FIX: Esta función estaba siendo llamada pero no definida → ahora está aquí.
     */
    function obtenerDatosClienteSeleccionado(valor) {
        const opt = document.querySelector(`#clientes option[value="${CSS.escape(valor)}"]`);
        if (!opt) return null;
        return {
            id:      opt.dataset.id      || null,
            nombre:  opt.dataset.nombre  || valor,
            credito: opt.dataset.credito || "0"
        };
    }

    function mostrarclientes() {
        const searchcliente = document.getElementById('cliente').value.toLowerCase();

        if (searchcliente.length < 2) {
            $('#clientes').empty();
            return;
        }

        $.ajax({
            type:     "POST",
            url:      "filtro_cliente.php",
            data:     { search: searchcliente },
            dataType: "html",
            success: function (response) {
                // El PHP devuelve <option value="Nombre" data-id="1" data-credito="500.00">
                $("#clientes").html(response);
            },
            error: function () {
                console.error("Error al buscar clientes.");
            }
        });
    }

    function aplicarClienteSeleccionado(valor) {
        const datosCliente = obtenerDatosClienteSeleccionado(valor);

        if (datosCliente) {
            clienteSeleccionado = datosCliente;
            console.log("Cliente seleccionado:", clienteSeleccionado);

            // Actualizar campos ocultos
            $('#cliente_id').val(clienteSeleccionado.id);
            $('#cliente_credito').val(clienteSeleccionado.credito);

            // Mostrar crédito disponible si el label existe
            const creditoLabel = document.getElementById('creditoLabel');
            if (creditoLabel) {
                creditoLabel.textContent = `$ ${parseFloat(clienteSeleccionado.credito).toFixed(2)}`;
            }
        } else {
            // No hay coincidencia exacta aún (el usuario sigue escribiendo)
            clienteSeleccionado = { id: null, nombre: null, credito: null };
            $('#cliente_id').val('');
            $('#cliente_credito').val('0');
            const creditoLabel = document.getElementById('creditoLabel');
            if (creditoLabel) creditoLabel.textContent = '$ 0.00';
        }
    }

    // Evento en el input de cliente
    $("#cliente").on("keyup keypress click", function () {
        mostrarclientes();
        aplicarClienteSeleccionado($(this).val());
    });

    // Botón "Público en General"
    if (publicoGeneralBtn) {
        publicoGeneralBtn.addEventListener('click', () => {
            document.getElementById('cliente').value = 'Publico en General';
            mostrarclientes();
            // Resetear crédito para público general
            $('#cliente_id').val('0');
            $('#cliente_credito').val('0');
            const creditoLabel = document.getElementById('creditoLabel');
            if (creditoLabel) creditoLabel.textContent = '$ 0.00';
        });
    }

});
