<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Proveedores</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>
<!--aqui pondre unos estilos locales para agrupar el input y el icono -->

<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo"><span> <i class="bi bi-bus-front-fill"></i> </span> Proveedores</h2>
            <form method="post" class="login-form">
                <!--<label for="email">Email</label>-->
                <div class="form-row">
                    <div class="input-group">
                        <label for="imagen-proveedor"><i class="bi bi-image" style="color: #1199d0;"></i> Imagen del Proveedor</label>
                        <input type="file" id="imagen-proveedor" name="imagen-proveedor" accept="image/*">
                    </div>
                </div>
                <div class="form-group">
                    <!--<i class="bi bi-universal-access icon" style="color: #43cced;"></i> Icono de usuario -->
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre del proveedor" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-fill-exclamation icon" style="color: #1199d0;"></i>
                    <input type="text" id="contacto" name="contacto" placeholder="Contacto" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-pin-map-fill icon" style="color: #1199d0;"></i>
                    <textarea id="direccion" name="direccion" placeholder=" Direccion"></textarea>
                </div>
                <div class="form-group">
                    <i class="bi bi-telephone-fill icon" style="color: #66fa1c;"></i>
                    <input type="text" id="telefono" name="telefono" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <!--<label for="password">Password</label>-->
                <button type="submit" name="Xenviar" id="Xenviar" class="login-button">Enviar</button>
                <p class="signup-text">Ver <a href="iProveedor.php">Articulos</a></p>
            </form>
        </div>
    </div>
</body>


</html>
<?php
include("iCNX.php");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if(isset($_POST['Xenviar'])){
    $nombre_pv = trim($_POST['nombre']);
    $contacto_pv = trim($_POST['contacto']);
    $direccion_pv = trim($_POST['direccion']);
    $telefono_pv = trim($_POST['telefono']);
    if (empty($nombre_pv) || empty($contacto_pv) || empty($direccion_pv) || empty($telefono_pv) ) {
        die("Todos los campos son obligatorios.");
    }else{
        try {
            
            // Preparar la consulta
            $sqlB1 = "SELECT COUNT(*) AS total FROM proveedor_tb WHERE nombre = :nombre_pv";
            $stmt = $pdo->prepare($sqlB1);
            $stmt->bindParam(':nombre_pv', $nombre_pv, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Validar la existencia del registro
            if ($row['total'] > 0) {
                echo "<script> alert('Proveedor existente puedes continuar!'); </script>";
            } else {
                $sqlIst = "INSERT INTO proveedor_tb (nombre, contacto, telefono, direccion) VALUES (:nombre_pv, :contacto_pv, :telefono_pv,:direccion_pv)";
                $stmt = $pdo->prepare($sqlIst);
                $stmt->bindParam(':nombre_pv', $nombre_pv, PDO::PARAM_STR);
                $stmt->bindParam(':contacto_pv', $contacto_pv, PDO::PARAM_STR);
                $stmt->bindParam(':telefono_pv', $telefono_pv, PDO::PARAM_INT);
                $stmt->bindParam(':direccion_pv', $direccion_pv, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    echo "<script> alert('Registrado correctamente'); </script>";
                    //location.reload();
                } else {
                    echo "<script> alert('Error al enviar el Registro'); </script>";
                }
            }
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
    
        // Cerrar conexión
        $pdo = null;
    }
}

?>
