<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Cliente</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/objetos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box" style="width: 500px;">
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
                <div class="form-group">
                    <i class="bi bi-envelope-at icon" style="color: #1199d0;"></i>
                    <input type="email" id="xmail" name="xmail" placeholder="example@correo.com" required>
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
            <p class="signup-text">Consultar o editar informacion <a href="#">Registrar Cliente</a></p>
        </div>
    </div>
</body>

</html>
<?php
include("iCNX.php");
if(isset($_POST['Xenviar'])){
    $nombre_c = trim($_POST['xname']);
    $correo_c = trim($_POST['xmail']);
    $direccion_c = trim($_POST['xaddres']);
    $telefono_c = trim($_POST['xphone']);
    $credito_a = trim($_POST['credito-autorizado']);
    $estatus = trim(2);
    $monto_c = trim($_POST['credito-disponible']);
    $fecha_c = trim($_POST['fecha-corte']);
    $fecha_c = trim($_POST['fecha-corte']);
    if (empty($nombre_c) || empty($credito_a) || empty($fecha_c)) {
        die("Favor de llenar los campos importantes del cliente.");
    }else{
        try {
            $pdo->beginTransaction();
            $sql_cliente = "INSERT INTO cliente_tb (nombre, correo, telefono, direccion, credito_disp, id_estatus, fecha_reg) VALUES (:nombre, :correo, :telefono, :direccion, :credito_disp, :id_estatus, CURDATE())";
            $stmt_cliente = $pdo->prepare($sql_cliente);
            $stmt_cliente->bindParam(':nombre', $nombre_c);
            $stmt_cliente->bindParam(':correo', $correo_c);
            $stmt_cliente->bindParam(':telefono', $telefono_c);
            $stmt_cliente->bindParam(':direccion', $direccion_c);
            $stmt_cliente->bindParam(':credito_disp', $credito_a);
            $stmt_cliente->bindParam(':id_estatus', $estatus);
            //$stmt_cliente->bindParam(':fecha_reg', $fecha_c);
            $stmt_cliente->execute();
            $idcliente = $pdo->lastInsertId();
            $sql_credito = "INSERT INTO credito_cliente (id_cliente, monto, fecha_inicio, fecha_final) VALUES (:id_cliente, :monto, CURDATE() , :fecha_final)";
            $stmt_credito = $pdo->prepare($sql_credito);
            $stmt_credito->bindParam(':id_cliente', $idcliente);
            $stmt_credito->bindParam(':monto', $credito_a);
            //$stmt_credito->bindParam(':fecha_inicio', $monto);
            $stmt_credito->bindParam(':fecha_final', $fecha_c);
            $stmt_credito->execute();
            $pdo->commit();
            //echo "Cliente y crédito registrados correctamente. ID del cliente: $idcliente";
            echo "<script> alert('Registro enviado correctamente'); </script>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<script> alert('Error al enviar el Registro . $e->getMessage()'); </script>";
        }
    }
    //echo $_POST['fecha-corte'];
    //REALIZAR LA INSERCION Y DESPUES LA CONSULTA PARA HACER LA INSERCION EL LA TABLA DE CREDITOS
echo $nombre_c;
echo $correo_c;
echo $direccion_c;
echo $telefono_c;
echo $credito_a;
echo $monto_c;
echo $fecha_c;
}
?>
