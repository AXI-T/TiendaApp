<?php
include("iCNX.php");

if (isset($_GET['id_reg'])) {
    $id = intval($_GET['id_reg']); 
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT * FROM articulos_tb WHERE id_articulo = :id_reg";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_reg', $id, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $query1 = "SELECT id_medida, unidad FROM catalogo_medidas";
        $stmt1 = $pdo->query($query1);
        $medidas = $stmt1->fetchAll(PDO::FETCH_ASSOC);
        
        $query2 = "SELECT id_categoria, nombre FROM categorias_tb";
        $stmt2 = $pdo->query($query2);
        $categorias = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        
        $query3 = "SELECT id_proveedor, nombre FROM proveedor_tb";
        $stmt3 = $pdo->query($query3);
        $proveedores = $stmt3->fetchAll(PDO::FETCH_ASSOC);
        
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se recibió ningún ID.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Articulos</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/objetos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="js/sweetA.js"></script>
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo"><span> <i class="bi bi-basket2-fill"></i> </span> Articulos</h2>
            <form class="login-form" method="post" enctype="multipart/form-data">
                <!--<label for="email">Email</label>-->
                <div class="contenedor" align="center">
                    <img id="foto" src="<?= htmlspecialchars($producto['ruta_img']); ?>" alt="Previsualización" style="max-width: 100px; height: 100px;" />
                </div>
                <div class="form-row">
                    <div class="input-group">
                        <label for="imagen"><i class="bi bi-image" style="color: #1199d0;"></i> Imagen del Producto</label>
                        <input type="file" id="imagen" name="imagen" accept="image/*">
                    </div>
                    <!--<div class="input-group">
                        <input type="text" id="nombre" placeholder="&#128273; Nombre" required>
                    </div>
                    <div class="input-group">
                        <textarea id="detalle" class="objetos-box" placeholder="&#128221; Detalle del Producto"></textarea>
                    </div> -->

                </div>
                <div class="form-group">
                    <!--<i class="bi bi-universal-access icon" style="color: #43cced;"></i> Icono de usuario -->
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre del Producto" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-upc-scan icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="codea" name="codea" placeholder="Codigo del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['codigo']); ?>" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-bag icon" style="color: #1199d0;"></i>
                    <select id="medida" name="medida">
                        <?php foreach ($medidas as $medida):
                            if($producto['u_medida'] === $medida['unidad']){ ?>
                        <option value="<?= $medida['unidad'] ?>" selected="<?= $medida['unidad'] ?>"> <?= htmlspecialchars($medida['unidad']) ?> </option>
                        <?php }else{ ?>
                        <option value="<?= $medida['unidad'] ?>"> <?= htmlspecialchars($medida['unidad']) ?> </option>
                        <?php } 
                    endforeach; 
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <i class="bi bi-ticket-detailed icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <textarea id="detalle" name="detalle" placeholder=" Detalle del Producto"></textarea>
                    <script>
                        document.getElementById('detalle').value = "<?= htmlspecialchars($producto['descripcion']); ?>";

                    </script>
                </div>
                <div class="form-group">
                    <i class="bi bi-tags-fill icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="precio" name="precio" placeholder="Precio del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['precio']); ?>" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-ticket-detailed icon" style="color: #1199d0;"></i>
                    <select id="categoria" name="categoria">
                        <?php foreach ($categorias as $categoria):
                            if($producto['id_categoria'] === $categoria['id_categoria']){ ?>
                        <option value="<?= $categoria['id_categoria'] ?>" selected="<?= $categoria['id_categoria'] ?>"> <?= htmlspecialchars($categoria['nombre']) ?> </option>
                        <?php }else{ ?>
                        <option value="<?= $categoria['id_categoria'] ?>"> <?= htmlspecialchars($categoria['nombre']) ?> </option>
                        <?php } 
                    endforeach; 
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <i class="bi bi-box-seam-fill icon" style="color: #d58938;"></i> <!-- Icono de usuario -->
                    <input type="text" id="stock" name="stock" placeholder="Existencias del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['stock']); ?>" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-bus-front-fill icon" style="color: #1199d0;"></i>
                    <select id="proveedor" name="proveedor">
                        <?php foreach ($proveedores as $proveedor):
                            if($producto['id_proveedor'] === $proveedor['id_proveedor']){ ?>
                        <option value="<?= $proveedor['id_proveedor'] ?>" selected="<?= $proveedor['id_proveedor'] ?>"> <?= htmlspecialchars($proveedor['nombre']) ?> </option>
                        <?php }else{ ?>
                        <option value="<?= $proveedor['id_proveedor'] ?>"> <?= htmlspecialchars($proveedor['nombre']) ?> </option>
                        <?php } 
                    endforeach; 
                    ?>
                    </select>
                </div>
                <!--<label for="password">Password</label>-->
                <button type="submit" name="Xenviar" class="login-button">Enviar</button>
            </form>
            <p class="signup-text">Ver <a href="itArticulos.php">Articulos</a></p>
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
    $medida = trim($_POST['medida']);
    $detalle_A = trim($_POST['detalle']);
    $precio_A = trim($_POST['precio']);
    $categoria_A = trim($_POST['categoria']);
    $stock_A = trim($_POST['stock']);
    $provedor_A = trim($_POST['proveedor']);
    if (empty($nombre_A) || empty($code_A) || empty($medida) || empty($detalle_A) || empty($precio_A) || $categoria_A ==0 || empty($stock_A) || $provedor_A ==  0) {
        die("Todos los campos son obligatorios.");
    }else{
        try {
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
                // Verificar si ya existe una imagen con ese nombre
                if (file_exists($rutaDestino)) {
                    unlink($rutaDestino); // Eliminar la imagen existente
                }
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
                    echo "<script> alert('Imagen Compatible'); </script>";
                }
            }
            $query = "UPDATE articulos_tb SET  nombre = :nombre_A, codigo = :code_A, u_medida = :medida, descripcion = :detalle_A, id_categoria = :categoria_A, precio = :precio_A, stock = :stock_A, id_proveedor = :provedor_A, ruta_img = :ruta_img_A WHERE id_articulo = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_A', $nombre_A, PDO::PARAM_STR);
            $stmt->bindParam(':code_A', $code_A, PDO::PARAM_STR);
            $stmt->bindParam(':medida', $medida, PDO::PARAM_STR);
            $stmt->bindParam(':detalle_A', $detalle_A, PDO::PARAM_STR);
            $stmt->bindParam(':categoria_A', $categoria_A, PDO::PARAM_STR);
            $stmt->bindParam(':precio_A', $precio_A, PDO::PARAM_STR);
            $stmt->bindParam(':stock_A', $stock_A, PDO::PARAM_STR);
            $stmt->bindParam(':provedor_A', $provedor_A, PDO::PARAM_STR);
            $stmt->bindParam(':ruta_img_A', $rutaDestino, PDO::PARAM_STR);
            $stmt->execute();
            echo "<script> Swal.fire({title: 'Se Actualizo con EXITO!',icon: 'success', draggable: true}); window.location.href = 'itArticulos.php'; </script>";
        } catch (PDOException $e) {
            echo "<script> Swal.fire({title: 'Error! datos no Actualizados',icon: 'error', draggable: true}); </script>";
            echo "Error en la Actualizacion de los datos: " . $e->getMessage();
        }
    
        // Cerrar conexión
        $pdo = null;
    }
}
?>
