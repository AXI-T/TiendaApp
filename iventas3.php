<!DOCTYPE html>
<html lang="es">
<?php
include("iCNX.php");

try{
    
    $sqlB = "SELECT * FROM cliente_tb WHERE id_estatus = 2";
    $sqlB1 = "SELECT * FROM articulos_tb";
    $sqlB2 = "SELECT * FROM catalogo_Pago";
    $sqlB3 = "SELECT * FROM catalogo_d_pago";
    $sqlB4 = "SELECT * FROM usuarios_tb";
    $sqlB5 = "SELECT * FROM categorias_tb";
    $sqlB6 = "SELECT * FROM catalogo_medidas";
    //-------------------------------
    $stmt = $pdo->prepare($sqlB1);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //-------------------------------
    $stmt1 = $pdo->prepare($sqlB);
    $stmt1->execute();
    $clientes = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //-------------------------------
    $stmt2 = $pdo->prepare($sqlB2);
    $stmt2->execute();
    $pago = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //-------------------------------
    $stmt3 = $pdo->prepare($sqlB3);
    $stmt3->execute();
    $d_pago = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //-------------------------------
    $stmt4 = $pdo->prepare($sqlB4);
    $stmt4->execute();
    $users = $stmt4->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //-------------------------------
    $stmt5 = $pdo->prepare($sqlB5);
    $stmt5->execute();
    $Categorias = $stmt5->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //-------------------------------
    $stmt6 = $pdo->prepare($sqlB6);
    $stmt6->execute();
    $medidas = $stmt6->fetchAll(PDO::FETCH_ASSOC);
    //-------------------------------
    //echo "<script> alert('se realizaron todas las operaciones');  ";

}catch (PDOExeption $e){
echo "Error en la conexión: " . $e->getMessage();

}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APP para Negocio</title>
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/productos.css">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.css">
    <script src="js/sweetA.js"></script>
</head>

