<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Categorias</title>
</head>
<link rel="stylesheet" href="css/login.css">
<link rel="stylesheet" href="css/articulos.css">
<link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
<!-- SweetAlert2 -->
<script src="js/sweetA.js"></script>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo"><span> <i class="bi bi-boxes"></i> </span> Categorias de Articulos</h2>
            <form id="form1" name="form1" class="login-form" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i>
                    <input type="text" id="name" name="name" placeholder="Nombre de la Categoria" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-info-circle-fill icon" style="color: #1199d0;"></i>
                    <textarea id="detalle" name="detalle" placeholder=" Detalle de la Categoria"></textarea>
                </div>
                <button type="submit" name="Xenviar" class="login-button">Enviar</button>
            </form>
        </div>
    </div>
</body>

</html>
<?php

if(isset($_POST['Xenviar'])){
    include("iCNX.php");
    $nombre_c = trim($_POST['name']);
    $detalle_c = trim($_POST['detalle']);
    if (empty($nombre_c) || empty($detalle_c)) {
        die("Todos los campos son obligatorios.");
    }else{
        try {
            $sqlB1 = "SELECT COUNT(*) AS total FROM categorias_tb WHERE nombre = :nombre_c";
            $stmt = $pdo->prepare($sqlB1);
            $stmt->bindParam(':nombre_c', $nombre_c, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row['total'] > 0) {
                echo "<script> alert('Ya cuentas con un registro existente'); </script>"; 
            } else {
                define('BxNombre', 'No existe');
                if(BxNombre == 'No existe'){
                    $sqlIst = "INSERT INTO categorias_tb (nombre, detalle) VALUES (:nombre_c, :detalle_c)";
                    $stmt = $pdo->prepare($sqlIst);  
                    $stmt->bindParam(':nombre_c', $nombre_c, PDO::PARAM_STR); 
                    $stmt->bindParam(':detalle_c', $detalle_c, PDO::PARAM_STR);
                    if ($stmt->execute()) {
                       echo "<script> Swal.fire({title: 'Registro Exitoso!',icon: 'success', draggable: true}); </script>";
                            
                    } else {
                        echo "<script> Swal.fire({title: 'Error al enviar el Registro!',icon: 'error', draggable: true}); </script>";
                    }
                }else{  
                    echo "<script> Swal.fire({title: 'Intentas enviar un registro Existente...',icon: 'warning', draggable: true}); </script>"; 
                }
            } 
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
        $pdo = null;
    }
}
?>
