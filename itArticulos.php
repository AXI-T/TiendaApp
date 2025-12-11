<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.min.css">
    <link href="bootstrap4j/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap4j/js/jquery-1.12.4.min.js"></script>
    <script src="css/bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>
    <!-- *******************************************************************-->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap4j/js/jquery.dataTables.min.js"></script>
    <script src="bootstrap4j/js/dataTables.bootstrap.min.js"></script>


    <!-- buttons -->
    <script src="bootstrap4j/buttons/dataTables.buttons.min.js"></script>
    <script src="bootstrap4j/buttons/jszip.min.js"></script>
    <script src="bootstrap4j/buttons/pdfmake.min.js"></script>
    <script src="bootstrap4j/buttons/vfs_fonts.js"></script>
    <script src="bootstrap4j/buttons/buttons.html5.min.js"></script>
    <title>Tabla de Artículos</title>
</head>

<body>
    <?php
    include("iCNX.php");
    $sqlB1 = "SELECT a.*, p.nombre as nombreP FROM articulos_tb a INNER JOIN proveedor_tb p ON a.id_proveedor = p.id_proveedor";
    $stmt = $pdo->prepare($sqlB1);
    $stmt->execute();
    $sqlB5 = "SELECT * FROM categorias_tb";
    $stmt5 = $pdo->prepare($sqlB5);
    $stmt5->execute();
    $Categorias = $stmt5->fetchAll(PDO::FETCH_ASSOC);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    ?>
    <div class="login-container">
        <div class="login-box" style="width: 80%;">
            <table class="cart-table">
                <tbody>
                    <tr>
                        <td>
                            <a href="iArticulos.php" class="login-button-back" style="font-size: 22px;"> Agregar Articulos <i class="bi bi-reply-all-fill"></i></a><br>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="cart-table" id="tablaDinamica">
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
                <tbody id="tabla_articulos">
                    <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($producto['ruta_img']); ?>" alt="Imagen Producto" style="max-width: 50px;"></td>
                        <td><?= htmlspecialchars($producto['nombre']); ?></td>
                        <td><?= htmlspecialchars($producto['codigo']); ?></td>
                        <td><?= htmlspecialchars($producto['descripcion']); ?></td>
                        <td>$<?= htmlspecialchars($producto['precio']); ?></td>
                        <td><?= htmlspecialchars($producto['stock']); ?></td>
                        <td><?= htmlspecialchars($producto['nombreP']); ?></td>
                        <td class="actions" style="display: grid; place-items: center;"><button class="delete-button" onclick="eliminarProducto('<?= htmlspecialchars($producto['id_articulo']); ?>');"><i class="bi bi-trash3"></i></button><button class="edit-button" onclick="editarProducto('<?= htmlspecialchars($producto['id_articulo']); ?>');"><i class="bi bi-pencil"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8">No hay productos disponibles</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>
    <!-- ****************************-->

    <script>
        $(document).ready(function() {
            $('#tablaDinamica').DataTable({
                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info": "Mostrando página _PAGE_ de _PAGES_ de un total de _TOTAL_ registros",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    },
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        title: 'Listado de Articulos',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        title: 'Listado de Articulos',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    }
                ],
                responsive: true,
                pageLength: 10
            });
        });

        function eliminarProducto(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este producto?")) {
                $.post("eliminarArt.php", {
                    id: id
                }, function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        alert("Producto eliminado correctamente.");
                        location.reload();
                    } else {
                        alert("Error al eliminar el producto.");
                    }
                });
            }
        }

        function editarProducto(id) {
            window.location.href = "iArticulosMod.php?id_reg=" + id;
        }

    </script>
    <!--  ******************************* -->
</body>

</html>
