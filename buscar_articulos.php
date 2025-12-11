<?php
include("iCNX.php");
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$categoria_id = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : '';
try {
    $sqlP = "SELECT a.*, p.nombre AS nombreP FROM articulos_tb a INNER JOIN proveedor_tb p ON a.id_proveedor = p.id_proveedor";
    if ($search) {
        $sql = $sqlP." WHERE LOWER(a.nombre) LIKE :search OR LOWER(a.codigo) LIKE :search OR LOWER(a.descripcion) LIKE :search OR p.nombre LIKE :search";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":search", "%$search%", PDO::PARAM_STR);
        
    } elseif($categoria_id) {
        $sql = $sqlP." WHERE id_categoria = :categoria_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":categoria_id", $categoria_id, PDO::PARAM_INT);
    } else {
        $sql = $sqlP;
        $stmt = $pdo->prepare($sql);
    }
// aqui cargaremos la tabla
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($productos)) {
        foreach ($productos as $producto) {
            echo '<tr>';
                echo '<td><img src="' . htmlspecialchars($producto["ruta_img"]) . '" alt="Imagen Producto" style="max-width: 50px;"></td>';
                echo '<td>' . htmlspecialchars($producto["nombre"]) . '</td>';
                echo '<td>' . htmlspecialchars($producto["codigo"]) . '</td>';
                echo '<td>' . htmlspecialchars($producto["descripcion"]) . '</td>';
                echo '<td>$' . htmlspecialchars($producto["precio"]) . '</td>';
                echo '<td>' . htmlspecialchars($producto["stock"]) . '</td>';
                echo '<td>' . htmlspecialchars($producto["nombreP"]) . '</td>';
                echo '<td class="actions" style="display: grid; place-items: center;">
                    <button class="delete-button" onclick="eliminarProducto(' . htmlspecialchars($producto["id_articulo"]) . ')">
                        <i class="bi bi-trash3"></i>
                    </button>
                    <button class="edit-button" onclick="editarProducto(' . htmlspecialchars($producto["id_articulo"]) . ')">
                        <i class="bi bi-pencil"></i>
                    </button>
                </td>';
            echo '</tr>';
}
} else {
echo '
<tr>
    <td colspan="8">No hay productos disponibles</td>
</tr>';
}
} catch (PDOException $e) {
echo "Error en la consulta: " . $e->getMessage();
}
?>
