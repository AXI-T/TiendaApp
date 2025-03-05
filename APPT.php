<!-- pagina.html -->
<!DOCTYPE html>
<html lang="es">
<?php
include("iCNX.php");
$sqlB1 = "SELECT * FROM articulos_tb";
$stmt = $pdo->prepare($sqlB1);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página con Contenedores</title>
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/productos.css">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- Inclusión del archivo de navegación -->
    <iframe src="nav.html" style="border:none; width: 100%; height: 50px;"></iframe>

    <div class="container">
        <div class="left-content">
            <!-- Sección de búsqueda y filtro -->
            <div class="search-filter">
                <input type="text" id="search" placeholder="Buscar producto">
                <select id="filter">
                    <option value="">Todos los productos</option>
                    <option value="smartphone">Smartphones</option>
                    <option value="laptop">Laptops</option>
                    <option value="accessories">Accesorios</option>
                    <option value="smartwatch">Smartwatches</option>
                </select>
            </div>
            <!-- Sección de selección de Vendedor y Cliente -->
            <div class="selectors">
                <select id="seller-cashier">
                    <option value="">Seleccione &#9986; Vendedor/Cajero</option>
                    <option value="vendedor1">Vendedor 1</option>
                    <option value="vendedor2">Vendedor 2</option>
                    <option value="cajero1">Cajero 1</option>
                    <option value="cajero2">Cajero 2</option>
                </select>
                <select id="clients">
                    <option value="">Seleccione Cliente</option>
                    <option value="cliente1">Cliente 1</option>
                    <option value="cliente2">Cliente 2</option>
                    <option value="cliente3">Cliente 3</option>
                    <option value="cliente4">Cliente 4</option>
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
                        <p class="product-name"><?= htmlspecialchars($producto['nombre']); ?></p>
                        <p class="product-price">$ <?= htmlspecialchars($producto['precio']); ?></p>
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
                <select id="clientes">
                    <option value="">+ &#128179; credito defecto</option>
                    <option value="vendedor1">Vendedor 1</option>
                    <option value="vendedor2">Vendedor 2</option>
                    <option value="cajero1">Cajero 1</option>
                    <option value="cajero2">Cajero 2</option>
                </select>
                <select id="tipop">
                    <option value="">&#129689; efectivo </option>
                    <option value="cliente1">Cliente 1</option>
                    <option value="cliente2">Cliente 2</option>
                    <option value="cliente3">Cliente 3</option>
                    <option value="cliente4">Cliente 4</option>
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
                    <button class="checkout-button">Finalizar Venda</button>
                </div>
                <div class="add-item-container">
                    <input type="text" class="add-item-input" placeholder="Escriba aquí...">
                    <button class="add-item-button">Agregar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const carritoTabla = document.querySelector("#carrito-body"); // Cuerpo de la tabla del carrito
            const botonesAgregar = document.querySelectorAll(".add-button"); // Botones de agregar producto
            const totalElement = document.querySelector("#total-precio"); // Elemento donde se muestra el total

            botonesAgregar.forEach((boton) => {
                boton.addEventListener("click", function() {
                    const card = boton.closest(".product-card"); // Encuentra la tarjeta del producto
                    const nombre = card.querySelector(".product-name").textContent.trim();
                    const precio = parseFloat(card.querySelector(".product-price").textContent.replace("$", "").trim());
                    //const imagenSrc = card.querySelector("img").src; // Obtener la imagen

                    agregarAlCarrito(nombre, precio);
                });
            });

            function agregarAlCarrito(nombre, precio) {
                const filas = carritoTabla.getElementsByTagName("tr");

                for (let fila of filas) {
                    let nombreProducto = fila.querySelector(".nombre-producto").textContent.trim();

                    if (nombreProducto === nombre) {
                        // Si el producto ya está en el carrito, aumentar la cantidad
                        let cantidadElemento = fila.querySelector(".cantidad-producto");
                        let precioElemento = fila.querySelector(".precio-producto");

                        let cantidad = parseInt(cantidadElemento.textContent) + 1;
                        cantidadElemento.textContent = cantidad;
                        precioElemento.textContent = "$ " + (cantidad * precio).toFixed(2);

                        actualizarTotal();
                        return;
                    }
                }

                // Si el producto NO está en el carrito, agregar una nueva fila
                const nuevaFila = document.createElement("tr");
                nuevaFila.innerHTML = `
                    <td class="nombre-producto">${nombre}</td>
                    <td class="cantidad-producto">1</td>
                    <td class="precio-producto">$ ${precio.toFixed(2)}</td>
                    <td><button class="eliminar-producto delete-button"><i class="bi bi-trash"></i></button></td>
                `;
                carritoTabla.appendChild(nuevaFila);
                // Agregar evento al botón de eliminar
                nuevaFila.querySelector(".eliminar-producto").addEventListener("click", function() {
                    nuevaFila.remove();
                    actualizarTotal();
                });

                actualizarTotal();
            }

            function actualizarTotal() {
                let total = 0;
                const filas = carritoTabla.getElementsByTagName("tr");

                for (let fila of filas) {
                    let precioTexto = fila.querySelector(".precio-producto").textContent.replace("$", "").trim();
                    total += parseFloat(precioTexto);
                }

                totalElement.textContent = "Total: $ " + total.toFixed(2);
            }
        });

    </script>
</body>

</html>
