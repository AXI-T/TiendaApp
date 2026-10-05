/**
 * v_btn_pg.js — Captura y envío de la venta por botón "Finalizar Venta"
 *
 * FIXES aplicados:
 *  - #clients   → #cliente_id   (campo oculto que v_filtros.js llena con el ID real)
 *  - #personal  → #vendedor_id  (campo oculto que iventas2.php llena al elegir vendedor)
 *  - Selectores de columnas del carrito actualizados para coincidir con el HTML real:
 *      .cod-producto      → tr.dataset.code
 *      .nombre-producto   → td:nth-child(1)
 *      .cantidad-producto → .cantidad
 *      .precio-producto   → .precio
 *  - alert() de debug → console.log / Swal.fire
 */

function capturarpago2() {

    // FIX: IDs corregidos para coincidir con los campos reales del HTML
    const clienteID = document.getElementById("cliente_id")?.value || "";
    const vendedorID = document.getElementById("vendedor_id")?.value || "";
    const tipoVentaID = document.getElementById("tipo_venta")?.value || "";
    const formaPagoID = document.getElementById("forma_pago")?.value || "";

    // Total leído desde el objeto global (más confiable que parsear el DOM)
    const totalventa = parseFloat(App.totalV) || 0;
    const pagocapturado = parseFloat(document.getElementById("xpagar")?.value) || 0;

    // Validaciones previas
    if (!clienteID) {
        Swal.fire("Atención", "Selecciona un cliente antes de finalizar.", "warning");
        return;
    }
    if (!vendedorID) {
        Swal.fire("Atención", "Selecciona un vendedor antes de finalizar.", "warning");
        return;
    }
    if (totalventa <= 0) {
        Swal.fire("Atención", "El carrito está vacío.", "warning");
        return;
    }
    if (isNaN(pagocapturado)) {
        Swal.fire("Error", "El monto a pagar no es un número válido.", "error");
        return;
    }
    if (pagocapturado < totalventa) {
        Swal.fire("Pago insuficiente", 'Faltan $' + (totalventa - pagocapturado).toFixed(2) + ' para cubrir el total', "warning");
        return;
    }

    // FIX: Leer columnas del carrito con los selectores reales del HTML
    const productos = [];
    const filas = document.querySelectorAll("#carrito-body tr");

    filas.forEach(fila => {
        const tds = fila.querySelectorAll("td");
        if (tds.length < 3) return; // fila mal formada, ignorar

        const code = fila.dataset.code || ""; // FIX: dataset.code
        const nombre = tds[0].textContent.trim(); // FIX: primer td
        const cantidad = fila.querySelector(".cantidad")?.textContent.trim() || "0"; // FIX: clase .cantidad
        const precio = fila.querySelector(".precio")
            ?.textContent.replace("$", "").trim() || "0"; // FIX: clase .precio

        productos.push({
            code,
            nombre,
            cantidad,
            precio
        });
    });

    console.log("Enviando venta:", {
        clienteID,
        vendedorID,
        tipoVentaID,
        formaPagoID,
        totalventa,
        pagocapturado,
        productos
    });

    $.ajax({
        type: "POST",
        url: "registrar_venta.php",
        data: {
            cliente_id: clienteID,
            user_id: vendedorID,
            tipo_venta_id: tipoVentaID,
            forma_pago_id: formaPagoID,
            monto_pagar: totalventa,
            pagorecibido: pagocapturado,
            productos: JSON.stringify(productos)
        },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                Swal.fire("Éxito", "Venta registrada con éxito.", "success").then(() => {
                    // Limpiar carrito y campos
                    document.getElementById("carrito-body").innerHTML = "";
                    document.getElementById("xpagar").value = "";
                    document.getElementById("total-precio").innerHTML = "<strong>Total:</strong> $ 0.00";
                    App.totalV = 0;
                });
            } else {
                Swal.fire("Error", "Error al registrar la venta: " + response.message, "error");
            }
        },
        error: function (xhr, status, err) {
            console.error("Error AJAX:", status, err);
            Swal.fire("Error", "Error en la comunicación con el servidor.", "error");
        }
    });
}
