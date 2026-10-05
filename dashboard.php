<!DOCTYPE html>
<html lang="es">
<?php
    include ('ICNX.php');
    try{
        $SQLQ1 =  "SELECT * FROM venta_tb";
        $stmt = $pdo->prepare($SQLQ1);
        $stmt->execute();
        $inf_ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //----------------------------------------->>
        $SQLTopArticulos = "SELECT dv.id_articulo, SUM(dv.cantidad) AS suma_vendidos, a.nombre FROM detalle_venta dv INNER JOIN articulos_tb a ON dv.id_articulo = a.id_articulo GROUP BY a.id_articulo, a.nombre ORDER BY suma_vendidos DESC LIMIT 5";
        $Articulos_top = $pdo->query($SQLTopArticulos)->fetchAll(PDO::FETCH_ASSOC);
        //----------------------------------------->>
        $anio_actual = date('Y');
        $mes_actual = date('n');
        $mes_anterior = $mes_actual -1;
        $anio_anterior = $anio_actual -1;
        
        
        $indicadorD = "bg-success";
        //----------------------------------------->>
        $nombres_meses = [1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'];
        $SQLQ3 = "SELECT MONTH(fecha_v) as mes, COUNT(*) as ventas_mes, COALESCE(SUM(totalventa), 0) as monto_total_mes, COALESCE(AVG(totalventa), 0) as promedio_mes FROM venta_tb WHERE YEAR(fecha_v) = YEAR(CURDATE()) GROUP BY MONTH(fecha_v) ORDER BY MONTH(fecha_v)";
        $stmtG = $pdo->prepare($SQLQ3);
        $stmtG->execute();
        //$ventas_axm = $stmtG->fetchAll(PDO::FETCH_ASSOC);
        $datos_mes = [];
        $rows = $stmtG->fetchAll(PDO::FETCH_ASSOC);
        $datos_mes = [];
        foreach ($rows as $row) {
            $datos_mes[$row['mes']] = ['ventas_mes' => $row['ventas_mes'], 'monto_total_mes' => $row['monto_total_mes'], 'promedio_mes' => $row['promedio_mes']];
            /*$mes = $row['mes'];
            if($mes = $mes_anterior){
                print_r('el mes anterior es '.$mes);
                $SQLQ_ma = "SELECT COUNT(*) AS ventas_mes, SUM(totalventa) AS monto_total_mes, AVG(totalventa) AS Promedio_mes FROM venta_tb WHERE YEAR(fecha_v) = YEAR(CURDATE()) AND MONTH(fecha_v) = MONTH(CURDATE())";
                $stmt2 = $pdo->prepare($SQLQ_v_m);
                $ventas_del_mes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
            }*/
        }
        $labels = [];
        $datos_ventas = [];
        for ($mes = 1; $mes <= $mes_actual; $mes++) {
            $labels[] = $nombres_meses[$mes];
            if (isset($datos_mes[$mes])) {
                $datos_ventas[] = floatval($datos_mes[$mes]['monto_total_mes']);
            } else {
                $datos_ventas[] = 0;
            }
        }
        $labels_json = json_encode($labels);
        $datos_json = json_encode($datos_ventas);
        //----------------------------------------->>
        $SQLQ_v_d = "SELECT COUNT(*) AS ventas_hoy, SUM(totalventa) AS monto_total_hoy, COALESCE(AVG(totalventa), 0) AS Promedio_hoy FROM venta_tb WHERE fecha_v = CURDATE()";
        $stmt1 = $pdo->prepare($SQLQ_v_d);
        $ventas_del_dia = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($ventas_del_dia)){
            foreach($ventas_del_dia as $ventas_dia){
                $monto_dia = $ventas_dia['monto_total_dia'];
            }
        } else{
            $monto_dia = 0;
        }
        //----------------------------------------->>
        $SQLQ_v_m = "SELECT COUNT(*) AS ventas_mes, SUM(totalventa) AS monto_total_mes, AVG(totalventa) AS Promedio_mes FROM venta_tb WHERE YEAR(fecha_v) = YEAR(CURDATE()) AND MONTH(fecha_v) = MONTH(CURDATE())";
        $stmt2 = $pdo->prepare($SQLQ_v_m);
        $ventas_del_mes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($ventas_del_mes)){
            foreach($ventas_del_mes as $ventas_mes){
                $monto_mes = $ventas_mes['monto_total_mes'];
            }
        } else{
            $monto_mes = 0;
        }
        //----------------------------------------->>
        $SQLQ_ma = "SELECT COUNT(*) AS ventas_mes, SUM(totalventa) AS monto_total_mes, AVG(totalventa) AS Promedio_mes FROM venta_tb WHERE YEAR(fecha_v) = YEAR(CURDATE()) AND MONTH(fecha_v) = $mes_anterior";
        $stmt_ma = $pdo->prepare($SQLQ_ma);
        $mes_a_v = $stmt_ma->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($mes_a_v)){
            foreach($mes_a_v as $mes_a_v){
                $monto_mes_a = $mes_a_v['monto_total_mes'];
            }
        }else {
            $monto_mes_a =0;
        }
        
        //----------------------------------------->>
        $SQLQ_v_a = "SELECT COUNT(*) AS ventas_anio, SUM(totalventa) AS monto_total_anio, AVG(totalventa) AS Promedio_anio FROM venta_tb WHERE YEAR(fecha_v) = YEAR(CURDATE())";
        $stmt3 = $pdo->prepare($SQLQ_v_a);
        $stmt3->execute();
        $ventas_del_anio = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($ventas_del_anio)){
            foreach($ventas_del_anio as $ventas_anio){
                $monto_anio = $ventas_anio['monto_total_anio'];
                //$promedio_anio = $ventas_anio['Promedio_anio']; esto es la media conforme a las ventas
            }
        } else{
            $monto_anio = 0;
            //$promedio_anio = 0;
        }
        //----------------------------------------->>
        // monto Anio Anterior
        $SQLQ_a_a = "SELECT COUNT(*) AS ventas_anio, SUM(totalventa) AS monto_total_anio, AVG(totalventa) AS Promedio_anio FROM venta_tb WHERE YEAR(fecha_v) = $anio_anterior";
        $stmt_aa = $pdo->prepare($SQLQ_a_a);
        $stmt_aa->execute();
        $monto_aa = $stmt_aa->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($monto_aa)){
            foreach($monto_aa as $monto_aa){
                $monto_anio_a = $monto_aa['monto_total_anio'];
                //$promedio_anio = $ventas_anio['Promedio_anio']; esto es la media conforme a las ventas
            }
        } else{
            $monto_anio_a = 0;
            //$promedio_anio = 0;
        }
        //----------------------------------------->>
        //porcentaje ante anio anterior//
        if($monto_anio_a>0){
            if($monto_anio_a > $monto_anio){
                $signoA = "-";
                $c_porcentajeA = ($monto_anio * 100) / $monto_anio_a;
                $indicadorA = "bg-danger";
                //$porcentaje_xmes = number_format($comparacion_ma, 2);
            }elseif($monto_anio_a == $monto_anio){
                $signoA = "+";
                $c_porcentajeA = ($monto_anio * 100) / $monto_anio_a;
                $indicadorA = "bg-warning";
                //$porcentaje_xmes = number_format($comparacion_ma, 2);
            }else{
                $signoA = "+";
                $c_porcentajeA = ($monto_anio * 100) / $monto_anio_a;
                $indicadorA = "bg-success";
            }
        }else{
            $signoA = "+";
            $c_porcentajeA = 100;
            $indicadorA = "bg-success";
        }
        
        
        //----------------------------------------->>
        // comparacion del mes //
        if($monto_mes_a>0){
            if($monto_mes_a > $monto_mes){
                $signoM = "-";
                $c_porcentajeM = ($monto_mes * 100) / $monto_mes_a;
                $indicadorM = "bg-danger";
                //$porcentaje_xmes = number_format($comparacion_ma, 2);
            }elseif($monto_mes_a == $monto_mes){
                $signoM = "+";
                $c_porcentajeM = ($monto_mes * 100) / $monto_mes_a;
                $indicadorM = "bg-warning";
            }else{
                $signoM = "+";
                $c_porcentajeM = ($monto_mes * 100) / $monto_mes_a;
                $indicadorM = "bg-success";
            }
        }else{
            $signoM = "+";
            $c_porcentajeM = 0;
            $indicadorM = "bg-success";
        }
        
        $porcentaje_xmes = number_format($c_porcentajeM, 2);
        $porcentaje_anio = number_format($c_porcentajeA, 2);
        //----------------------------------------->>
        // porcentaje del dia //
        if($monto_mes>0){
            $c_porcentajeD = ($monto_dia * 100) / $monto_mes;
            
        }else{
            $c_porcentajeD = 0;
        }
        
        $porcentaje_xdia = number_format($c_porcentajeD, 2);
        //----------------------------------------->>
     
        /*
        $c_porcentajeA = ($monto_anio * 100)/ $monto_anio;
        $c_porcentajeM = ($monto_mes * 100) / $monto_anio;
        $c_porcentajeD = ($monto_dia * 100) / $monto_anio;
        $porcentaje_anio = number_format($c_porcentajeA, 2);
        $porcentaje_xmes = number_format($c_porcentajeM, 2);
        $porcentaje_xdia = number_format($c_porcentajeD, 2);
        */
        
    }catch(PDOExeption $e){
        echo 'Error 404 sin conexion '. $e->getMessage();
    }
    ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Tienda</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="css/font-Awesome/Font-Awesome-7.x/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            border: none;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .stat-card-2 {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .stat-card-3 {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .progress {
            height: 8px;
            margin-top: 10px;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .list-group-item {
            border: none;
            padding: 10px 15px;
        }

    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <!-- cargar o crear tabla para datos de tienda y cargarlos de forma dinamica -->
                <i class="fas fa-store"></i> Mi Tienda
            </a>
            <div class="navbar-nav ms-auto">
                <!--
                <span class="navbar-text me-3"><i class="fas fa-user"></i> Administrador </span>
                <a class="navbar-text me-2 btn btn btn-sm" href="logout.php"> <i class="fas fa-sign-out-alt"></i> Salir </a>
                -->
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="iventas.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart me-2"></i>Ventas
                    </a>
                    <a href="iProveedor.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-truck me-2"></i>Proveedores
                    </a>
                    <a href="iArticulos.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-boxes me-2"></i>Productos
                    </a><a href="ipersonal.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-id-card-alt me-2"></i>Personal
                    </a>
                    <a href="iclientes.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Clientes
                    </a>
                    <a href="ireportes.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Reportes
                    </a>
                </div>
            </div>

            <!-- Contenido Principal que mostrar en el DASH  -->
            <div class="col-md-9">
                <!-- Estadísticas Principales -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card dashboard-card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">VENTAS HOY</h6>
                                        <h2 class="card-text">$ <?= htmlspecialchars($monto_dia); ?></h2>
                                        <span class="badge bg-success"><?= htmlspecialchars($porcentaje_xdia); ?>% este mes</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x"></i>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-light" style="width: <?= htmlspecialchars($c_porcentajeD); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card stat-card-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">VENTAS DEL MES</h6>
                                        <h2 class="card-text">$ <?= htmlspecialchars($monto_mes); ?></h2>
                                        <span class="badge <?= htmlspecialchars($indicadorM); ?>"><?= htmlspecialchars($signoM.$porcentaje_xmes); ?>% vs mes anterior</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-light" style="width: <?= htmlspecialchars($c_porcentajeM); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card stat-card-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">VENTAS DEL AÑO</h6>
                                        <h2 class="card-text">$<?= htmlspecialchars($monto_anio); ?></h2>
                                        <span class="badge <?= htmlspecialchars($indicadorA); ?>"><?= htmlspecialchars($signoA.$porcentaje_anio); ?>% Ante el año anterior</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-box fa-2x"></i>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-light" style="width: <?= htmlspecialchars($c_porcentajeA); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gráficas y Listas -->
                <div class="row">
                    <!-- Gráfica Mensual -->
                    <div class="col-md-8">
                        <div class="chart-container">
                            <h5>Ventas Mensuales</h5>
                            <canvas id="monthlyChart" height="200"></canvas>
                        </div>
                    </div>

                    <!-- Productos Más Vendidos -->
                    <div class="col-md-4">
                        <div class="card dashboard-card">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-trophy me-2"></i> Productos Más Vendidos</h6>
                            </div>
                            <?php if(!empty($Articulos_top)): ?>
                            <?php foreach($Articulos_top as $productos): ?>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <?= htmlspecialchars($productos['nombre']) ?>
                                    <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($productos['suma_vendidos']) ?></span>
                                </div>
                                <?php endforeach; ?>
                                <?php endif ?>
                                <!--<div class="list-group-item d-flex justify-content-between align-items-center">
                                    Ppum
                                    <span class="badge bg-primary rounded-pill">987</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Doug
                                    <span class="badge bg-primary rounded-pill">756</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Arrat
                                    <span class="badge bg-primary rounded-pill">654</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Corner
                                    <span class="badge bg-primary rounded-pill">543</span>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comparación y Métricas Adicionales -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card dashboard-card">
                            <div class="card-body text-center">
                                <h5>Comparación Anual</h5>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <h3 class="text-success">65%</h3>
                                        <p class="text-muted">2019</p>
                                    </div>
                                    <div class="col-6">
                                        <h3 class="text-primary">75%</h3>
                                        <p class="text-muted">2020</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h6>Top Vendedores</h6>
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Vendedor">
                                    <div>
                                        <strong>Natasha Ros</strong>
                                        <div class="progress mt-1">
                                            <div class="progress-bar bg-success" style="width: 85%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <img src="https://via.placeholder.com/40" class="rounded-circle me-3" alt="Vendedor">
                                    <div>
                                        <strong>Lorem Ipsum</strong>
                                        <div class="progress mt-1">
                                            <div class="progress-bar bg-info" style="width: 70%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h6>Enlaces Rápidos</h6>
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="fas fa-blog me-2"></i>Blog de la Tienda
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="fas fa-cog me-2"></i>Configuración
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <i class="fas fa-question-circle me-2"></i>Ayuda
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="css/bootstrap-4.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/chart.js"></script>

    <script>
        const labels = <?php echo $labels_json; ?>;
        const datosVentas = <?php echo $datos_json; ?>;
        const anioActual = <?php echo $anio_actual; ?>;
        // Gráfica de ventas mensuales encotrar la forma de cargarla con la BD
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels, //cargar meses de enero a el dia precente 
                datasets: [{
                    label: 'Ventas ' + anioActual, //anio en presente
                    data: datosVentas, // cantidades de venta en los meses
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

    </script>
</body>

</html>
<script>
    /*
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector(".stat-card").addEventListener("click", function(event) {
            alert("Hoola mundo");
        });
    });
    */

</script>
