<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Personal</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/articulos.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="forms-titulo">Registro Personal</h2>
            <form class="login-form">
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
                    <select id="rol">
                        <option value="">Rol asignado</option>
                        <option value="1">Administrador</option>
                        <option value="2">Colaborador</option>
                    </select>
                </div>
                <div class="form-group">
                    <i class="bi bi-spellcheck icon" style="color: #1199d0;"></i>
                    <input type="text" id="name" placeholder="Nombre" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-person-fill-up icon" style="color: #1199d0;"></i>
                    <input type="text" id="user" placeholder="Usuario" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-envelope-at icon" style="color: #1199d0;"></i>
                    <input type="email" id="email" placeholder="Correo" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-shield-lock icon" style="color: #1199d0;"></i>
                    <div class="form-objetos password-container">
                        <input type="password" id="confirm-password" placeholder="Password" required>
                        <span class="toggle-passwordconfirm"><i id="cicoeye" class="bi bi-eye-fill"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-shield-check icon" style="color: #1199d0;"></i>
                    <div class="form-objetos password-container">
                        <input type="password" id="password" placeholder="Password confirm" required>
                        <span class="toggle-password"><i id="icoeye" class="bi bi-eye-fill"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <i class="bi bi-telephone-fill icon" style="color: #66fa1c;"></i>
                    <input type="text" id="phone" placeholder="Telefono" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <div class="form-group">
                    <i class="bi bi-coin icon" style="color: #f4d03f;"></i>
                    <input type="text" id="salary" placeholder="Salario" oninput="this.value = this.value.replace(/[^0-9]/,'')" required>
                </div>
                <!--<label for="password">Password</label>-->
                <button type="submit" class="login-button">Enviar</button>
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

        // Cambiar el ícono
        //this.textContent = type === 'password' ? '👁️' : '🙈';
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
