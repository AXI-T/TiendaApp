<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Articulos</title>
</head>
<!--aqui pondre unos estilos locales para agrupar el input y el icono -->
<link rel="stylesheet" href="css/login.css">
<link rel="stylesheet" href="css/articulos.css">
<link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo"><span> <i class="bi bi-basket2-fill"></i> </span> Articulos</h2>
            <form id="form1" name="form1" class="login-form" method="post" enctype="multipart/form-data">
                <div class="contenedor" align="center">
                    <img id="foto" src="#" alt="Previsualización" style="display: none; max-width: 100px; height: 100px;" />
                </div>
                <!--<label for="email">Email</label>-->
                <div class="form-row">
                    <div class="input-group">
                        <label for="imagen"><i class="bi bi-image" style="color: #1199d0;"></i> Imagen del Producto</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                    </div>
                </div>
                <div class="form-group">
                    <!--<i class="bi bi-universal-access icon" style="color: #43cced;"></i> Icono de usuario -->
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="name" name="name" placeholder="Nombre del Producto" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-upc-scan icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="codea" name="codea" placeholder="Codigo del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-ticket-detailed icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <textarea id="detalle" name="detalle" placeholder=" Detalle del Producto"></textarea>
                </div>
                <div class="form-group">
                    <i class="bi bi-tags-fill icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="precio" name="precio" placeholder="Precio del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <!--<div class="form-row">
                    <div class="input-group">
                        <div class="password-container">
                            <input type="text" id="precio" placeholder="&#129689; &#36; Precio del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="text" id="cantidad" placeholder="&#127857; Existencias del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                    </div>
                </div>-->
                <div class="form-group">
                    <i class="bi bi-box-seam-fill icon" style="color: #d58938;"></i> <!-- Icono de usuario -->
                    <input type="text" id="stock" name="stock" placeholder="Existencias del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-bus-front-fill icon" style="color: #1199d0;"></i>
                    <!-- Icono de usuario 
                    <input type="text" id="phone" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>-->
                    <?php
                    include("iCNX.php");
                    try {
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        // Consulta para obtener los proveedores
                        $query = "SELECT id_proveedor, nombre FROM proveedor_tb";
                        $stmt = $pdo->query($query);
                    
                        // Guardar los resultados en un array
                        $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    } catch (PDOException $e) {
                        echo "Error en la conexión: " . $e->getMessage();
                        exit;
                    }
                     ?>
                    <select id="proveedor" name="proveedor">
                        <option value="0"> Seleccione un Proveedor </option>
                        <?php foreach ($proveedores as $proveedor): ?>
                        <option value="<?= $proveedor['id_proveedor'] ?>"><?= htmlspecialchars($proveedor['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!--<label for="password">Password</label>-->
                <button type="submit" name="Xenviar" class="login-button">Enviar</button>
            </form>
            <p class="signup-text"><a href="itArticulos.php"> Articulos <i class="bi bi-shop-window"></i></a></p>
        </div>
    </div>
</body>
<script>
    // Obtener el input file y el elemento de previsualización
    const inputFile = document.getElementById('imagen');
    const foto = document.getElementById('foto');

    // Agregar el evento change al input file
    inputFile.addEventListener('change', function(event) {
        const archivo = event.target.files[0]; // Obtener el archivo cargado

        if (archivo) {
            const lector = new FileReader(); // Crear un FileReader para leer el archivo

            lector.onload = function(e) {
                // Configurar la imagen de previsualización
                foto.src = e.target.result;
                foto.style.display = 'block'; // Mostrar la imagen
            };

            lector.readAsDataURL(archivo); // Leer el archivo como una URL de datos
        } else {
            // Si no hay archivo, ocultar la imagen
            vistaPrevia.style.display = 'none';
            vistaPrevia.src = '#';
        }
    });

</script>

</html>
<?php

if(isset($_POST['Xenviar'])){
    $nombre_A = trim($_POST['name']);
    $code_A = trim($_POST['codea']);
    $detalle_A = trim($_POST['detalle']);
    $precio_A = trim($_POST['precio']);
    $stock_A = trim($_POST['stock']);
    $provedor_A = trim($_POST['proveedor']);
    if (empty($nombre_A) || empty($code_A) || empty($detalle_A) || empty($precio_A) || empty($stock_A) || $provedor_A ==  0) {
        die("Todos los campos son obligatorios.");
    }else{
        try {
            // Preparar la consulta
            $sqlB1 = "SELECT COUNT(*) AS total FROM articulos_tb WHERE nombre = :nombre_A";
            $stmt = $pdo->prepare($sqlB1);
        
            // Asignar el parámetro
            $stmt->bindParam(':nombre_A', $nombre_A, PDO::PARAM_STR);
        
            // Ejecutar la consulta
            $stmt->execute();
        
            // Obtener el resultado
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            // Validar la existencia del registro
            if ($row['total'] > 0) {
                echo "El registro por nombre existe.";
            } else {
                define('BxNombre', 'No existe');
                try {
                    // Preparar la consulta
                    $sqlB2 = "SELECT COUNT(*) AS total FROM articulos_tb WHERE codigo = :code_A";
                    $stmt = $pdo->prepare($sqlB2);
                
                    // Asignar el parámetro
                    $stmt->bindParam(':code_A', $code_A, PDO::PARAM_STR);
                
                    // Ejecutar la consulta
                    $stmt->execute();
                
                    // Obtener el resultado
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                    // Validar la existencia del registro
                    if ($row['total'] > 0) {
                        echo "El registro por codigo existe.";
                    } else {
                        define('BxCodigo', 'No existe');
                        if(BxNombre and BxCodigo == 'No existe'){
                            // Validar la imagen antes de continuar
                            $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['imagen'])) {
                                if (in_array($_FILES['imagen']['type'], $tiposPermitidos) != null) {
                                    if (!in_array($_FILES['imagen']['type'], $tiposPermitidos)) {
                                        die("Solo se permiten imágenes (JPEG, PNG, GIF).");
                                    }
                                }
                                
                                $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                                $nombreImagen = $code_A . '.' . $extension;
                                $rutaDestino = 'uploads/' . $nombreImagen;
                                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                                    echo "<script> alert('Imagen Compatible'); </script>";
                                }

                            }
                            $sqlIst = "INSERT INTO articulos_tb (nombre, codigo, descripcion, precio, stock, id_proveedor, ruta_img) VALUES (:nombre_A, :code_A, :detalle_A,:precio_A, :stock_A, :provedor_A, :ruta_img_A)";
                            $stmt = $pdo->prepare($sqlIst);
                            $stmt->bindParam(':nombre_A', $nombre_A, PDO::PARAM_STR);
                            $stmt->bindParam(':code_A', $code_A, PDO::PARAM_STR);
                            $stmt->bindParam(':detalle_A', $detalle_A, PDO::PARAM_STR);
                            $stmt->bindParam(':precio_A', $precio_A, PDO::PARAM_STR);
                            $stmt->bindParam(':stock_A', $stock_A, PDO::PARAM_STR);
                            $stmt->bindParam(':provedor_A', $provedor_A, PDO::PARAM_STR);
                            $stmt->bindParam(':ruta_img_A', $rutaDestino, PDO::PARAM_STR);
                            
                            if ($stmt->execute()) {
                                echo "<script> alert('Usuario registrado correctamente'); </script>";
                                //location.reload();
                            } else {
                                echo "<script> alert('Error al enviar el Registro'); </script>";
                            }
                            
                        }else{
                            echo "<script> alert('El articulo ya existe puedes registrar otro articulo'); </script>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "Error en la consulta: " . $e->getMessage();
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
