/**
 * v_filtros.js — Filtros de productos y búsqueda de clientes
 *
 * OPTIMIZACIÓN CLIENTES:
 *   Antes: AJAX a filtro_cliente.php en cada keyup (latencia por tecla)
 *   Ahora: JSON cargado una vez en el <head> desde PHP → filtrado 100% local
 *
 *   Para activarlo, agrega esto en iventas2.php ANTES de cargar este script:
 *
 *       <script>
 *           const CLIENTES_DATA = <?php echo json_encode(
 *               array_map(fn($c) => [
 *                   'id'      => $c['id_cliente'],
 *                   'nombre'  => trim($c['Nombre_Cliente']),
 *                   'alias'   => $c['alias']             ?? '',
 *                   'curp'    => $c['curp']               ?? '',
 *                   'credito' => $c['credito_disponible'] ?? 0,
 *                   'limite'  => $c['Limite_credito']     ?? 0,
 *               ], $clientes)
 *           , JSON_UNESCAPED_UNICODE); ?>;
 *       </script>
 *
 *   filtro_cliente.php ya NO es necesario para la búsqueda normal.
 *   Se conserva solo como fallback si CLIENTES_DATA falla.
 */

$(document).ready(function () {

    // ── Estado local ─────────────────────────────────────────────────────────────
    let clienteSeleccionado = { id: null, nombre: null, credito: null };

    // ─────────────────────────────────────────────────────────────────────────────
    // 1. FILTRO DE PRODUCTOS POR TEXTO  (debounce 300ms)
    // ─────────────────────────────────────────────────────────────────────────────
    let searchTimer = null;

    $("#search").on("keyup keypress", function () {
        clearTimeout(searchTimer);
        const searchText = $(this).val().toLowerCase();

        searchTimer = setTimeout(() => {
            $.ajax({
                type:     "POST",
                url:      "filtro.php",
                data:     { search: searchText },
                dataType: "html",
                success:  response => $(".product-container").html(response),
                error:    ()       => console.error("Error al buscar productos.")
            });
        }, 300);
    });

    // ─────────────────────────────────────────────────────────────────────────────
    // 2. FILTRO DE PRODUCTOS POR CATEGORÍA
    // ─────────────────────────────────────────────────────────────────────────────
    $("#categoria").on("change", function () {
        $.ajax({
            type:     "POST",
            url:      "filtrar_categorias.php",
            data:     { categoria_id: $(this).val() },
            dataType: "html",
            success:  response => $(".product-container").html(response),
            error:    ()       => console.error("Error al filtrar los productos.")
        });
    });

    // ─────────────────────────────────────────────────────────────────────────────
    // 3. BÚSQUEDA DE CLIENTES — FILTRADO LOCAL CON JSON
    // ─────────────────────────────────────────────────────────────────────────────

    /**
     * Filtra CLIENTES_DATA localmente y reconstruye el <datalist>
     * sin tocar el servidor. Busca en nombre, alias y CURP.
     * Máximo 20 resultados para no saturar el datalist.
     */
    function filtrarClientesLocal(texto) {
        const datalist = document.getElementById('clientes');
        if (!datalist) return;

        // Fallback a AJAX si el JSON global no está disponible
        if (typeof CLIENTES_DATA === 'undefined') {
            console.warn("CLIENTES_DATA no encontrado, usando AJAX como fallback.");
            filtrarClientesAjax(texto);
            return;
        }

        if (texto.length < 2) {
            datalist.innerHTML = '';
            return;
        }

        const termino = texto.toLowerCase();

        const resultados = CLIENTES_DATA
            .filter(c =>
                c.nombre.toLowerCase().includes(termino) ||
                c.alias.toLowerCase().includes(termino)  ||
                c.curp.toLowerCase().includes(termino)
            )
            .slice(0, 20);

        datalist.innerHTML = resultados
            .map(c =>
                `<option value="${htmlEscape(c.nombre)}" ` +
                `data-id="${c.id}" ` +
                `data-credito="${c.credito}" ` +
                `data-limite="${c.limite}">` +
                `</option>`
            )
            .join('');
    }

    /** Fallback AJAX — solo si CLIENTES_DATA no está disponible */
    function filtrarClientesAjax(texto) {
        if (texto.length < 2) { $('#clientes').empty(); return; }
        $.ajax({
            type:     "POST",
            url:      "filtro_cliente.php",
            data:     { search: texto },
            dataType: "html",
            success:  response => $("#clientes").html(response),
            error:    ()       => console.error("Error al buscar clientes.")
        });
    }

    /**
     * Cuando el valor del input coincide exactamente con una opción del datalist
     * (el usuario seleccionó un cliente), extrae y guarda sus datos.
     */
    function aplicarClienteSeleccionado(valor) {
        const opt = document.querySelector(`#clientes option[value="${CSS.escape(valor)}"]`);

        if (opt && opt.dataset.id) {
            clienteSeleccionado = {
                id:      opt.dataset.id,
                nombre:  valor,
                credito: parseFloat(opt.dataset.credito) || 0,
                limite:  parseFloat(opt.dataset.limite)  || 0
            };
            $('#cliente_id').val(clienteSeleccionado.id);
            $('#cliente_credito').val(clienteSeleccionado.credito);
            if (typeof App !== 'undefined') {
                App.credito_disponible = clienteSeleccionado.credito;
            }
            console.log("Cliente seleccionado:", clienteSeleccionado);
        } else {
            clienteSeleccionado = { id: null, nombre: null, credito: null };
            $('#cliente_id').val('');
            $('#cliente_credito').val('0');
            if (typeof App !== 'undefined') App.credito_disponible = 0;
        }
    }

    // Escuchar cambios en el input de cliente
    $("#cliente").on("input change", function () {
        filtrarClientesLocal($(this).val());
        aplicarClienteSeleccionado($(this).val());
    });

    // Botón "Público en General"
    const publicoGeneralBtn = document.getElementById('publicoGeneralBtn');
    if (publicoGeneralBtn) {
        publicoGeneralBtn.addEventListener('click', () => {
            document.getElementById('cliente').value = 'Publico en General';
            $('#cliente_id').val('0');
            $('#cliente_credito').val('0');
            if (typeof App !== 'undefined') App.credito_disponible = 0;
            const lbl = document.getElementById('labelcredito');
            if (lbl) lbl.innerHTML = '';
        });
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // UTILIDAD
    // ─────────────────────────────────────────────────────────────────────────────
    function htmlEscape(str) {
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#39;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }
});
