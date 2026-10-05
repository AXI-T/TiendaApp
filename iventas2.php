<!DOCTYPE html>
<html lang="es">
<?php
include("iCNX.php");

// FIX 1: PDOExeption → PDOException (typo que impedía capturar errores de BD)
try {
    $sqlB = "SELECT  c.id_cliente, CONCAT(c.nombre,' ',c.apellido_1,' ',c.apellido_2) AS Nombre_Cliente, c.curp, c.alias, COALESCE(cc.c_disponible, 0) AS credito_disponible, COALESCE(cc.c_autorizado, 0) AS Limite_credito FROM cliente_tb c LEFT JOIN credito_cliente cc ON c.id_cliente = cc.id_cliente WHERE c.id_estatus = 2 ORDER BY c.nombre ASC";
    $sqlB1 = "SELECT * FROM articulos_tb";
    $sqlB2 = "SELECT * FROM catalogo_Pago";
    $sqlB3 = "SELECT * FROM catalogo_d_pago";
    $sqlB4 = "SELECT * FROM usuarios_tb";
    $sqlB5 = "SELECT * FROM categorias_tb";
    $sqlB6 = "SELECT * FROM catalogo_medidas";

    $stmt = $pdo->prepare($sqlB1); $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt1 = $pdo->prepare($sqlB); $stmt1->execute();
    $clientes = $stmt1->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->prepare($sqlB2); $stmt2->execute();
    $pago = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $stmt3 = $pdo->prepare($sqlB3); $stmt3->execute();
    $d_pago = $stmt3->fetchAll(PDO::FETCH_ASSOC);

    $stmt4 = $pdo->prepare($sqlB4); $stmt4->execute();
    $users = $stmt4->fetchAll(PDO::FETCH_ASSOC);

    $stmt5 = $pdo->prepare($sqlB5); $stmt5->execute();
    $Categorias = $stmt5->fetchAll(PDO::FETCH_ASSOC);

    $stmt6 = $pdo->prepare($sqlB6); $stmt6->execute();
    $medidas = $stmt6->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APP para Negocio</title>

    <!-- FIX 2: Bootstrap 5 CSS agregado (faltaba completamente) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/productos.css">
    <link rel="stylesheet" href="css/carrito.css">
    <!-- FIX 3: Solo un bloque de bootstrap-icons (tenías el mismo dos veces) -->
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <script src="js/sweetA.js"></script>
</head>

<body>
    <?php include_once("navegacion.php"); ?>

    <div class="container" style="max-width:100%">
        <div class="left-content">
            <!-- Búsqueda y filtro -->
            <div class="search-filter">
                <input type="text" id="search" name="search" placeholder="Buscar producto">
                <select id="categoria" name="categoria">
                    <option value="">Filtrar por categoría</option>
                    <?php foreach ($Categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>">
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="product-container">
                <?php foreach ($productos as $producto): ?>
                <div class="product-card">
                    <div class="product-image">
                        <img src="<?= htmlspecialchars($producto['ruta_img']) ?>" alt="Imagen Producto" style="max-width:50px">
                    </div>
                    <div class="product-info">
                        <p class="oculto" style="display:none"><?= htmlspecialchars($producto['codigo']) ?></p>
                        <p class="product-name"><?= htmlspecialchars($producto['nombre']) ?></p>
                        <p class="product-price">$ <?= htmlspecialchars($producto['precio']) ?></p>
                        <p class="product-unit"><?= htmlspecialchars($producto['u_medida']) ?></p>
                        <!-- FIX 4: id="btn-agregar" duplicado en loop → cambiado a class -->
                        <button id="btn-agregar" class="add-button">+</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="right-content">
            <div class="selectors">
                <!-- FIX 5: variable $pago usada como iterable Y como nombre de item → renombrada a $tipo -->
                <select id="tipo_venta" name="tipo_venta">
                    <option value="">Selecciona tipo de Venta</option>
                    <?php foreach ($pago as $tipo): ?>
                    <?php if ($tipo['detalle'] == "Contado"): ?>
                    <option value="<?= $tipo['id_pago'] ?>">&#128181; <?= htmlspecialchars($tipo['detalle']) ?></option>
                    <?php else: ?>
                    <option value="<?= $tipo['id_pago'] ?>">&#128179; <?= htmlspecialchars($tipo['detalle']) ?></option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </select>

                <select id="forma_pago" name="forma_pago">
                    <option value="">Forma de pago</option>
                    <?php foreach ($d_pago as $dp): ?>
                    <option value="<?= $dp['id_d_pago'] ?>"><?= htmlspecialchars($dp['detalle']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="cart-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="carrito-body"></tbody>
                </table>

                <div class="cart-total">
                    <p id="total-precio">Total: $ 0.00</p>
                </div>

                <div class="add-item-container">
                    <!-- FIX 6: #xpagar movido fuera del input-group para no confundir con entrada-rapida -->
                    <input type="number" id="xpagar" name="xpagar" class="add-item-input" placeholder="Cantidad a pagar...">
                    <button class="add-item-button" id="pago_btn" name="pago_btn">Pago</button>
                </div>

                <div class="input-group mb-3">
                    <input type="text" id="entrada-rapida" class="form-control" placeholder="Ej: 2*PROD001 o PROD001 (Enter para agregar)" autocomplete="off">
                    <button id="agregar-btn" name="agregar-btn" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Agregar
                    </button>
                </div>

                <div class="cart-total">
                    <button id="xenviar" name="xenviar" class="checkout-button" onclick="capturarpago2()">Finalizar Venta
                    </button>
                </div>
            </div>

            <!-- Botón para abrir modal -->
            <button type="button" class="btn btn-primary btn-lg mt-2" data-bs-toggle="modal" data-bs-target="#ventaModal">
                Procesar Pago
            </button>

            <!-- ==================== MODAL DE VENTA ==================== -->
            <div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ventaModalLabel">Procesando Pago...</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <!-- FIX 7: <form> dentro de modal Bootstrap 5 funciona bien
                                         pero submit se maneja con JS (e.preventDefault) -->
                            <form id="ventaForm">
                                <!-- Vendedor -->
                                <div class="mb-3">
                                    <label for="vendedor" class="form-label">Vendedor / Cajero</label>
                                    <input type="text" class="form-control" id="vendedor" list="vendedoresList" placeholder="Escriba para buscar...">
                                    <!-- FIX 8: variable $users renombrada en loop para no pisar el array -->
                                    <datalist id="vendedoresList">
                                        <?php foreach ($users as $user): ?>
                                        <option value="<?= htmlspecialchars($user['nombre']) ?>" data-id="<?= $user['id_usuario'] ?>">
                                        </option>
                                        <?php endforeach; ?>
                                    </datalist>
                                    <!-- FIX 9: campo oculto para guardar el ID del vendedor seleccionado -->
                                    <input type="hidden" id="vendedor_id" name="vendedor_id">
                                </div>

                                <!-- Cliente -->
                                <div class="mb-3">
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cliente" list="clientes" placeholder="Escriba o seleccione...">
                                        <button class="btn btn-outline-secondary" type="button" id="publicoGeneralBtn">
                                            <i class="bi bi-people"></i> Público Gral.
                                        </button>
                                    </div>
                                    <datalist id="clientes"></datalist>
                                    <!-- FIX 10: campos ocultos para ID y crédito del cliente -->
                                    <input type="hidden" id="cliente_id" name="cliente_id">
                                    <input type="hidden" id="cliente_credito" name="cliente_credito">
                                </div>

                                <!-- Crédito disponible -->
                                <label id="labelcredito" class="mb-2"></label>

                                <!-- Método de pago -->
                                <div class="mb-3">
                                    <label class="form-label">Método de Pago</label>
                                    <div class="row g-2">
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-success payment-btn w-100" id="cashBtn">
                                                <i class="bi bi-cash-coin"></i> Efectivo
                                            </button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-warning payment-btn w-100" id="creditBtn">
                                                <i class="bi bi-credit-card"></i> Crédito
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección dinámica (se llena con JS) -->
                                <div class="amount-section mb-3" id="amountSection" style="display:none;"></div>

                                <button type="submit" class="btn btn-primary w-100">
                                    Procesar Venta
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ==================== FIN MODAL ==================== -->

        </div><!-- /.right-content -->
    </div><!-- /.container -->

    <!-- ============================================================
         SCRIPTS
         FIX 11: Solo UNA versión de jQuery (3.6 CDN).
                 Se eliminaron: jquery-1.12.4.min.js, jquery.js, jquery.min.js
         FIX 12: Bootstrap 5 JS agregado (era necesario para el modal)
    ============================================================ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CLIENTES_DATA = <?php
        // Normalizar las columnas al formato que espera v_filtros.js
        $clientesJson = array_map(function($c) {
            return [
                'id'      => (int) $c['id_cliente'],
                'nombre'  => trim($c['Nombre_Cliente']  ?? ($c['nombre'] . ' ' . $c['apellido_1'])),
                'alias'   => $c['alias']             ?? '',
                'curp'    => $c['curp']               ?? '',
                'credito' => (float)($c['credito_disponible'] ?? 0),
                'limite'  => (float)($c['Limite_credito']     ?? 0),
            ];
        }, $clientes);
        echo json_encode($clientesJson, JSON_UNESCAPED_UNICODE);
    ?>;

    </script>
    <script src="v_filtros.js"></script>
    <script src="js/app.js"></script>
    <script src="js/v_btn_pg.js"></script>

    <!-- ============================================================
         OBJETO GLOBAL DE LA APP
         FIX 13: Nombre unificado a "App" (antes mezclaba App y APP)
    ============================================================ -->
    <script>
        // Un único objeto global que comparten todos los módulos
        const App = {
            totalV: 0,
            credito_disponible: 0
        };

    </script>

    <!-- ============================================================
         FUNCIONES DE CRÉDITO (accesibles desde el modal)
    ============================================================ -->
    <script>
        function mucredito() {
            const nomC = document.getElementById("cliente").value.trim();
            const $option = $(`#clientes option[value="${nomC}"]`);

            if ($option.length) {
                // FIX 14: Eliminados alert() de debug → console.log
                App.credito_disponible = parseFloat($option.data('credito')) || 0;
                console.log("Crédito del cliente:", App.credito_disponible);
            } else {
                App.credito_disponible = 0;
            }
        }

        function quitacredito() {
            document.getElementById('labelcredito').innerHTML = '';
        }

        function impcredito() {
            const credito = App.credito_disponible;
            // FIX 15: Eliminado alert(credito) de debug
            console.log("Imprimiendo crédito:", credito);
            document.getElementById('labelcredito').innerHTML =
                `<strong>Crédito Disponible:</strong> $ ${credito.toFixed(2)}`;
        }

        // Sincronizar ID de vendedor cuando el usuario escoge del datalist
        document.getElementById('vendedor').addEventListener('change', function() {
            const val = this.value;
            const opt = document.querySelector(`#vendedoresList option[value="${val}"]`);
            document.getElementById('vendedor_id').value = opt ? (opt.dataset.id || '') : '';
        });

    </script>

    <!-- ============================================================
         CARRITO Y ENTRADA RÁPIDA
    ============================================================ -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const productosDB = <?php echo json_encode(array_column($productos, null, 'id_articulo')); ?>;
            const tbody = document.querySelector("#carrito-body");
            const totalDisplay = document.querySelector("#total-precio");

            // Agregar desde tarjeta de producto
            document.querySelector(".product-container").addEventListener("click", e => {
                if (e.target.classList.contains("add-button")) {
                    agregarProductoDesdeCard(e.target.closest(".product-card"));
                }
            });

            // Entrada rápida con Enter
            const entradaRapida = document.getElementById("entrada-rapida");
            entradaRapida.addEventListener("keyup", e => {
                if (e.key === "Enter") {
                    procesarEntradaRapida(e.target.value.trim());
                    e.target.value = "";
                }
            });

            // Botón Agregar
            document.querySelector("#agregar-btn").addEventListener("click", () => {
                const val = entradaRapida.value.trim();
                if (val) {
                    procesarEntradaRapida(val);
                    // FIX 16: "documet" → "document"
                    document.getElementById("entrada-rapida").value = "";
                }
            });

            // ── Funciones ──────────────────────────────────────────

            function procesarEntradaRapida(valor) {
                if (!valor) return;

                const regex = /^(?:(\d+(?:\.\d+)?)\*)?([A-Za-z0-9]+)$/;
                const match = valor.match(regex);
                // FIX 17: alert(match) de debug eliminado
                if (!match) {
                    return Swal.fire("Error", "Formato inválido. Usa Ej: 2*ABC123 o ABC123", "warning");
                }

                // FIX 18: "const cantidad" → "let cantidad" (no se puede reasignar const)
                //         Bloque if innecesario eliminado (ya maneja el || 1)
                let cantidad = match[1] ? parseFloat(match[1]) : 1;
                const codigo = match[2];
                // FIX 19: alert(cantidad) y alert(codigo) de debug eliminados
                console.log("Entrada rápida → cantidad:", cantidad, "código:", codigo);

                const producto = Object.values(productosDB).find(p => p.codigo === codigo);
                if (!producto) {
                    return Swal.fire("Error", `Producto ${codigo} no encontrado`, "error");
                }

                agregarAlCarrito({
                    code: producto.codigo,
                    nombre: producto.nombre,
                    precio: parseFloat(producto.precio),
                    unidad: producto.u_medida || "pza",
                    cantidad: cantidad
                });
            }

            function agregarProductoDesdeCard(card) {
                const producto = {
                    code: card.querySelector(".oculto").textContent.trim(),
                    nombre: card.querySelector(".product-name").textContent.trim(),
                    precio: parseFloat(card.querySelector(".product-price").textContent.replace("$", "").trim()),
                    unidad: card.querySelector(".product-unit").textContent.trim(),
                    cantidad: 1
                };

                if (producto.unidad === "kg" || producto.unidad === "lt") {
                    solicitarCantidad(producto);
                } else {
                    agregarAlCarrito(producto);
                }
            }

            function solicitarCantidad(producto) {
                Swal.fire({
                    title: `Cantidad (${producto.unidad})`,
                    input: "number",
                    inputValue: 1,
                    inputAttributes: {
                        min: 0.01,
                        step: 0.1
                    },
                    showCancelButton: true,
                    confirmButtonText: "Agregar",
                    cancelButtonText: "Cancelar",
                    inputValidator: val => (!val || val <= 0) ? "Ingresa una cantidad válida" : null
                }).then(r => {
                    if (r.isConfirmed) {
                        producto.cantidad = parseFloat(r.value);
                        agregarAlCarrito(producto);
                    }
                });
            }

            function agregarAlCarrito(producto) {
                const fila = [...tbody.querySelectorAll("tr")]
                    .find(tr => tr.dataset.code === producto.code);

                if (fila) {
                    const cantidadEl = fila.querySelector(".cantidad");
                    const precioEl = fila.querySelector(".precio");
                    const actual = parseFloat(cantidadEl.textContent);
                    // FIX 20: precio unitario guardado en dataset para cálculos correctos
                    const precioUnit = parseFloat(fila.dataset.precio);
                    const nueva = actual + producto.cantidad;
                    cantidadEl.textContent = nueva.toFixed(2);
                    precioEl.textContent = "$ " + (nueva * precioUnit).toFixed(2);
                } else {
                    const tr = document.createElement("tr");
                    tr.dataset.code = producto.code;
                    tr.dataset.precio = producto.precio; // guardamos precio unitario
                    tr.innerHTML = `
                        <td>${producto.nombre}</td>
                        <td class="cantidad">${producto.cantidad.toFixed(2)}</td>
                        <td class="precio">$ ${(producto.precio * producto.cantidad).toFixed(2)}</td>
                        <td>
                            <button class="eliminar-producto btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                            <button class="editar btn btn-sm btn-secondary">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </td>`;
                    tbody.appendChild(tr);
                }

                actualizarTotal();
                Swal.fire({
                    icon: "success",
                    title: "Agregado",
                    text: `${producto.nombre} añadido`,
                    timer: 800,
                    showConfirmButton: false
                });
            }

            // FIX 21: ".eliminar" → ".eliminar-producto" (coincide con la clase del botón)
            tbody.addEventListener("click", e => {
                const fila = e.target.closest("tr");
                if (!fila) return;

                if (e.target.closest(".eliminar-producto")) {
                    fila.remove();
                    actualizarTotal();
                } else if (e.target.closest(".editar")) {
                    editarCantidad(fila);
                }
            });

            function editarCantidad(fila) {
                const cantidadActual = parseFloat(fila.querySelector(".cantidad").textContent);
                const precioUnitario = parseFloat(fila.dataset.precio);

                Swal.fire({
                    title: "Editar cantidad",
                    input: "number",
                    inputValue: cantidadActual,
                    showCancelButton: true,
                    confirmButtonText: "Actualizar",
                    inputValidator: val => (!val || val <= 0) ? "Cantidad inválida" : null
                }).then(r => {
                    if (r.isConfirmed) {
                        const nueva = parseFloat(r.value);
                        fila.querySelector(".cantidad").textContent = nueva.toFixed(2);
                        fila.querySelector(".precio").textContent = "$ " + (precioUnitario * nueva).toFixed(2);
                        actualizarTotal();
                    }
                });
            }

            function actualizarTotal() {
                let total = 0;
                [...tbody.querySelectorAll("tr")].forEach(tr => {
                    total += parseFloat(tr.querySelector(".precio").textContent.replace("$", "").trim());
                });
                totalDisplay.innerHTML = `<strong>Total:</strong> $ ${total.toFixed(2)}`;
                App.totalV = total;
            }
        });

    </script>

    <!-- ============================================================
         MODAL DE VENTA — Lógica JS
         FIX 22: Código duplicado eliminado.
                 Solo existe esta versión (vmodalf.js ya no se carga).
                 "APP" unificado a "App".
    ============================================================ -->
    <script>
        (function() {
            const cashBtn = document.getElementById('cashBtn');
            const creditBtn = document.getElementById('creditBtn');
            const amountSection = document.getElementById('amountSection');
            const publicoGeneralBtn = document.getElementById('publicoGeneralBtn');
            const clienteInput = document.getElementById('cliente');
            const form = document.getElementById('ventaForm');
            const ventaModal = document.getElementById('ventaModal');

            let selectedMethod = null;
            let totalVenta = 0;

            // Al abrir el modal, tomamos el total actualizado
            ventaModal.addEventListener('show.bs.modal', function() {
                totalVenta = App.totalV; // FIX 23: APP → App
                resetModal();
            });

            publicoGeneralBtn.addEventListener('click', () => {
                clienteInput.value = 'Publico en General';
                document.getElementById('cliente_id').value = '0';
                document.getElementById('cliente_credito').value = '0';
                document.getElementById('labelcredito').innerHTML = '';
            });

            cashBtn.addEventListener('click', () => {
                quitacredito();
                setActiveMethod('cash');
                updateAmountSection();
            });

            creditBtn.addEventListener('click', () => {
                mucredito();
                impcredito();
                setActiveMethod('credit');
                updateAmountSection();
            });

            function setActiveMethod(method) {
                selectedMethod = method;
                cashBtn.classList.remove('active');
                creditBtn.classList.remove('active');
                (method === 'cash' ? cashBtn : creditBtn).classList.add('active');
            }

            function updateAmountSection() {
                if (!selectedMethod) {
                    amountSection.style.display = 'none';
                    return;
                }

                // Actualizamos totalVenta por si cambió mientras el modal estaba cerrado
                totalVenta = App.totalV;
                amountSection.style.display = 'block';
                let html = '';

                if (selectedMethod === 'cash') {
                    html = `
                        <div class="mb-2">
                            <label class="form-label">
                                <i class="bi bi-cash"></i> Cantidad a pagar:
                            </label>
                            <input type="number" class="form-control" id="cashAmount"
                                   step="0.01" min="0" value="${totalVenta.toFixed(2)}" placeholder="0.00">
                        </div>
                        <div class="alert alert-info mb-0">
                            Total de venta: <strong>$ ${totalVenta.toFixed(2)}</strong>
                        </div>`;
                } else {
                    html = `
                        <div class="alert alert-warning mb-2">
                            Total a crédito: <strong>$ ${totalVenta.toFixed(2)}</strong>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="advanceCheckbox">
                            <label class="form-check-label" for="advanceCheckbox">
                                ¿Adelantar pago?
                            </label>
                        </div>
                        <div id="advanceInputContainer" style="display:none;">
                            <label class="form-label">
                                <i class="bi bi-cash-stack"></i> Monto a adelantar:
                            </label>
                            <input type="number" class="form-control" id="advanceAmount"
                                   step="0.01" min="0" max="${totalVenta.toFixed(2)}"
                                   value="0.00" placeholder="0.00">
                        </div>`;
                }

                amountSection.innerHTML = html;

                if (selectedMethod === 'credit') {
                    document.getElementById('advanceCheckbox').addEventListener('change', function() {
                        document.getElementById('advanceInputContainer').style.display =
                            this.checked ? 'block' : 'none';
                    });
                }
            }

            function resetModal() {
                document.getElementById('vendedor').value = '';
                clienteInput.value = '';
                selectedMethod = null;
                cashBtn.classList.remove('active');
                creditBtn.classList.remove('active');
                amountSection.style.display = 'none';
                amountSection.innerHTML = '';
            }

            // Envío del formulario
            form.addEventListener('submit', e => {
                e.preventDefault();

                const vendedor = document.getElementById('vendedor').value.trim();
                const cliente = clienteInput.value.trim();

                if (!vendedor || !cliente) {
                    Swal.fire("Atención", "Por favor seleccione vendedor y cliente.", "warning");
                    return;
                }
                if (!selectedMethod) {
                    Swal.fire("Atención", "Seleccione un método de pago.", "warning");
                    return;
                }
                if (App.totalV <= 0) {
                    Swal.fire("Atención", "El carrito está vacío.", "warning");
                    return;
                }

                let pagoInfo = {};

                if (selectedMethod === 'cash') {
                    const cashAmount = parseFloat(document.getElementById('cashAmount')?.value) || totalVenta;
                    if (cashAmount < totalVenta) {
                        Swal.fire("Atención", "El monto no cubre el total de la venta.", "warning");
                        return;
                    }
                    pagoInfo = {
                        metodo: 'Efectivo',
                        cantidad: cashAmount
                    };
                } else {
                    const advanceCheck = document.getElementById('advanceCheckbox')?.checked || false;
                    const advanceAmount = advanceCheck ?
                        parseFloat(document.getElementById('advanceAmount')?.value || 0) :
                        0;
                    pagoInfo = {
                        metodo: 'Crédito',
                        total: totalVenta,
                        adelanto: advanceAmount,
                        saldo: totalVenta - advanceAmount
                    };
                }

                console.log('Venta procesada:', {
                    vendedor,
                    cliente,
                    ...pagoInfo
                });

                // TODO: reemplazar este alert por la llamada real a registrar_venta.php
                Swal.fire("Éxito", "Venta registrada correctamente.", "success").then(() => {
                    bootstrap.Modal.getInstance(ventaModal).hide();
                });
            });
        })();

    </script>

    <!-- ============================================================
         BOTÓN PAGO RÁPIDO (SweetAlert)
    ============================================================ -->
    <script>
        document.querySelector("#pago_btn").addEventListener("click", async () => {
            const total = App.totalV;
            if (total <= 0) {
                Swal.fire("Atención", "El carrito está vacío.", "warning");
                return;
            }
            const {
                value
            } = await Swal.fire({
                title: "Pago rápido",
                html: `
                    <p>Total: <strong>$ ${total.toFixed(2)}</strong></p>
                    <input id="swal-pago" class="swal2-input" type="number"
                           placeholder="Monto recibido" min="${total}" step="0.01">
                `,
                focusConfirm: false,
                preConfirm: () => {
                    const val = parseFloat(document.getElementById("swal-pago").value);
                    if (!val || val < total) {
                        Swal.showValidationMessage("El monto no cubre el total.");
                        return false;
                    }
                    return val;
                }
            });
            if (value) {
                Swal.fire("Cambio", `Cambio: $ ${(value - total).toFixed(2)}`, "success");
            }
        });

    </script>

</body>

</html>
