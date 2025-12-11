

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
    <div class="login-container">
        <div class="login-box" style="width: 80%;">
        
        <div class="">
            <div class="search-filter">
            <label for="busqueda">Buscar producto:</label>
            <input type="text" id="busqueda" placeholder="Escribe nombre o código...">
            <button>Buscar</button>
            </div>

        <p class="signup-text"><a href="iArticulos.php">Regresar</a></p><br>
        </div>
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
                <tbody id="tabla-productos">
                    <!-- Aquí se cargarán los productos con AJAX -->
                </tbody>
            </table>
            
        </div>
    </div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // Detectar cuando el usuario escribe en el campo de búsqueda
    document.getElementById('busqueda').addEventListener('keyup', function () {
        const query = this.value; // Obtener el texto ingresado
        const xhr = new XMLHttpRequest();
        // Configurar la solicitud AJAX
        xhr.open('POST', 'buscar_articulos.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Actualizar la tabla con los resultados
                document.getElementById('tabla-productos').innerHTML = xhr.responseText;
            }
        };
        // Enviar la solicitud con el texto de búsqueda
        xhr.send('query=' + encodeURIComponent(query));
    });
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
</script>