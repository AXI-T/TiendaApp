<!DOCTYPE html>
<html lang="es">

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
                    <a href="dah.php" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="iventas.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart me-2"></i>Ventas
                    </a>
                    <a href="iArticulos.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-boxes me-2"></i>Productos
                    </a>
                    <a href="iclientes.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Clientes
                    </a>
                    <a href="ireportes.php" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-bar me-2"></i>Reportes
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Estadísticas Principales -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card dashboard-card stat-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">VENTAS TOTALES</h6>
                                        <h2 class="card-text">$17,421.97</h2>
                                        <span class="badge bg-success">+30% este mes</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x"></i>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-light" style="width: 30%"></div>
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
                                        <h2 class="card-text">$2,194.20</h2>
                                        <span class="badge bg-warning">+25% vs mes anterior</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-light" style="width: 25%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card dashboard-card stat-card-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">PRODUCTOS VENDIDOS</h6>
                                        <h2 class="card-text">1,929,838</h2>
                                        <span class="badge bg-danger">-2% este mes</span>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-box fa-2x"></i>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-light" style="width: 70%"></div>
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
                                <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>Productos Más Vendidos</h6>
                            </div>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    Joenn
                                    <span class="badge bg-primary rounded-pill">1,243</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
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
                                </div>
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
        // Gráfica de ventas mensuales encotrar la forma de cargarla con la BD
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago'], //cargar meses de enero a el dia precente 
                datasets: [{
                    label: 'Ventas 2024', //anio en presente
                    data: [1200, 1900, 1500, 2200, 1800, 2400, 2100, 2194], // cantidades de venta en los meses
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
