<?php 
include("iCNX.php");
    $sqlB = "SELECT c.id_cliente, CONCAT(nombre, ' ', apellido_1, ' ', apellido_2) AS Nombre_Cliente, curp, alias, cc.c_autorizado AS Limite_credito, cc.c_disponible AS credito_disponible, est.descripcion AS estado_cliente, cc.fecha_corte AS fecha_corte FROM cliente_tb c INNER JOIN credito_cliente cc ON c.id_cliente = cc.id_cliente LEFT JOIN catalogo_estatus est ON c.id_estatus = est.id_estatus  WHERE c.id_estatus = 2";
    $stmt1 = $pdo->prepare($sqlB);
    $stmt1->execute();
    $clientes = $stmt1->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modal de Venta con Bootstrap</title>
    <script src="bootstrap4j/js/jquery-1.12.4.min.js"></script>
    <script src="css/bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="js/boostrap_para_moals.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/v_f_cliente.js"></script>
    <script src="js/vmodalf.js"></script>
    <script src="js/jquery.js"></script>
    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.min.css">
    <link href="bootstrap4j/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.css">
    <style>
        /* Ajuste para que el modal ocupe aproximadamente el 40% */
        .modal-dialog {
            max-width: 500px;
            /* 40% aprox en pantallas grandes */
        }

        /* Para inputs con datalist, mantener consistencia */
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .payment-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .payment-btn.active {
            background-color: #198754;
            color: white;
            border-color: #198754;
        }

        .payment-btn.credit.active {
            background-color: #fd7e14;
            border-color: #fd7e14;
            color: white;
        }

        .amount-section {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .advance-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

    </style>
</head>

<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <!-- Botón para abrir modal -->
    <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#ventaModal">
        Abrir Modal de Venta
    </button>

    <!-- Modal -->
    <div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ventaModalLabel">Seleccione Vendedor/Cajero</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ventaForm">
                        <!-- Vendedor searchable -->
                        <div class="mb-3">
                            <label for="vendedor" class="form-label">Vendedor / Cajero</label>
                            <input type="text" class="form-control" id="vendedor" list="vendedoresList" placeholder="Escriba para buscar...">
                            <datalist id="vendedoresList">
                                <option value="Juan Perez">
                                <option value="Maria Lopez">
                                <option value="Carlos Gomez">
                                <option value="Ana Martinez">
                                <option value="Pedro Ramirez">
                            </datalist>
                        </div>

                        <!-- Cliente searchable con botón rápido -->
                        <div class="mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="cliente" name="cliente" list="clientes" placeholder="Escriba o seleccione...">
                                <button class="btn btn-outline-secondary" type="button" id="publicoGeneralBtn">
                                    <i class="fas fa-users"></i> Público Gral.
                                </button>
                            </div>
                            <datalist id="clientes">

                            </datalist>
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
                            <!-- Se llena con JS -->
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Procesar Venta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
