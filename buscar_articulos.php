<?php
// Configuración de conexión a la base de datos
include("iCNX.php");
try {
    $query = isset($_POST['query']) ? trim($_POST['query']) : '';

    // Crear la consulta SQL con LIKE para buscar por nombre o código
    $sql = "SELECT a.*, pv.id_proveedor, (pv.nombre) AS pvnombre FROM articulos_tb a INNER JOIN proveedor_tb pv ON a.id_proveedor = pv.id_proveedor  WHERE a.nombre LIKE :query OR a.codigo LIKE :query";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();

    // Construir las filas de la tabla
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($resultados) > 0) {
        foreach ($resultados as $producto) {
            echo "<tr>
                    <td><img src='' alt='Imagen Producto' style='max-width: 50px;'></td>
                    <td>" . htmlspecialchars($producto['nombre']) . "</td>
                    <td>" . htmlspecialchars($producto['codigo']) . "</td>
                    <td>" . htmlspecialchars($producto['descripcion']) . "</td>
                    <td>" . htmlspecialchars($producto['precio']) . "</td>
                    <td>" . htmlspecialchars($producto['stock']) . "</td>
                    <td>" . htmlspecialchars($producto['pvnombre']) . "</td>
                    <td class='actions'><button class='delete-button' onclick='eliminarProducto(". htmlspecialchars($producto['id_articulo']).");'><i class='bi bi-trash3'></i></button><button class='edit-button' onclick='editarProducto(". htmlspecialchars($producto['id_articulo']).");'><i class='bi bi-pencil'></i></button></td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No se encontraron productos.</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
