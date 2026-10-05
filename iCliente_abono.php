<!DOCTYPE html>
<html lang="es">
<?php
include("iCNX.php");
if (isset($_GET['id_reg'])) {
    $id = intval($_GET['id_reg']); 
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT c.id_cliente AS id_c, CONCAT(nombre, ' ', apellido_1, ' ', apellido_2) AS Nombre_Cliente, curp, cc.c_autorizado AS credito_autorizado, cc.c_disponible AS credito_disponible FROM cliente_tb c INNER JOIN credito_cliente cc ON c.id_cliente = cc.id_cliente WHERE c.id_cliente = :id_reg";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_reg', $id, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        //--------------------------------------//
        $saldo_a = $producto['credito_disponible']- $producto['credito_autorizado'];
        $adeudo = $producto['credito_autorizado'] - $producto['credito_disponible'] ;
        if ($saldo_a < 0){
            $color_saldo = "text-danger";
        }else{
            $color_saldo = "text-success";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se recibió ningún ID.";
    die();
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturar Abono</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/objetos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/font-Awesome/Font-Awesome-7.x/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="js/sweetA.js"></script>
</head>

<body>
    <?php include_once("navegacion.php"); ?>
    <div class="login-container">
        <div class="login-box" style="width: 35%; margin-top: 2%; margin-bottom: 2%;">
            <h2 class="forms-titulo"><span> <i class="fa-solid fa-hand-holding-dollar"></i> </span> Abono </h2>
            <form id="form1" name="form1" class="login-form" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i>
                    <input type="text" id="xname" name="xname" value="<?= htmlspecialchars($producto['Nombre_Cliente']); ?>" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-vcard icon" style="color: #1199d0;"></i>
                    <input type="text" id="curp" name="curp" value="<?= htmlspecialchars($producto['curp']); ?>" placeholder="CURP del Cliente">
                </div>
                <div class="form-group row">
                    <div class="mitad">
                        <label for="credito-autorizado"><i class="bi bi-currency-dollar" style="color: #1199d0;"></i> Crédito Autorizado</label>
                        <input type="number" id="credito-autorizado" name="credito-autorizado" value="<?= htmlspecialchars($producto['credito_autorizado']); ?>" placeholder="$" required>
                    </div>
                    <div class="mitad">
                        <label for="credito-disponible"><i class="bi bi-credit-card-2-back-fill" style="color: #1199d0;"></i> Saldo Actual</label>
                        <input type="number" id="abono-c" name="abono-c" class="<?= htmlspecialchars($color_saldo); ?>" value="<?= htmlspecialchars($saldo_a); ?>" disabled>
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-vcard icon" style="color: #1199d0;"></i>
                    <input type="text" id="abono" name="abono" placeholder="Capturar Abono">
                </div>
                <button type="submit" name="Xenviar" id="Xenviar" class="login-button">Abonar</button>
            </form>
            <p class="signup-text"><a href="itClientes.php" style="font-size: 16px;"><i class="bi bi-people-fill"></i> Ver Clientes</a></p>
            <!--
            <p class="signup-text">Consultar o editar informacion <a href="#">Registrar Cliente</a></p>
            -->
        </div>
    </div>
</body>


</html>

<?php

if(isset($_POST['Xenviar'])){

    $nombre_c = trim($_POST['Nombre_Cliente']);
    $curp = trim($_POST['curp']);
    $credito_a = trim($_POST['credito_autorizado']);
    $abono_c = trim($_POST['abono-c']);
    
    if (empty($id) || empty($nombre_c) ||empty($curp) || empty($credito_a) || empty($abono_c)) {
        echo "<script> Swal.fire({title: 'Campos Vacios! Favor de verificar los campos',icon: 'error', draggable: true}); </script>";
    }else{
        
        if($saldo_a >= 0){
            echo "<script> Swal.fire({title: 'Feliciades!! Cliente sin adeuos',icon: 'info', draggable: true}); window.location.href = 'itClientes.php'; </script>";
        }else{
            try {
                
                $pdo->beginTransaction();
                $SQLB_cliente = "UPDATE cliente_tb SET nombre = :nombre, apellido_1 = :apellido_1, apellido_2 = :apellido_2, curp = :curp, alias = :alias, correo = :correo, telefono = :telefono, direccion = :direccion, id_estatus = :id_estatus WHERE id_cliente = :id_cliente";
                $executaB = $pdo-prepare($SQLB_cliente);
                $executaB->bindParam(':id_cliente',$id);
                $executaB->bindParam(':nombre',$nombre_c);
                $executaB->bindParam(':apellido_1',$apellido_1);
                $executaB->bindParam(':apellido_1',$apellido_2);
                $executaB->bindParam(':apellido_1',$curp);
                $executaB->bindParam(':apellido_1',$alias);
                $executaB->bindParam(':apellido_1',$correo_c);
                $executaB->bindParam(':apellido_1',$telefono_c);
                $executaB->bindParam(':apellido_1',$direccion_c);
                $executaB->bindParam(':apellido_1',$estatus);
                if($executaB->execute()){
                    
                    echo "<script> Swal.fire({title: 'Se Actualizo con EXITO!',icon: 'success', draggable: true}); window.location.href = 'itArticulos.php'; </script>";
                    $SQL_A_creditoCliente = "UPDATE credito_cliente SET c_autorizado = :c_autorizado, c_disponible = :c_disponible, fecha_corte = :fecha_corte WHERE id_cliente = :id_cliente";
                    $executa_Qcredito = $pdo-prepare($SQL_A_creditoCliente);
                    $executa_Qcredito->bindParam(':id_cliente',$id);
                    $executa_Qcredito->bindParam(':c_autorizado',$apellido_1);
                    $executa_Qcredito->bindParam(':c_disponible',$apellido_1);
                    $executa_Qcredito->bindParam(':fecha_corte',$apellido_1);
                }else{
                    echo "<script> Swal.fire({title: 'Error! datos no Actualizados',icon: 'error', draggable: true}); </script>";
                        
                }
                $executaB->bindParam(':apellido_1',$fecha_c);
                $executaB->bindParam(':apellido_1',$fecha_c);
            
            } catch (PDOException $e) {
                $pdo->rollBack();
                echo "<script> Swal.fire({title: 'Error al enviar el Registro! . $e->getMessage()',icon: 'error', draggable: true}); </script>";
            }      
        }
        
    }

}
?>
