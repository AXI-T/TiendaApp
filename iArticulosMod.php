<?php
include("iCNX.php");
if (isset($_GET['id_reg'])) {
    $id = intval($_GET['id_reg']); // Asegúrate de sanitizar el dato para evitar inyección SQL
    try {
        $query = "SELECT * FROM articulos_tb WHERE id_articulo = :id_reg";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_reg', $id, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>
<!--aqui pondre unos estilos locales para agrupar el input y el icono -->

<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo"><span> <i class="bi bi-basket2-fill"></i> </span> Articulos</h2>
            <form class="login-form" method="post">
                <!--<label for="email">Email</label>-->
                <div class="form-row">
                    <div class="input-group">
                        <label for="imagen-producto"><i class="bi bi-image" style="color: #1199d0;"></i> Imagen del Producto</label>
                        <input type="file" id="imagen-producto" name="imagen-producto" accept="image/*">
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
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre del Producto"  required>
                </div>
                <div class="form-group">
                    <i class="bi bi-upc-scan icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="codea" name="codea" placeholder="Codigo del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['codigo']); ?>" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-ticket-detailed icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <textarea id="detalle" name="detalle"  placeholder=" Detalle del Producto"></textarea>
                    <script> document.getElementById('detalle').value = "<?= htmlspecialchars($producto['descripcion']); ?>"; </script>
                </div>
                <div class="form-group">
                    <i class="bi bi-tags-fill icon" style="color: #1199d0;"></i> <!-- Icono de usuario -->
                    <input type="text" id="precio" name="precio" placeholder="Precio del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['precio']); ?>" required>
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
                    <input type="text" id="stock" name="stock" placeholder="Existencias del Producto" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['stock']); ?>" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-bus-front-fill icon" style="color: #1199d0;"></i>
                    <!-- Icono de usuario 
                    <input type="text" id="phone" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>-->
                    <?php
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
                        
                        <?php foreach ($proveedores as $proveedor):
                            if($producto['id_proveedor'] === $proveedor['id_proveedor']){ ?>
                            <option value="<?= $proveedor['id_proveedor'] ?>" selected="<?= $proveedor['id_proveedor'] ?>"> <?= htmlspecialchars($proveedor['nombre']) ?> </option>
                        <?php }else{ ?>
                            <option value="<?= $proveedor['id_proveedor'] ?>" > <?= htmlspecialchars($proveedor['nombre']) ?> </option>
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
            $query = "UPDATE articulos_tb SET  nombre = :nombre_A, codigo = :code_A, descripcion = :detalle_A, precio = :precio_A, stock = :stock_A, id_proveedor = :provedor_A WHERE id_articulo = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre_A', $nombre_A, PDO::PARAM_STR);
            $stmt->bindParam(':code_A', $code_A, PDO::PARAM_STR);
            $stmt->bindParam(':detalle_A', $detalle_A, PDO::PARAM_STR);
            $stmt->bindParam(':precio_A', $precio_A, PDO::PARAM_STR);
            $stmt->bindParam(':stock_A', $stock_A, PDO::PARAM_STR);
            $stmt->bindParam(':provedor_A', $provedor_A, PDO::PARAM_STR);
            $stmt->execute();
            echo "<script>alert('Se Actualizo el Articulo con EXITO'); window.location.href = 'itArticulos.php';</script>";
        } catch (PDOException $e) {
            echo "Error en la Actualizacion de los datos: " . $e->getMessage();
        }
    
        // Cerrar conexión
        $pdo = null;
    }
}
?>





