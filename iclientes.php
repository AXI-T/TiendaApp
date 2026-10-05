<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Cliente</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/objetos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="js/sweetA.js"></script>
</head>

<body>
    <?php include_once("navegacion.php"); ?>
    <div class="login-container">
        <div class="login-box" style="width: 35%; margin-top: 2%; margin-bottom: 2%;">
            <h2 class="forms-titulo"><span> <i class="bi bi-person-fill-add"></i> </span> Cliente</h2>
            <form id="form1" name="form1" class="login-form" method="post" enctype="multipart/form-data">
                <!--<div class="form-group"> esto es para quitar el atributo hidden
                    <button type="button" id="btnsearch" name="btnsearch" onclick="acbuscar()" class="btnicon"><i class="bi bi-search"></i></button>
                    <input type="text" id="buscar" placeholder="Buscar" hidden>
                    <script>
    function acbuscar() {
        const buscarInput = document.getElementById('buscar');

        if (buscarInput.hasAttribute('hidden')) {
            buscarInput.removeAttribute('hidden');
            buscarInput.focus(); // Pon el enfoque en el campo de texto
        } else {
            buscarInput.setAttribute('hidden', true);
        }
    }

</script>
                </div>
                <div class="form-group search-row">

                    <input type="text" placeholder="Buscar cliente..." class="input-search">
                    <button type="button" class="btn-search"><i class="bi bi-search"></i></button>
                </div>
                <div class="form-group">
                    <i class="bi bi-calendar-event" style="color: #1199d0; padding-left: 10px;"></i><label for="fecha-actual">Fecha Actual</label>
                    <input type="date" id="fecha-actual" name="fecha-actual">
                </div>
                -->
                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i>
                    <input type="text" id="xname" name="xname" placeholder="Nombre" required>
                </div>
                <div class="form-group row">
                    <div class="mitad">
                        <input type="text" id="ap1" name="ap1" placeholder="Primer Apellido" required>
                    </div>
                    <div class="mitad">
                        <input type="text" id="ap2" name="ap2" placeholder="Segundo Apellido">
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-vcard icon" style="color: #1199d0;"></i>
                    <input type="text" id="curp" name="curp" placeholder="CURP del Cliente">
                </div>
                <div class="form-group">
                    <i class="bi bi-person-exclamation icon" style="color: #1199d0;"></i>
                    <input type="text" id="alias" name="alias" placeholder="Alias del Cliente">
                </div>
                <div class="form-group">
                    <i class="bi bi-envelope-at icon" style="color: #1199d0;"></i>
                    <input type="email" id="xmail" name="xmail" placeholder="ejemplo@correo.com" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-telephone-fill icon" style="color: #66fa1c;"></i>
                    <input type="text" id="xphone" name="xphone" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-pin-map-fill icon" style="color: #1199d0;"></i>
                    <textarea id="xaddres" name="xaddres" placeholder=" Direccion"></textarea>
                </div>
                <div class="form-group row">
                    <div class="mitad">
                        <label for="credito-autorizado"><i class="bi bi-currency-dollar" style="color: #1199d0;"></i> Crédito Autorizado</label>
                        <input type="number" id="credito-autorizado" name="credito-autorizado" placeholder="$" required>
                    </div>
                    <div class="mitad">
                        <label for="credito-disponible"><i class="bi bi-credit-card-2-back-fill" style="color: #1199d0;"></i> Crédito Disponible</label>
                        <input type="number" id="credito-disponible" name="credito-disponible" placeholder="$">
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-calendar-check" style="color: #1199d0; padding-left: 10px;"></i><label for="fecha-corte">Fecha de Corte</label>
                    <input type="date" id="fecha-corte" name="fecha-corte" required>
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
include("iCNX.php");
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
    $estatus = 2;
    $fecha_c = trim($_POST['fecha-corte']);
    if (empty($nombre_c) || empty($apellido_1) ||empty($curp) || empty($credito_a) || empty($fecha_c)) {
        echo "<script> Swal.fire({title: 'Campos Vacios! Favor de llenar campos',icon: 'error', draggable: true}); </script>";
    }else{
        try{
            $pdo->beginTransaction();
            $SQLB_cliente = "SELECT COUNT(*) AS total FROM cliente_tb WHERE nombre = :nombre AND curp = :curp";
            $executaB = $pdo->prepare($SQLB_cliente);
            $executaB->bindParam(':nombre',$nombre_c, PDO::PARAM_STR);
            $executaB->bindParam(':curp',$curp, PDO::PARAM_STR);
            $executaB->execute();
            $fila = $executaB->fetch(PDO::FETCH_ASSOC);
            if($fila['total'] > 0){
                echo "<script> Swal.fire({title: 'Datos duplicados!! verifica la informacion',icon: 'warning', draggable: true}); </script>";
            } else{
                $sql_cliente = "INSERT INTO cliente_tb (nombre, apellido_1, apellido_2, curp, alias, correo, telefono, direccion, id_estatus, fecha_reg) VALUES (:nombre, :apellido_1, :apellido_2, :curp, :alias, :correo, :telefono, :direccion, :id_estatus, CURDATE())";
                $stmt_cliente = $pdo->prepare($sql_cliente);
                $stmt_cliente->bindParam(':nombre', $nombre_c);
                $stmt_cliente->bindParam(':apellido_1', $apellido_1);
                $stmt_cliente->bindParam(':apellido_2', $apellido_2);
                $stmt_cliente->bindParam(':curp', $curp);
                $stmt_cliente->bindParam(':alias', $alias);
                $stmt_cliente->bindParam(':correo', $correo_c);
                $stmt_cliente->bindParam(':telefono', $telefono_c);
                $stmt_cliente->bindParam(':direccion', $direccion_c);
                
                //$stmt_cliente->bindParam(':credito_disp', $credito_a);
                
                $stmt_cliente->bindParam(':id_estatus', $estatus);
                $stmt_cliente->execute();
                $idcliente = $pdo->lastInsertId();
                $sql_credito = "INSERT INTO credito_cliente (id_cliente, c_autorizado, c_disponible, fecha_inicio, fecha_corte) VALUES (:id_cliente, :c_autorizado, :c_disponible, CURDATE() , :fecha_corte)";
                $stmt_credito = $pdo->prepare($sql_credito);
                $stmt_credito->bindParam(':id_cliente', $idcliente);
                $stmt_credito->bindParam(':c_autorizado', $credito_a);
                $stmt_credito->bindParam(':c_disponible', $credito_disponible);
                //$stmt_credito->bindParam(':fecha_inicio', $fecha_c);
                $stmt_credito->bindParam(':fecha_corte', $fecha_c);
                $stmt_credito->execute();
                $pdo->commit();
                echo "<script> Swal.fire({title: 'Nuevo Cliente Disponible!',icon: 'success', draggable: true}); </script>";
            }
        } catch (PDOException $e){
            $pdo->rollBack();
            $errormsg = $e->getMessage();
            echo "<script> Swal.fire({title: 'Error al enviar el Registro! . $errormsg.',icon: 'error', draggable: true}); </script>";
        } 

    }
}
?>
