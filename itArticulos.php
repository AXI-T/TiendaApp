

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <title>Tabla de Artículos</title>
</head>
<body>
<?php
include("iCNX.php");
$sqlB1 = "SELECT * FROM articulos_tb";
$stmt = $pdo->prepare($sqlB1);
$stmt->execute();
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <div class="login-container">
        <div class="login-box" style="width: 80%;">
        <p class="signup-text"><a href="iArticulos.php">Regresar</a></p><br>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Imagen del Producto</th>
                        <th>Nombre del Producto</th>
                        <th>Código del Producto</th>
                        <th>Detalle del Producto</th>
                        <th>Precio del Producto</th>
                        <th>Existencias del Producto</th>
                        <th>Proveedor</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (!empty($productos)): ?>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><img src="" alt="Imagen Producto" style="max-width: 50px;"></td>
                                <td><?= htmlspecialchars($producto['nombre']); ?></td>
                                <td><?= htmlspecialchars($producto['codigo']); ?></td>
                                <td><?= htmlspecialchars($producto['descripcion']); ?></td>
                                <td>$<?= htmlspecialchars($producto['precio']); ?></td>
                                <td><?= htmlspecialchars($producto['stock']); ?></td>
                                <td><?= htmlspecialchars($producto['id_proveedor']); ?></td>
                                <td class="actions"><button class="delete-button" onclick="eliminarProducto('<?= htmlspecialchars($producto['id_articulo']); ?>');"><i class="bi bi-trash3"></i></button><button class="edit-button" onclick="editarProducto('<?= htmlspecialchars($producto['id_articulo']); ?>');"><i class="bi bi-pencil"></i></button></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8">No hay productos disponibles</td></tr>
                    <?php endif; ?>
                    <!--<tr>
                        <td>Headphones</td>
                        <td>Headphones</td>
                        <td>Headphones</td>
                        <td>2</td>
                        <td>2</td>
                        <td>R$ 399.98</td>id_articulo






                        <td>R$ 399.98</td>
                        <td class="actions"><button class="delete-button"><i class="bi bi-trash3"></i></button><button class="edit-button"><i class="bi bi-pencil"></i></button></td>
                    </tr>
                    <tr>
                        <td>Smartwatch</td>
                        <td>1</td>
                        <td>R$ 299.99</td>
                        <td><button class="delete-button"> - </button></td>
                    </tr>
                     Más productos pueden agregarse aquí -->
        
                </tbody>
            </table>
            
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Función para eliminar producto
    function eliminarProducto(id) {
        if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
            $.post("eliminarArt.php", { id: id }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert("Producto eliminado correctamente.");
                    location.reload(); // Recargamos la página
                } else {
                    alert("Error al eliminar el producto.");
                }
            });
        }
    }
    function editarProducto(id){
        alert("el id del articulo seleccionado es : "+ id);
        window.location.href = "iArticulosMod.php?id_reg=" + id;
    }

    // Función para editar producto
   /* function editarProducto(id) {
        const nombre = prompt("Nuevo nombre del producto:");
        const codigo = prompt("Nuevo código del producto:");
        const detalle = prompt("Nuevo detalle del producto:");
        const precio = prompt("Nuevo precio del producto:");
        const existencias = prompt("Nuevas existencias:");
        const proveedor = prompt("Nuevo proveedor:");

        if (nombre && codigo && detalle && precio && existencias && proveedor) {
            $.post("editar_producto.php", {
                id: id,
                nombre: nombre,
                codigo: codigo,
                detalle: detalle,
                precio: precio,
                existencias: existencias,
                proveedor: proveedor
            }, function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    alert("Producto editado correctamente.");
                    location.reload();
                } else {
                    alert("Error al editar el producto.");
                }
            });
        } else {
            alert("Todos los campos son obligatorios.");
        }
    }*/
</script>