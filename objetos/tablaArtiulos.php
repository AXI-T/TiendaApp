<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tiendacss.css">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <!-- jQuery FIRST -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
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
                            <a href="iArticulos.php" class="login-button-back"><i class="bi bi-reply-all-fill"> Agregar Articulos</i></a><br>
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
                        <td class="actions" style="display: grid; place-items: center;">
                            <button class="delete-button" onclick="eliminarProducto('<?= htmlspecialchars($producto['id_articulo']); ?>');"><i class="bi bi-trash3"></i></button>
                            <button class="edit-button" onclick="editarProducto('<?= htmlspecialchars($producto['id_articulo']); ?>');"><i class="bi bi-pencil"></i></button>
                        </td>
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
                        title: 'Listado de Productos',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        title: 'Listado de Productos',
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

</body>

</html>
