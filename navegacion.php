<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/font-Awesome/Font-Awesome-7.x/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-4.0.0/dist/css/bootstrap.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width:100%">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <!-- cargar o crear tabla para datos de tienda y cargarlos de forma dinamica -->
                <i class="fas fa-store"></i> Mi Tienda
            </a>
            <div class="navbar-nav ms-auto">
                <!-- aqui podemos poner alguna sesion
                <span class="navbar-text me-3"><i class="fas fa-user"></i> Administrador </span>
                -->
                <a class="navbar-text me-2 btn btn btn-sm" href="iclientes.php"> <i class="fas fa-users"></i> Clientes </a>
                <a class="navbar-text me-2 btn btn btn-sm" href="ipersonal.php"> <i class="fas fa-id-card-alt"></i> Personal </a>
                <a class="navbar-text me-2 btn btn btn-sm" href="iArticulos.php"> <i class="fas fa-boxes"></i> Productos </a>
                <a class="navbar-text me-2 btn btn btn-sm" href="iProveedor.php"> <i class="fas fa-truck"></i> Proveedores </a>
                <a class="navbar-text me-2 btn btn btn-sm" href="iventas.php"> <i class="fas fa-shopping-cart"></i> Ventas </a>

            </div>
        </div>
    </nav>
</body>

</html>
