<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Personal</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="js/sweetA.js"></script>
</head>
<?php
                    
    include("iCNX.php");
    try {
        $query1 = "SELECT *FROM roles_tb";
        $stmt1 = $pdo->query($query1);
        $roles = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
        exit;
    }
                     
?>

<body>
    <?php include_once("navegacion.php"); ?>
    <div class="login-container">
        <div class="login-box" style="width: 500px;">
            <h2 class="forms-titulo">Registro Personal</h2>
            <form id="form1" name="form1" class="login-form" method="post" enctype="multipart/form-data">
                <!--<label for="email">Email</label>
                <div class="form-row">
                    <div class="input-group">

                    </div>
                    <div class="input-group">
                        <input type="text" id="user" placeholder="Usuario" required>
                    </div>
                    <div class="input-group">
                        <div class="password-container">
                            <input type="password" id="password" placeholder="Password" required>
                            <span class="toggle-password"><i id="icoeye" class="bi bi-eye-fill"></i></span>
                        </div>
                    </div>

                </div> 
                <div class="form-group">
                    <i class="bi bi-image icon" style="color: #1199d0;"></i>
                     Icono de usuario 
                    <input type="file" id="imagen-perfil" name="imagen-perfil" accept="image/*" required>
                </div>-->
                <div class="form-group">
                    <i class="bi bi-person-fill-gear icon" style="color: #1199d0;"></i>
                    <!-- Icono de usuario 
                    <input type="text" id="phone" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>-->
                    <select id="rols" name="rols">
                        <option value="0">Rol asignado</option>
                        <?php foreach ($roles as $rols): ?>
                        <option value="<?= $rols['id'] ?>"><?= htmlspecialchars($rols['rol']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i>
                    <input type="text" id="nombre" name="nombre" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-fill-up icon" style="color: #1199d0;"></i>
                    <input type="text" id="usuario" name="usuario" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-envelope-at icon" style="color: #1199d0;"></i>
                    <input type="email" id="correo" name="correo" placeholder="Correo" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-shield-lock icon" style="color: #1199d0;"></i>
                    <div class="form-objetos password-container">
                        <input type="password" id="password" name="password" placeholder="Contraseña" required>
                        <span class="toggle-password"><i id="icoeye" class="bi bi-eye-fill"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-shield-check icon" style="color: #1199d0;"></i>
                    <div class="form-objetos password-container">
                        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirmar Contraseña " required>
                        <span class="toggle-passwordconfirm"><i id="cicoeye" class="bi bi-eye-fill"></i></span>
                    </div>
                </div>

                <div class="form-group">
                    <i class="bi bi-telephone-fill icon" style="color: #66fa1c;"></i>
                    <input type="text" id="telefono" name="telefono" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-coin icon" style="color: #f4d03f;"></i>
                    <input type="text" id="salario" name="salario" placeholder="Salario" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <button type="submit" id="Xenviar" name="Xenviar" class="login-button">Enviar</button>
            </form>
            <p class="signup-text">Ya tienes Usuario? <a href="#">Iniciar Sesion</a></p>
        </div>
    </div>
</body>
<script>
    document.querySelector('.toggle-password').addEventListener('click', function() {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        const icono = document.getElementById('icoeye');
        const clase = icono.getAttribute('class') === 'bi bi-eye-fill' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        icono.setAttribute('class', clase);
        passwordField.setAttribute('type', type);
    });
    document.querySelector('.toggle-passwordconfirm').addEventListener('click', function() {
        const passwordField = document.getElementById('confirm-password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        const icono = document.getElementById('cicoeye');
        const clase = icono.getAttribute('class') === 'bi bi-eye-fill' ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        icono.setAttribute('class', clase);
        passwordField.setAttribute('type', type);
    });

</script>

</html>
<?php
if(isset($_POST['Xenviar'])){
    $rol = trim($_POST['rols']);
    $nombre = trim($_POST['nombre']);
    $usuario = trim($_POST['usuario']);
    $correo = trim($_POST['correo']);
    $clave = trim($_POST['password']);
    $c_clave = trim($_POST['confirm-password']);
    $telefono = trim($_POST['telefono']);
    $salario = trim($_POST['salario']);
    if ($rol==null || $rol == 0){
        echo "<script> Swal.fire({title: 'Elige un Rol!', icon: 'error', draggable: true}); </script>";
    }else{
        if ( empty($nombre) || empty($usuario) || empty($correo) || empty($clave) || empty($c_clave) ||empty($salario)) {
            die("Todos los campos son obligatorios.");
        }else{
            // comparar la igualdad de las contrasenas para seguir
            if($clave === $c_clave){
                try {
                    $sqlB1 = "SELECT COUNT(*) AS total FROM usuarios_tb WHERE nombre = :nombre AND usuario = :usuario";
                    $stmt = $pdo->prepare($sqlB1);
                    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    // Validar la existencia del registro
                    if ($row['total'] > 0) {
                        echo "<script> Swal.fire({title: 'Usuarior Existente...', icon: 'warning', draggable: true}); </script>";
                    } else {
                        $sqlIst = "INSERT INTO usuarios_tb (usuario, clave, nombre, correo, telefono, id_rol, salario) VALUES (:usuario, :clave, :nombre, :correo, :telefono, :rol, :salario)";
                        $stmt = $pdo->prepare($sqlIst);
                        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
                        $stmt->bindParam(':clave', $clave, PDO::PARAM_STR);
                        $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                        $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
                        $stmt->bindParam(':telefono', $telefono, PDO::PARAM_INT);
                        $stmt->bindParam(':rol', $rol, PDO::PARAM_INT);
                        $stmt->bindParam(':salario', $salario, PDO::PARAM_STR);
                        if ($stmt->execute()) {
                            echo "<script> Swal.fire({ title: 'Registro Exitoso!', icon: 'success', draggable: true }); </script>";
                        } else {
                            echo "<script> Swal.fire({ title: 'Error al enviar el Registro!', icon: 'error', draggable: true }); </script>";
                        }
                    }
                } catch (PDOException $e) {
                    
                    echo "<script> Swal.fire({title: 'Error en la consulta: . $e->getMessage()',icon: 'error', draggable: true}); </script>" ;
                }
                $pdo = null;
            }else{
                echo "<script> Swal.fire({ title: 'Las contraseñas no coinciden!', icon: 'error', draggable: true }); </script>";
            }
        }
    }

}
?>
