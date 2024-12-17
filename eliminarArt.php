<?php
// Conexión a la base de datos
include('iCNX.php');
// Verificamos que se reciba el ID del producto
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Eliminamos el producto
    $sql = "DELETE FROM articulos_tb WHERE id_articulo = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID no recibido']);
}
?>
