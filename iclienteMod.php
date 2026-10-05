<!DOCTYPE html>
<html lang="es">
<?php
include("iCNX.php");
if (isset($_GET['id_reg'])) {
    $id = intval($_GET['id_reg']); 
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = "SELECT c.id_cliente AS id_c, nombre, apellido_1, apellido_2, curp, alias, correo, telefono, direccion, cc.c_autorizado AS credito_autorizado, cc.c_disponible AS credito_disponible, c.id_estatus AS id_estatus_cliente, est.descripcion AS estado_cliente, cc.fecha_inicio AS fecha_i, cc.fecha_corte AS fecha_corte FROM cliente_tb c INNER JOIN credito_cliente cc ON c.id_cliente = cc.id_cliente LEFT JOIN catalogo_estatus est ON c.id_estatus = est.id_estatus WHERE c.id_cliente = :id_reg";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id_reg', $id, PDO::PARAM_INT);
        $stmt->execute();
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        //--------------------------------------//
        $est_query = "SELECT * FROM catalogo_estatus";
        $stmt_est = $pdo->prepare($est_query);
        $stmt_est->execute();
        $c_estatus = $stmt_est->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No se recibió ningún ID.";
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
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
            <h2 class="forms-titulo"><span> <i class="fas fa-user-edit"></i> </span> Cliente</h2>
            <form id="form1" name="form1" class="login-form" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i>
                    <input type="text" id="xname" name="xname" value="<?= htmlspecialchars($producto['nombre']); ?>" placeholder="Nombre" required>
                </div>
                <div class="form-group row">
                    <div class="mitad">
                        <input type="text" id="ap1" name="ap1" value="<?= htmlspecialchars($producto['apellido_1']); ?>" placeholder="Primer Apellido" required>
                    </div>
                    <div class="mitad">
                        <input type="text" id="ap2" name="ap2" value="<?= htmlspecialchars($producto['apellido_2']); ?>" placeholder="Segundo Apellido">
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-vcard icon" style="color: #1199d0;"></i>
                    <input type="text" id="curp" name="curp" value="<?= htmlspecialchars($producto['curp']); ?>" placeholder="CURP del Cliente">
                </div>
                <div class="form-group">
                    <i class="bi bi-person-exclamation icon" style="color: #1199d0;"></i>
                    <input type="text" id="alias" name="alias" value="<?= htmlspecialchars($producto['alias']); ?>" placeholder="Alias del Cliente">
                </div>
                <div class="form-group">
                    <i class="bi bi-envelope-at icon" style="color: #1199d0;"></i>
                    <input type="email" id="xmail" name="xmail" value="<?= htmlspecialchars($producto['correo']); ?>" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-telephone-fill icon" style="color: #66fa1c;"></i>
                    <input type="text" id="xphone" name="xphone" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" value="<?= htmlspecialchars($producto['telefono']); ?>" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-pin-map-fill icon" style="color: #1199d0;"></i>
                    <textarea id="xaddres" name="xaddres" placeholder=" Direccion"></textarea>
                    <script>
                        document.getElementById('xaddres').value = "<?= htmlspecialchars($producto['direccion']); ?>";

                    </script>
                </div>
                <div class="form-group row">
                    <div class="mitad">
                        <label for="credito-autorizado"><i class="bi bi-currency-dollar" style="color: #1199d0;"></i> Crédito Autorizado</label>
                        <input type="number" id="credito-autorizado" name="credito-autorizado" value="<?= htmlspecialchars($producto['credito_autorizado']); ?>" placeholder="$" required>
                    </div>
                    <div class="mitad">
                        <label for="credito-disponible"><i class="bi bi-credit-card-2-back-fill" style="color: #1199d0;"></i> Crédito Disponible</label>
                        <input type="number" id="credito-disponible" name="credito-disponible" value="<?= htmlspecialchars($producto['credito_disponible']); ?>" placeholder="$">
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-fill-gear icon" style="color: #1199d0;"></i>
                    <select id="xestatus" name="xestatus">
                        <?php foreach ($c_estatus as $item): ?>
                        <option value="<?= $item['id_estatus'] ?>" <?= ($item['id_estatus'] == $producto['id_estatus_cliente']) ? 'selected' : '' ?>><?= $item['descripcion'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <i class="bi bi-calendar-check" style="color: #1199d0; padding-left: 10px;"></i><label for="fecha-corte">Fecha de Corte</label>
                    <input type="date" id="fecha-corte" name="fecha-corte" value="<?= htmlspecialchars($producto['fecha_corte']); ?>" required>
                </div>
                <button type="submit" name="Xenviar" id="Xenviar" class="login-button">Enviar</button>
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
    $nombre_c = trim($_POST['xname']);
    $apellido_1 = trim($_POST['ap1']);
    $apellido_2 = trim($_POST['ap2']);
    $curp = trim($_POST['curp']);
    $alias = trim($_POST['alias']);
    $correo_c = trim($_POST['xmail']);
    $direccion_c = trim($_POST['xaddres']);
    $telefono_c = trim($_POST['xphone']);
    $credito_a = trim($_POST['credito-autorizado']);
    $credito_disponible = trim($_POST['credito-disponible']);
    $estatus = trim($_POST['xestatus']);
    //$fecha_reg = trim($_POST['fecha-corte']);
    $fecha_c = trim($_POST['fecha-corte']);
    if (empty($nombre_c) || empty($apellido_1) ||empty($curp) || empty($credito_a) || empty($fecha_c)) {
        echo "<script> Swal.fire({title: 'Campos Vacios! Favor de llenar campos',icon: 'error', draggable: true}); </script>";
    }else{
        try {

            $pdo->beginTransaction();
            //$SQLB_cliente = "SELECT COUNT(*) AS total FROM cliente_tb WHERE nombre = :nombre AND apellido_1 = :apellido_1";
            $SQLB_cliente = "UPDATE cliente_tb SET nombre = :nombre, apellido_1 = :apellido_1, apellido_2 = :apellido_2, curp = :curp, alias = :alias, correo = :correo, telefono = :telefono, direccion = :direccion, id_estatus = :id_estatus WHERE id_cliente = :id_reg";
            $executaB = $pdo->prepare($SQLB_cliente);
            $executaB->bindParam(':id_reg', $id, PDO::PARAM_INT);
            $executaB->bindParam(':nombre',$nombre_c);
            $executaB->bindParam(':apellido_1',$apellido_1);
            $executaB->bindParam(':apellido_2',$apellido_2);
            $executaB->bindParam(':curp',$curp);
            $executaB->bindParam(':alias',$alias);
            $executaB->bindParam(':correo',$correo_c);
            $executaB->bindParam(':telefono',$telefono_c);
            $executaB->bindParam(':direccion',$direccion_c);
            $executaB->bindParam(':id_estatus',$estatus);
            //$executaB->bindParam(':fecha_corte',$fecha_c);
            if($executaB->execute()){
                
                $SQL_A_creditoCliente = "UPDATE credito_cliente SET c_autorizado = :c_autorizado, c_disponible = :c_disponible, fecha_corte = :fecha_corte WHERE id_cliente = :id_reg";
                $executa_Qcredito = $pdo->prepare($SQL_A_creditoCliente);
                $executa_Qcredito->bindParam(':id_reg', $id, PDO::PARAM_INT);
                $executa_Qcredito->bindParam(':c_autorizado',$credito_a);
                $executa_Qcredito->bindParam(':c_disponible',$credito_disponible);
                $executa_Qcredito->bindParam(':fecha_corte',$fecha_c);
                $executa_Qcredito->execute();
            }else{
                echo "<script> Swal.fire({title: 'Error! datos no Actualizados',icon: 'error', draggable: true}); </script>";
            }
            $pdo->commit();
            echo "<script> Swal.fire({title: 'Se Actualizo con EXITO!',icon: 'success', draggable: true}); window.location.href = 'itClientes.php'; </script>";
            //$fila = $executaB->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $errormsg = $e->getMessage();
            echo "<script> Swal.fire({title: 'Error al enviar el Registro! . $e->getMessage()',icon: 'error', draggable: true}); </script>";
            $pdo->rollBack();
        }
    }

}
?>
