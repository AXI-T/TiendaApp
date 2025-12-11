<?php
include("iCNX.php");

// Habilitar reporte de errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar datos requeridos
$required_fields = ['cliente_id', 'user_id', 'tipo_venta_id', 'forma_pago_id', 'monto_pagar', 'pagorecibido', 'productos'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Falta el campo requerido: $field"]);
        exit;
    }
}

// Obtener datos
$clienteID = $_POST['cliente_id'];
$usuarioID = $_POST['user_id'];
$tipoVentaID = $_POST['tipo_venta_id'];
$formaPagoID = $_POST['forma_pago_id'];
$montoPagar = (float)$_POST['monto_pagar'];
$PagoRecibido = (float)$_POST['pagorecibido'];
$productos = json_decode($_POST['productos'], true);

// Validar JSON de productos
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Error en formato de productos: ' . json_last_error_msg()]);
    exit;
}

try {
    if ($PagoRecibido >= $montoPagar) {
        $cambio = $PagoRecibido - $montoPagar;
        
        $pdo->beginTransaction();

        // Insertar venta principal
        $sqlVenta = "INSERT INTO venta_tb (id_cliente, id_usuario, id_pago, id_d_pago, fecha_v, hora_v, totalventa, cantidadpago, cambio) 
                     VALUES (:cliente_id, :user_id, :tipo_venta_id, :forma_pago_id, CURDATE(), CURTIME(), :monto_pagar, :pagorecibido, :cambio)";
        //agregar el update para restar la cantidad a el stok
        
        
        $stmtVenta = $pdo->prepare($sqlVenta);
        $stmtVenta->execute([
            ':cliente_id' => $clienteID,
            ':user_id' => $usuarioID,
            ':tipo_venta_id' => $tipoVentaID,
            ':forma_pago_id' => $formaPagoID,
            ':monto_pagar' => $montoPagar,
            ':pagorecibido' => $PagoRecibido,
            ':cambio' => $cambio
        ]);

        $ventaID = $pdo->lastInsertId();

        // Insertar detalles de venta
        foreach ($productos as $producto) {
            // Obtener ID y precio del producto
            $sqlProducto = "SELECT id_articulo, stock, precio FROM articulos_tb WHERE nombre = :nombre";
            $stmtProducto = $pdo->prepare($sqlProducto);
            $stmtProducto->execute([':nombre' => $producto['nombre']]);
            $productoData = $stmtProducto->fetch(PDO::FETCH_ASSOC);

            if ($productoData) {
                $subtotal = $producto['cantidad'] * $productoData['precio'];
                if($productoData['stock']> $producto['cantidad']){
                    $nuevoStock = $productoData['stock'] - $producto['cantidad'];
                }else{
                    $nuevoStock = 0;
                }
                
                
                $sqlDetalle = "INSERT INTO detalle_venta (id_venta, id_articulo, cantidad, precio_unitario, subtotal) 
                              VALUES (:venta_id, :producto_id, :cantidad, :precio, :subtotal)";
                $sqlStok = "UPDATE articulos_tb SET stock = :stock_A WHERE codigo = :code";
                $stmtDetalle = $pdo->prepare($sqlDetalle);
                $stmtDetalle->execute([
                    ':venta_id' => $ventaID,
                    ':producto_id' => $productoData['id_articulo'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio' => $productoData['precio'],
                    ':subtotal' => $subtotal
                ]);
                $stmtStok = $pdo->prepare($sqlStok);
                $stmtStok->bindParam(':stock_A', $nuevoStock, PDO::PARAM_STR);
                $stmtStok->execute();
            }
        }

        $pdo->commit();
        echo json_encode(['success' => true, 'message' => 'Venta registrada con éxito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'El pago recibido es menor al monto total']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'success' => false, 
        'message' => 'Error al registrar la venta', 
        'error' => $e->getMessage(),
        'error_info' => isset($stmtVenta) ? $stmtVenta->errorInfo() : null
    ]);
}
?>
