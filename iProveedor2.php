<?php
// ══════════════════════════════════════════════════════════════════════════════
//  PHP PRIMERO — así los Swal.fire se imprimen dentro del <body> correctamente
// ══════════════════════════════════════════════════════════════════════════════
include("iCNX.php");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Variable que llevará el mensaje de alerta al HTML
$swal_script = "";

if (isset($_POST['Xenviar'])) {

    $nombre_pv    = trim($_POST['nombre']);
    $contacto_pv  = trim($_POST['contacto']);
    $direccion_pv = trim($_POST['direccion']);
    $telefono_pv  = trim($_POST['telefono']);

    // ── Validar campos obligatorios ──────────────────────────────────────────
    if (empty($nombre_pv) || empty($contacto_pv) || empty($direccion_pv) || empty($telefono_pv)) {
        $swal_script = "Swal.fire({ title: 'Todos los campos son obligatorios.', icon: 'warning', draggable: true });";
    } else {
         try {
                // Verificar si el proveedor ya existe
                $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM proveedor_tb WHERE nombre = :nombre_pv");
                $stmt->bindParam(':nombre_pv', $nombre_pv, PDO::PARAM_STR);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row['total'] > 0) {
                    $swal_script = "Swal.fire({ title: 'Proveedor ya registrado', text: 'Ya existe un proveedor con ese nombre.', icon: 'warning', draggable: true });";
                } else {
                    // aqui va la imagen del proveedor 
                            // ── Procesar imagen ──────────────────────────────────────────────────
                    $ruta_imagen = null;   // se guarda en BD; null si no subieron imagen
                    $archivo = $_FILES['imagen-proveedor'] ?? null;
                    $imagen_subida = $archivo && $archivo['error'] === UPLOAD_ERR_OK && $archivo['size'] > 0;
                    if ($imagen_subida) {
                        // Tipos MIME permitidos
                        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                        $tipo_real = mime_content_type($archivo['tmp_name']); // valida contenido real, no solo extensión
                        if (!in_array($tipo_real, $tipos_permitidos)) {
                            $swal_script = "Swal.fire({ title: 'Imagen no válida', text: 'Solo se permiten JPG, PNG, WEBP o GIF.', icon: 'error' });";
                            $imagen_subida = false; // cancelar subida
                        } else {
                            // Extensión a partir del tipo MIME real (más seguro que usar el nombre original)
                            $extensiones = [
                                'image/jpeg' => 'jpg',
                                'image/png'  => 'png',
                                'image/webp' => 'webp',
                                'image/gif'  => 'gif',
                            ];
                            $extension = $extensiones[$tipo_real];
                            // ── Sanitizar el nombre del proveedor para usarlo como nombre de archivo
                            $nombre_archivo = strtolower($nombre_pv);
                            $nombre_archivo = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre_archivo); // quita acentos: á→a
                            $nombre_archivo = preg_replace('/[^a-z0-9]+/', '_', $nombre_archivo);  // espacios/especiales → _
                            $nombre_archivo = trim($nombre_archivo, '_');                           // limpiar bordes
                            // Nombre final: nombre_proveedor.ext  (ej: distribuidora_garcia.jpg)
                            $nombre_final = $nombre_archivo . '.' . $extension;
                            // ── Carpeta de destino ───────────────────────────────────────
                            $carpeta = __DIR__ . '/uploads/proveedores/';
                            if (!is_dir($carpeta)) {
                                mkdir($carpeta, 0755, true); // crea la carpeta si no existe
                            }
                            $destino = $carpeta . $nombre_final;
                            if (move_uploaded_file($archivo['tmp_name'], $destino)) {
                                $ruta_imagen = 'uploads/proveedores/' . $nombre_final; // ruta relativa para guardar en BD
                            } else {
                                $swal_script   = "Swal.fire({ title: 'No se pudo guardar la imagen.', icon: 'error' });";
                                $imagen_subida = false;
                            }
                        }
                    }
                    //
                    // INSERT — incluye ruta_img (ajusta el nombre de columna si es diferente en tu BD)
                    $sqlIst = "INSERT INTO proveedor_tb (nombre, contacto, telefono, direccion, ruta_img)
                               VALUES (:nombre_pv, :contacto_pv, :telefono_pv, :direccion_pv, :ruta_img)";
                    $stmt = $pdo->prepare($sqlIst);
                    $stmt->bindParam(':nombre_pv',    $nombre_pv,    PDO::PARAM_STR);
                    $stmt->bindParam(':contacto_pv',  $contacto_pv,  PDO::PARAM_STR);
                    $stmt->bindParam(':telefono_pv',  $telefono_pv,  PDO::PARAM_STR); // corregido: era PARAM_INT
                    $stmt->bindParam(':direccion_pv', $direccion_pv, PDO::PARAM_STR);
                    $stmt->bindParam(':ruta_img',     $ruta_imagen,  PDO::PARAM_STR); // null si no subió imagen

                    if ($stmt->execute()) {
                        $swal_script = "Swal.fire({ title: '¡Registro Exitoso!', icon: 'success', draggable: true });";
                    } else {
                        $swal_script = "Swal.fire({ title: 'Error al guardar el registro.', icon: 'error', draggable: true });";
                    }
                }
            } catch (PDOException $e) {
                // Escapar el mensaje de error para no romper el JS
                $msg = addslashes($e->getMessage());
                $swal_script = "Swal.fire({ title: 'Error en la base de datos', text: '$msg', icon: 'error' });";
            } finally {
                $pdo = null;
            }

    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Proveedores</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <script src="js/sweetA.js"></script>
</head>

<body>
    <?php include_once("navegacion.php"); ?>

    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo">
                <span><i class="bi bi-bus-front-fill"></i></span> Proveedores
            </h2>

            <!-- FIX: enctype="multipart/form-data" es obligatorio para subir archivos -->
            <form method="post" class="login-form" enctype="multipart/form-data">

                <div class="form-row">
                    <div class="input-group">
                        <label for="imagen-proveedor">
                            <i class="bi bi-image" style="color:#1199d0;"></i> Imagen del Proveedor
                        </label>
                        <input type="file" id="imagen-proveedor" name="imagen-proveedor" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color:#1199d0;"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre del proveedor" required>
                </div>

                <div class="form-group">
                    <i class="bi bi-person-fill-exclamation icon" style="color:#1199d0;"></i>
                    <input type="text" id="contacto" name="contacto" placeholder="Contacto" required>
                </div>

                <div class="form-group">
                    <i class="bi bi-pin-map-fill icon" style="color:#1199d0;"></i>
                    <textarea id="direccion" name="direccion" placeholder="Dirección" required></textarea>
                </div>

                <div class="form-group">
                    <i class="bi bi-telephone-fill icon" style="color:#66fa1c;"></i>
                    <input type="text" id="telefono" name="telefono" placeholder="Teléfono" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                </div>

                <button type="submit" name="Xenviar" id="Xenviar" class="login-button">
                    Enviar
                </button>
            </form>

            <p class="signup-text">
                <a href="itProveedores.php" style="font-size:16px;">
                    <i class="bi bi-bus-front-fill"></i> Ver Proveedores
                </a>
            </p>
        </div>
    </div>

    <!-- Alerta generada por PHP (vacía si no hubo POST) -->
    <?php if (!empty($swal_script)): ?>
    <script>
        <?php echo $swal_script; ?>

    </script>
    <?php endif; ?>

</body>

</html>
