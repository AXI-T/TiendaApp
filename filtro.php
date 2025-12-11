<?php
include("iCNX.php");
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$sqlP = "SELECT a.*, p.nombre AS nombreP FROM articulos_tb a INNER JOIN proveedor_tb p ON a.id_proveedor = p.id_proveedor";
try {
    if ($search) {
        $sql = $sqlP." WHERE LOWER(a.nombre) LIKE :search OR LOWER(a.codigo) LIKE :search OR LOWER(a.descripcion) LIKE :search OR p.nombre LIKE :search";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":search", "%$search%", PDO::PARAM_STR);
        
    } else {
        $sql = "SELECT * FROM articulos_tb";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($productos)) {
        foreach ($productos as $producto) {
            echo '
                <div class="product-card">
                    <div class="product-image"><img src="'.htmlspecialchars($producto['ruta_img']).'" alt="Imagen Producto" style="max-width: 50px;"></div>
                    <div class="product-info">
                        <p class="product-name">'.htmlspecialchars($producto['nombre']).'</p>
                        <p class="product-price">$ '.htmlspecialchars($producto['precio']).'</p>
                        <button class="add-button">+</button>
                    </div>
                </div>';
        }
    } else {
        echo "<p>No hay productos en esta categoría.</p>";
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
