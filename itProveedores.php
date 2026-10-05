<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/carrito.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.min.css">
    <link href="bootstrap4j/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap4j/js/jquery-1.12.4.min.js"></script>
    <script src="css/bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>
    <!-- *******************************************************************-->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap4j/js/jquery.dataTables.min.js"></script>
    <script src="bootstrap4j/js/dataTables.bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="js/sweetA.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">
    <!-- buttons -->
    <script src="bootstrap4j/buttons/dataTables.buttons.min.js"></script>
    <script src="bootstrap4j/buttons/jszip.min.js"></script>
    <script src="bootstrap4j/buttons/pdfmake.min.js"></script>
    <script src="bootstrap4j/buttons/vfs_fonts.js"></script>
    <script src="bootstrap4j/buttons/buttons.html5.min.js"></script>
    <title>Tabla de Clientes</title>
</head>
<style>
    /* Estilos adicionales para mejor responsividad */
    .dataTables_wrapper {
        overflow-x: auto;
    }

    /* Ajuste de imágenes en móviles */
    @media (max-width: 768px) {
        .table td img {
            max-width: 40px !important;
            height: auto;
        }

        /* Reducir padding en celdas */
        .table td,
        .table th {
            padding: 8px 5px;
        }

        /* Ocultar columnas menos importantes en móviles */
        .hide-on-mobile {
            display: none;
        }
    }

    /* Para pantallas muy pequeñas */
    @media (max-width: 480px) {
        .table td img {
            max-width: 30px !important;
        }

        .btn {
            padding: 3px 6px;
            font-size: 12px;
        }
    }

</style>

<body>
    <?php
    include("iCNX.php");
    $sqlB1 = "SELECT id_proveedor, nombre, contacto, telefono, ruta_img FROM proveedor_tb";
    $stmt = $pdo->prepare($sqlB1);
    $stmt->execute();
    /*
    $sqlB5 = "SELECT * FROM categorias_tb";
    $stmt5 = $pdo->prepare($sqlB5);
    $stmt5->execute();
    $Categorias = $stmt5->fetchAll(PDO::FETCH_ASSOC);
    */
    $lista_proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    ?>
    <?php include_once("navegacion.php"); ?>
    <div class="container-fluid mt-3" style="max-width:90%;">
        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <p style="font-size:16px; font-weight: bold;"><a href="iclientes.php" class="login-button-back"> <i class="bi bi-person-hearts"></i> Agregar Cliente </a> </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="table-responsive">
            <table class="table table-hover" id="tablaDinamica" style="overflow-x:scroll;">
                <thead class="cart-table thead table-light" style="color: #8a2be2;">
                    <tr>
                        <th>Nombre del Proveedor</th>
                        <th>Contacto</th>
                        <th>Telefono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla_articulos">
                    <?php if (!empty($lista_proveedores)): ?>
                    <?php foreach ($lista_proveedores as $proveedor): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($proveedor['ruta_img']); ?>" style="max-width: 50px; height: auto;" class="img-thumbnail"></td>
                        <td><?= htmlspecialchars($proveedor['nombre']); ?></td>
                        <td><?= htmlspecialchars($proveedor['contacto']); ?></td>
                        <td><?= htmlspecialchars($proveedor['telefono']); ?></td>
                        <td><?= htmlspecialchars($proveedor['telefono']); ?></td>
                        <td class="actions" style="display: grid; place-items: center;"><button class="delete-button" onclick="eliminarproveedor('<?= htmlspecialchars($cliente['id_proveedor']); ?>');"><i class="bi bi-trash3"></i></button><button class="edit-button" onclick="editarproveedor('<?= htmlspecialchars($cliente['id_proveedor']); ?>');"><i class="bi bi-pencil"></i></button></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8">No hay Clientes para Mostrar</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- ****************************-->
    <button onclick="abreSweet()">probamos1</button>
    <script>
        $(document).ready(function() {
            $('#tablaDinamica').DataTable({
                language: {
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "info": "Página _PAGE_ de _PAGES_ (_TOTAL_ registros)" + " ",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(Filtrado de _MAX_ registros)",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "No se encontraron registros",
                    "paginate": {
                        "first": "",
                        "last": "",
                        "next": ">",
                        "previous": "<"
                    },
                },
                dom: ' Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                        title: 'Lista de Proveedores',
                        className: 'btn btn-success',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                        title: 'Lista de Proveedores',
                        className: 'btn btn-danger',
                        exportOptions: {
                            columns: [0, 1, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ],
                responsive: true,
                pageLength: 5
            });
        });

        function eliminarproveedor(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este Proveedor?")) {
                $.post("eliminarCliente.php", {
                    id: id
                }, function(response) {
                    const data = JSON.parse(response);
                    if (data.success) {
                        alert("Proveedor eliminado correctamente.");
                        location.reload();
                    } else {
                        alert("Error al eliminar Cliente.");
                    }
                });
            }
        }

        function editarproveedor(id) {
            window.location.href = "iclienteMod.php?id_reg=" + id;
        }

    </script>
    <script>
        function abreSweet() {
            Swal.fire({
                with: "100%",
                html: '<iframe src="iclienteMod.php" style="border:none; width: 100%;"></iframe>'

            });

        }

    </script>
    <!--  ******************************* -->
</body>

</html>
