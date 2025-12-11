<?php
include("iCNX.php");

$categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : '';

try {
    if ($categoria_id) {
        $sql = "SELECT * FROM articulos_tb WHERE id_categoria = :categoria_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
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
