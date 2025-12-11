<?php
include("iCNX.php");
if (isset($_POST['tipo_venta_id'])) {
    $tipo_venta_id = $_POST['tipo_venta_id'];

    $stmt = $pdo->prepare("SELECT * FROM catalogo_d_pago WHERE id_pago = ?");
    $stmt->execute([$tipo_venta_id]);
    $formas_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($formas_pago);
}
?>
