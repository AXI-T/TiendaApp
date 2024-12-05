<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/icon/bootstrap-icons-1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <img src="logo.png" alt="Logo" class="logo-img">
            </div>
            <form class="login-form">
                <!--<label for="email">Email</label>-->
                <input type="text" id="user" placeholder="Usuario" required>

                <!--<label for="password">Password</label>-->
                <div class="password-container">
                    <input type="password" id="password" placeholder="Password" required>
                    <span class="toggle-password"><i id="icoeye" class="bi bi-eye-fill"></i></span>
                </div>

                <button type="submit" class="login-button">Iniciar</button>
            </form>
            <p class="signup-text">No cuentas con Usuario? <a href="#">Registrar</a></p>
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

</script>

</html>