<body>
    <!-- Inclusión del archivo de navegación -->
    <?php include_once("navegacion.php"); ?>
    <!--
    <iframe src="navegacion.php" style="border:none; width: 100%;"></iframe>
    -->

    <div class="container" style="max-width:100%">
        <div class="left-content">
            <!-- Sección de búsqueda y filtro -->
            <div class="search-filter">
                <input type="text" id="search" name="search" placeholder="Buscar producto">
                <select id="categoria" name="categoria">
                    <option value="">Filtrar por categoria</option>
                    <?php foreach ($Categorias as $categoria): ?>
                    <option value="<?= $categoria['id_categoria'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Contenido principal -->
            <h2>Sección Principal</h2>
            <p>Este contenedor ocupa el 60% de la página.</p>
            <div class="product-container">
                <!-- Carta de Producto -->
                <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $producto): ?>
                <div class="product-card">
                    <div class="product-image"><img src="<?= htmlspecialchars($producto['ruta_img']); ?>" alt="Imagen Producto" style="max-width: 50px;"></div>
                    <div class="product-info">
                        <p class="oculto" style="display : none;"><?= htmlspecialchars($producto['codigo']); ?></p>
                        <p class="product-name"><?= htmlspecialchars($producto['nombre']); ?></p>
                        <p class="product-price">$ <?= htmlspecialchars($producto['precio']); ?></p>
                        <p class="product-unit"><?= htmlspecialchars($producto['u_medida']); ?></p>
                        <button id="btn-agregar" class="add-button">+</button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <?php endif; ?>

            </div>
        </div>

        <div class="right-content">
            <!-- Contenido secundario -->
            <h2>Sección Secundaria</h2>
            <p>Este contenedor ocupa el 40% de la página.</p>


            <div class="selectors">
                <select id="tipo_venta" name="tipo_venta">
                    <option value=""> Selecciona tipo de Venta </option>
                    <?php foreach ($pago as $pago): ?>
                    <?php if ($pago['detalle'] == "Contado"): ?>
                    <option value="<?= $pago['id_pago'] ?>">&#128181 <?= htmlspecialchars($pago['detalle']) ?></option>
                    <?php else: ?>
                    <option value="<?= $pago['id_pago'] ?>"> &#128179; <?= htmlspecialchars($pago['detalle']) ?></option>
                    <?php endif ?>
                    <?php endforeach; ?>
                </select>
                <select id="forma_pago" name="forma_pago">

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
                    <tbody id="carrito-body">
                        <!-- aqui se cargaran los productos del carrito -->
                    </tbody>
                </table>
                <div class="cart-total">
                    <p id="total-precio">Total:</p>
                    <!--<button class="checkout-button">Finalizar Venda</button>-->
                </div>
                <div class="add-item-container">
                    <input type="text" id="xpagar" name="xpagar" class="add-item-input" placeholder="ingresa la cantidad a pagar...">
                    <button class="add-item-button" id="pago_btn" name="pago_btn">Pago</button>
                </div>
                <div class="input-group mb-3">
                    <input type="text" id="entrada-rapida" class="form-control" placeholder="Ej: 2*PROD001 o PROD001 (Enter para agregar)" autocomplete="off">
                    <button id="agregar-btn" name="agregar-btn" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Agregar
                    </button>
                    <div class="cart-total">
                        <button id="xenviar" name="xenviar" class="checkout-button" onclick="capturarpago2()">Finalizar Venda</button>
                    </div>
                </div>
            </div>
            <!-- Botón para abrir modal -->
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#ventaModal">
                Abrir Modal de Venta
            </button>

            <!-- Modal -->
            <div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="ventaModalLabel">Procesando Pago...</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <div class="modal-body">
                            <form id="ventaForm">
                                <!-- Seleccionar vendedor -->
                                <div class="mb-3">
                                    <label for="vendedor" class="form-label">Vendedor / Cajero</label>
                                    <input type="text" class="form-control" id="vendedor" list="vendedoresList" placeholder="Escriba para buscar...">
                                    <datalist id="vendedoresList">
                                        <?php foreach ($users as $users): ?>
                                        <option value="<?= htmlspecialchars($users['nombre']) ?>" data-id="<?= $users['id_usuario'] ?>"></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>

                                <!-- Cliente searchable con botón rápido -->
                                <div class="mb-3">
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cliente" list="clientes" placeholder="Escriba o seleccione...">
                                        <button class="btn btn-outline-secondary" type="button" id="publicoGeneralBtn">
                                            <i class="fas fa-users"></i> Publico Gral.
                                        </button>
                                    </div>
                                    <datalist id="clientes" class="clientesList"></datalist>
                                    <input type="hidden" id="cliente_id" name="cliente_id">
                                    <input type="hidden" id="cliente_credito" name="cliente_credito">
                                </div>
                                <!-- Métodos de pago con iconos -->
                                <div class="mb-3">
                                    <label class="form-label">Método de Pago</label>
                                    <div class="row g-2">
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-success payment-btn w-100" id="cashBtn">
                                                <i class="fas fa-money-bill-wave"></i> Efectivo
                                            </button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-outline-warning payment-btn w-100" id="creditBtn">
                                                <i class="fas fa-credit-card"></i> Crédito/Tarjeta
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sección dinámica de cantidad/adelanto -->
                                <div class="amount-section mb-3" id="amountSection" style="display: none;">
                                    <!-- Se llena con js -->
                                </div>
                                <label for="" id="labelcredito"></label>
                                <button type="submit" class="btn btn-primary w-100">Procesar Venta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap JS y Popper -->

        </div>
        <script src="bootstrap4j/js/jquery-1.12.4.min.js"></script>
        <script src="js/v_filtros.js"></script>
        <script src="js/app.js"></script>
        <script src="js/queryCliente.js"></script>
        <script src="js/v_btn_pg.js"></script>
        <script src="js/boostrap_para_moals.js"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="js/jquery.js"></script>
        <script src="js/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                let clienteSeleccionado = {
                    id: null,
                    nombre: null,
                    credito: null
                };

                function obtenerDatosCseleccionado(valorSeleccionado) {

                }
            });

        </script>
        <script>
            document.addEventListener("DOMContentLoaded", () => {

                // Base de datos de productos desde PHP
                const productosDB = <?php echo json_encode(array_column($productos, null, 'id_articulo')); ?>;
                const tbody = document.querySelector("#carrito-body");
                const totalDisplay = document.querySelector("#total-precio");

                // Delegar eventos en el contenedor principal de productos
                document.querySelector(".product-container").addEventListener("click", e => {
                    if (e.target.classList.contains("add-button")) {
                        const card = e.target.closest(".product-card");
                        agregarProductoDesdeCard(card);
                    }
                });

                // Búsqueda rápida con Enter
                const entradaRapida = document.getElementById("entrada-rapida");
                if (entradaRapida) {
                    entradaRapida.addEventListener("keyup", e => {
                        if (e.key === "Enter") {
                            procesarEntradaRapida(e.target.value.trim());
                            e.target.value = "";
                        }
                    });
                }
                document.querySelector("#agregar-btn").addEventListener("click", function(event) {
                    const entradaRapida = document.getElementById("entrada-rapida").value.trim();
                    if (entradaRapida) {
                        procesarEntradaRapida(entradaRapida);
                        documet.getElementById("entrada-rapida").value = "";
                    }
                });

                // === FUNCIONES ===

                function procesarEntradaRapida(valor) {
                    if (!valor) return;

                    //const regex = /^(\d+(?:\.\d+)?)?\*?([A-Za-z0-9]+)$/;
                    const regex = /^(?:(\d+(?:\.\d+)?)\*)?([A-Za-z0-9]+)$/;
                    const match = valor.match(regex);
                    alert(match);
                    if (!match) return Swal.fire("Error", "Formato inválido. Usa Ej: 2*ABC123 o ABC123", "warning");

                    //const cantidad = parseFloat(match[1]) || 1;
                    const cantidad = match[1] ? parseFloat(match[1]) : 1;
                    alert(cantidad);
                    const codigo = match[2];
                    alert(codigo);
                    if (cantidad == null || cantidad == '') {
                        cantidad = 1;
                    }

                    const producto = Object.values(productosDB).find(p =>
                        p.codigo === codigo
                    );

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
                    let fila = [...tbody.querySelectorAll("tr")]
                        .find(tr => tr.dataset.code == producto.code);

                    if (fila) {
                        const cantidadEl = fila.querySelector(".cantidad");
                        const precioEl = fila.querySelector(".precio");
                        const actual = parseFloat(cantidadEl.textContent);
                        const nueva = actual + producto.cantidad;
                        cantidadEl.textContent = nueva.toFixed(2);
                        precioEl.textContent = "$ " + (nueva * producto.precio).toFixed(2);
                    } else {
                        const tr = document.createElement("tr");
                        tr.dataset.code = producto.code;
                        tr.innerHTML = `
                <td>${producto.nombre}</td>
                <td class="cantidad">${producto.cantidad.toFixed(2)}</td>
                <td class="precio">$ ${(producto.precio * producto.cantidad).toFixed(2)}</td>
                <td><button class="eliminar-producto delete-button"><i class="bi bi-trash"></i></button></td>`;
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

                tbody.addEventListener("click", e => {
                    const fila = e.target.closest("tr");
                    if (!fila) return;

                    if (e.target.closest(".eliminar")) {
                        fila.remove();
                        actualizarTotal();
                    } else if (e.target.closest(".editar")) {
                        editarCantidad(fila);
                    }
                });

                function editarCantidad(fila) {
                    const cantidadActual = parseFloat(fila.querySelector(".cantidad").textContent);
                    const precioUnitario = parseFloat(fila.querySelector(".precio").textContent.replace("$", "")) / cantidadActual;

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
                //aqui actualizamos el total de la venta cada ves que se actualice el carrito
                function actualizarTotal() {
                    let total = 0;
                    [...tbody.querySelectorAll("tr")].forEach(tr => {
                        total += parseFloat(tr.querySelector(".precio").textContent.replace("$", ""));
                    });

                    totalDisplay.innerHTML = `<strong>Total:</strong> $ ${total.toFixed(2)}`;
                    App.totalV = total;

                }
            });
            //----------------------------------------------------------------------------
            (function() {
                const cashBtn = document.getElementById('cashBtn');
                const creditBtn = document.getElementById('creditBtn');
                const amountSection = document.getElementById('amountSection');
                const publicoGeneralBtn = document.getElementById('publicoGeneralBtn');
                const clienteInput = document.getElementById('cliente');
                const form = document.getElementById('ventaForm');
                const ventaModal = document.getElementById('ventaModal');
                let selectedMethod = null; // 'efectivo' o 'credito'
                // Evento cuando se abre el modal (resetear)
                ventaModal.addEventListener('show.bs.modal', function() {
                    resetModal();
                    totalVenta = App.totalV;
                });

                // Manejo de métodos de pago efectivo
                cashBtn.addEventListener('click', () => {
                    //aqui quitamos el label del credito al poner efectivo
                    quitacreito();
                    setActiveMethod('cash');
                    updateAmountSection();
                });
                // Manejo de métodos de pago credito
                creditBtn.addEventListener('click', () => {
                    //aqui agregamos label con el monto con el credito disponible
                    //mucredito();
                    //impcredito();
                    setActiveMethod('credit');
                    updateAmountSection();
                });

                function setActiveMethod(method) {
                    selectedMethod = method;
                    // Remover active de todos
                    cashBtn.classList.remove('active');
                    creditBtn.classList.remove('active');
                    if (method === 'cash') {
                        cashBtn.classList.add('active');
                    } else {
                        creditBtn.classList.add('active');
                    }
                }

                function updateAmountSection() {
                    if (!selectedMethod) {
                        amountSection.style.display = 'none';
                        return;
                    }

                    amountSection.style.display = 'block';
                    let html = '';

                    if (selectedMethod === 'cash') {
                        html = `
                        <div class="mb-2">
                            <label class="form-label"><i class="fas fa-money-bill-wave"></i> Cantidad a pagar:</label>
                            <input type="number" class="form-control" id="cashAmount" step="0.01" min="0" value="${totalVenta}" placeholder="0.00">
                        </div>
                        <div class="alert alert-info mb-0">Total de venta: $${totalVenta.toFixed(2)}</div>
                    `;
                    } else if (selectedMethod === 'credit') {
                        html = `
                        <div class="alert alert-warning mb-2">Total a crédito: $${totalVenta.toFixed(2)}</div>
                        <div class="mb-3"> <label class="form-label id="labelcredito"> </label> </div>
                        <div class="form-check advance-checkbox mb-2">
                            <input class="form-check-input" type="checkbox" id="advanceCheckbox">
                            <label class="form-check-label" for="advanceCheckbox">¿Adelantar pago?</label>
                        </div>
                        <div class="advance-input" id="advanceInputContainer" style="display: none;">
                            <label class="form-label"><i class="fas fa-hand-holding-usd"></i> Monto a adelantar:</label>
                            <input type="number" class="form-control" id="advanceAmount" step="0.01" min="0" max="${totalVenta.toFixed(2)}" value="0.00" placeholder="${totalVenta.toFixed(2)}">
                        </div>
                    `;
                    }

                    amountSection.innerHTML = html;

                    // Si es crédito, agregar funcionalidad al checkbox para activar el input para el adelanto
                    if (selectedMethod === 'credit') {
                        const advanceCheckbox = document.getElementById('advanceCheckbox');
                        const advanceContainer = document.getElementById('advanceInputContainer');
                        if (advanceCheckbox) {
                            advanceCheckbox.addEventListener('change', function(e) {
                                advanceContainer.style.display = this.checked ? 'block' : 'none';
                            });
                        }
                    }
                }

                function resetModal() {
                    // Limpiar inputs
                    document.getElementById('vendedor').value = '';
                    clienteInput.value = '';
                    // Quitar método activo
                    selectedMethod = null;
                    cashBtn.classList.remove('active');
                    creditBtn.classList.remove('active');
                    amountSection.style.display = 'none';
                    amountSection.innerHTML = '';
                }

                // Manejar envío del formulario
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const vendedor = document.getElementById('vendedor').value;
                    const cliente = clienteInput.value;

                    if (!vendedor || !cliente) {
                        alert('Por favor seleccione vendedor y cliente.');
                        return;
                    }
                    if (!selectedMethod) {
                        alert('Seleccione un método de pago.');
                        return;
                    }

                    let pagoInfo = {};
                    if (selectedMethod === 'cash') {
                        const cashAmount = document.getElementById('cashAmount')?.value || totalVenta;
                        pagoInfo = {
                            metodo: 'Efectivo',
                            cantidad: parseFloat(cashAmount)
                        };
                    } else {
                        const advanceCheck = document.getElementById('advanceCheckbox')?.checked || false;
                        const advanceAmount = advanceCheck ? (document.getElementById('advanceAmount')?.value || 0) : 0;
                        pagoInfo = {
                            metodo: 'Crédito',
                            total: totalVenta,
                            adelanto: advanceCheck ? parseFloat(advanceAmount) : 0,
                            saldo: totalVenta - (advanceCheck ? parseFloat(advanceAmount) : 0)
                        };
                    }

                    console.log('Venta procesada:', {
                        vendedor,
                        cliente,
                        ...pagoInfo
                    });

                    alert('Venta registrada (ver consola).');

                    // Cerrar modal con Bootstrap
                    const modalInstance = bootstrap.Modal.getInstance(ventaModal);
                    modalInstance.hide();
                });
            })();

            //------------------------------------------------------------------------

        </script>

        <script>
            document.querySelector("#pago_btn").addEventListener("click", function(event) {

                (async () => {
                    const {
                        value: formValues
                    } = await Swal.fire({
                        title: "Multiple inputs",
                        html: `
      <input id="swal-input1" class="swal2-input">
      <input id="swal-input2" class="swal2-input">
    `,
                        focusConfirm: false,
                        preConfirm: () => {
                            return [
                                document.getElementById("swal-input1").value,
                                document.getElementById("swal-input2").value
                            ];
                        }
                    });
                    if (formValues) {
                        Swal.fire(JSON.stringify(formValues));
                    }
                })()
            });

        </script>

</body>


</html>
<!-- codigo para prueba de cliente  -->


<!-- Mostrar resumen del cliente seleccionado (opcional) -->
<div class="alert alert-info" id="resumenCliente" style="display: none;">
    <strong>Cliente seleccionado:</strong><br>
    <span id="clienteNombre"></span><br>
    <span id="clienteCredito"></span>
</div>
