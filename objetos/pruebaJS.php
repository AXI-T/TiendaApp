<!-- pagina.html -->
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
    //echo "<script> alert('se realizaron todas las operaciones');  </script>";

}catch (PDOExeption $e){
    echo "Error en la conexión: " . $e->getMessage();

}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página con Contenedores</title>
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/productos.css">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <!-- Inclusión del archivo de navegación -->
    <iframe src="nav.html" style="border:none; width: 100%; height: 50px;"></iframe>

    <div class="container">
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
            <div class="selectors">
                <select id="personal" name="personal">
                    <option value="">Seleccione &#9986; Vendedor/Cajero</option>
                    <?php foreach ($users as $users): ?>
                    <option value="<?= $users['id_usuario'] ?>"><?= htmlspecialchars($users['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select id="clients" name="clients">
                    <option value="0"> Seleccione un Cliente </option>
                    <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>"><?= htmlspecialchars($cliente['nombre']) ?></option>
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
                        <button class="add-button">+</button>
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
                    <button class="add-item-button">Agregar</button>
                </div>
                <div class="cart-total">
                    <button id="xenviar" name="xenviar" class="checkout-button" onclick="capturarpago2()">Finalizar Venda</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Delegación de eventos para los botones de "Agregar"
            document.querySelector(".product-container").addEventListener("click", function(event) {
                if (event.target.classList.contains("add-button")) {
                    const card = event.target.closest(".product-card");
                    const code = parseInt(card.querySelector(".oculto").textContent.trim());
                    const nombre = card.querySelector(".product-name").textContent.trim();
                    const precio = parseFloat(card.querySelector(".product-price").textContent.replace("$", "").trim());
                    const unidad = card.querySelector(".product-unit").textContent.trim();
                    if (unidad === "pza") {
                        let cant = 1;
                        agregarAlCarrito(nombre, precio, cant);
                    } else {
                        let cant = prompt("ingresa la cantidad o medida del articulo a comprar!!");
                        //alert(typeof cant);
                        if (typeof cant === "number") {
                            let cantidad = parseFloat(cant);
                            let n_precio = precio * cant;
                            agregarAlCarrito(nombre, n_precio, cant);
                        } else {
                            alert("Valores no validos intenta nuevamente");
                        }

                    }

                    //agregarAlCarrito(nombre, precio);
                }
            });

            // Función para agregar productos al carrito
            function agregarAlCarrito(nombre, precio, cant) {
                const filas = document.querySelectorAll("#carrito-body tr");

                for (let fila of filas) {
                    let nombreProducto = fila.querySelector(".nombre-producto").textContent.trim();
                    if (nombreProducto === nombre) {
                        let cantidadElemento = fila.querySelector(".cantidad-producto");
                        let precioElemento = fila.querySelector(".precio-producto");

                        let cantidad = parseFloat(cantidadElemento.textContent) + cant;
                        cantidadElemento.textContent = cantidad;
                        precioElemento.textContent = "$ " + (cantidad * precio).toFixed(2);

                        actualizarTotal();
                        return;
                    }
                }

                const nuevaFila = document.createElement("tr");
                nuevaFila.innerHTML = `
            <td class="nombre-producto">${nombre}</td>
            <td class="cantidad-producto">${cant}</td>
            <td class="precio-producto">$ ${precio.toFixed(2)}</td>
            <td><button class="eliminar-producto delete-button"><i class="bi bi-trash"></i></button></td>
        `;
                document.querySelector("#carrito-body").appendChild(nuevaFila);

                nuevaFila.querySelector(".eliminar-producto").addEventListener("click", function() {
                    nuevaFila.remove();
                    actualizarTotal();
                });

                actualizarTotal();
            }

            // Función para actualizar el total
            function actualizarTotal() {
                let total = 0;
                const filas = document.querySelectorAll("#carrito-body tr");

                for (let fila of filas) {
                    let precioTexto = fila.querySelector(".precio-producto").textContent.replace("$", "").trim();
                    total += parseFloat(precioTexto);
                }

                document.querySelector("#total-precio").textContent = "Total: $ " + total.toFixed(2);
            }
        });

    </script>

    <script>
        $(document).ready(function() {
            $("#tipo_venta").change(function() {
                let tipoVentaID = $(this).val();

                if (tipoVentaID) {
                    $.ajax({
                        type: "POST",
                        url: "o_tipo_pago.php",
                        data: {
                            tipo_venta_id: tipoVentaID
                        },
                        dataType: "json",
                        success: function(response) {
                            let opciones = '<option value="">Seleccione una forma de pago</option>';
                            response.forEach(function(forma) {
                                opciones += `<option value="${forma.id_d_pago}">${forma.detalle}</option>`;
                            });
                            $("#forma_pago").html(opciones);
                        }
                    });
                } else {
                    $("#forma_pago").html('<option value="">Seleccione primero un tipo de venta</option>');
                }
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            $("#search").on("keyup keypress", function() {
                let searchText = $(this).val().toLowerCase(); // Convertimos el texto a minúsculas

                $.ajax({
                    type: "POST",
                    url: "filtro.php",
                    data: {
                        search: searchText
                    },
                    dataType: "html",
                    success: function(response) {
                        $(".product-container").html(response); // Reemplaza el contenido con los productos filtrados
                    },
                    error: function() {
                        alert("Error al buscar productos.");
                    }
                });
            });
        });

        $(document).ready(function() {
            $("#categoria").change(function() {
                let categoriaID = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "filtrar_categorias.php",
                    data: {
                        categoria_id: categoriaID
                    },
                    dataType: "html",
                    success: function(response) {
                        $(".product-container").html(response); // Reemplaza los productos con la respuesta

                    },
                    error: function() {
                        alert("Error al filtrar los productos.");
                    }
                });
            });
        });

        function capturarpago2() {
            const clienteID = document.getElementById("clients").value;
            const userID = document.getElementById("personal").value;
            const tipoVentaID = document.getElementById("tipo_venta").value;
            const formaPagoID = document.getElementById("forma_pago").value;
            const totalventa = parseFloat(document.getElementById("total-precio").textContent.replace("Total: $", "").trim());
            const pagocapturado = parseFloat(document.getElementById("xpagar").value);

            if (isNaN(totalventa) || isNaN(pagocapturado)) {
                alert("Error: El total de la venta o el monto a pagar no son números válidos.");
                return; // Detener la ejecución si hay errores
            }
            if (pagocapturado >= totalventa) {
                alert("se puede proceder");
                const productos = [];
                const filas = document.querySelectorAll("#carrito-body tr");
                filas.forEach(fila => {

                    const nombre = fila.querySelector(".nombre-producto").textContent.trim();
                    const cantidad = fila.querySelector(".cantidad-producto").textContent.trim();
                    const precio = fila.querySelector(".precio-producto").textContent.replace("$", "").trim();
                    productos.push({
                        nombre,
                        cantidad,
                        precio
                    });
                });
                $.ajax({
                    type: "POST",
                    url: "registrar_venta.php", // Archivo PHP que procesará la venta
                    data: {
                        cliente_id: clienteID,
                        user_id: userID,
                        tipo_venta_id: tipoVentaID,
                        forma_pago_id: formaPagoID,
                        monto_pagar: totalventa,
                        pagorecibido: pagocapturado,
                        productos: JSON.stringify(productos) // Convertir el array de productos a JSON
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            alert("Venta registrada con éxito");
                            // Limpiar el carrito y otros campos si es necesario
                            document.getElementById("carrito-body").innerHTML = "";
                            document.getElementById("xpagar").value = "";
                        } else {
                            alert("Error al registrar la venta: " + response.message);
                        }
                    },
                    error: function() {
                        alert("Error en la comunicación con el servidor");
                    }
                });

            } else {
                alert("aun no cubres la cantidad necesaria para efectuar la venta");

            }

            /* Comparar el total de la venta con el monto a pagar
            if (totalventa >= totalventa) {
                alert("procedemos");
            } else if (totalventa === totalventa) { // Usar === para comparación estricta
                alert("La cantidad es exacta.");
            } else {
                const feria = totalventa - totalventa;
                alert("Entregar cambio de $" + feria.toFixed(2)); // Redondear a 2 decimales
            }*/

        }

        /*------------esto es la funcion de capturar pago
            function capturarpago() {
            const clienteID = document.getElementById("clients").value;
            const userID = document.getElementById("personal").value;
            const tipoVentaID = document.getElementById("tipo_venta").value;
            const formaPagoID = document.getElementById("forma_pago").value;
            const totalventa = document.getElementById("total-precio").value;
            const totalventa = document.getElementById("xpagar").value;
            // Capturar los productos en el carrito
            const productos = [];
            const filas = document.querySelectorAll("#carrito-body tr");
            filas.forEach(fila => {
                const nombre = fila.querySelector(".nombre-producto").textContent.trim();
                const cantidad = fila.querySelector(".cantidad-producto").textContent.trim();
                const precio = fila.querySelector(".precio-producto").textContent.replace("$", "").trim();
                productos.push({
                    nombre,
                    cantidad,
                    precio
                });
            });

            // Enviar los datos al servidor usando AJAX
            $.ajax({
                type: "POST",
                url: "registrar_venta.php", // Archivo PHP que procesará la venta
                data: {
                    cliente_id: clienteID,
                    user_id: userID,
                    tipo_venta_id: tipoVentaID,
                    forma_pago_id: formaPagoID,
                    monto_pagar: totalventa,
                    productos: JSON.stringify(productos) // Convertir el array de productos a JSON
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        alert("Venta registrada con éxito");
                        // Limpiar el carrito y otros campos si es necesario
                        document.getElementById("carrito-body").innerHTML = "";
                        document.getElementById("xpagar").value = "";
                    } else {
                        alert("Error al registrar la venta: " + response.message);
                    }
                },
                error: function() {
                    alert("Error en la comunicación con el servidor");
                }
            });
        }*/

    </script>
</body>

</html>
