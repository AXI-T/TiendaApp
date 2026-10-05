<?php
include("iCNX.php");
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$sql_cliente = "SELECT c.id_cliente, CONCAT(nombre, ' ', apellido_1, ' ', apellido_2) AS Nombre_Cliente, curp, alias, cc.c_autorizado AS Limite_credito, cc.c_disponible AS credito_disponible, est.descripcion AS estado_cliente, cc.fecha_corte AS fecha_corte FROM cliente_tb c INNER JOIN credito_cliente cc ON c.id_cliente = cc.id_cliente LEFT JOIN catalogo_estatus est ON c.id_estatus = est.id_estatus ";
try {
    if ($search) {
        
        $sql = $sql_cliente."WHERE c.id_estatus = 2 AND (c.nombre LIKE :search OR c.apellido_1 LIKE :search OR c.apellido_2 LIKE :search OR c.curp LIKE :search OR c.alias LIKE :search)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":search", "%$search%", PDO::PARAM_STR);
        
    } else {
        $sql =  $sql_cliente."WHERE c.id_estatus = 2";
        $stmt = $pdo->prepare($sql);
    }
    $stmt->execute();
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($clientes)) {
        foreach ($clientes as $cliente) {
            //aqui validamos que si es publico en general el nombre le quitammos los espacios
            
            echo '<option value="'.htmlspecialchars($cliente['Nombre_Cliente']).'" data-id="'.htmlspecialchars($cliente['id_cliente']).'" data-credito="'.htmlspecialchars($cliente['credito_disponible']).'"></option>';
        }
    } else {
        echo "<option value='' data-id='' data-credito=''></option>";
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
}
?>
