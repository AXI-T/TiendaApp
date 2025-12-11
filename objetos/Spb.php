<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SweetAlert con Input Numérico</title>
    <!-- SweetAlert2 -->
    <script src="js/sweetA.js"></script>
</head>

<body>
    <h2>Ejemplo SweetAlert + Input Numérico</h2>
    <button id="btn-numero">Ingresar número</button>
    <button id="btn2" onclick="llamarsweet()">mostrar sweet</button>

    <p>El número ingresado es: <span id="resultado">-</span></p>

    <script>
        document.getElementById("btn-numero").addEventListener("click", function() {
            alert("esto si jala");
            Swal.fire({
                title: "Ingrese un número",
                input: "number",
                inputAttributes: {
                    min: 0.0,
                    step: 0.5
                },
                showCancelButton: true,
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar",
                inputValidator: (value) => {
                    if (!value) {
                        return "⚠️ Debes ingresar un número";
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar mensaje y actualizar el HTML
                    Swal.fire("Número agregado", `Ingresaste el número: ${result.value}`, "success");
                    document.getElementById("resultado").textContent = result.value; // esto es para agregarlo al carrito
                }
            });
        });

        function llamarsweet() {
            /* otros sweet alert para implementar*/

            //este se muestra a la izquierda
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Your work has been saved",
                showConfirmButton: false,
                timer: 1500
            });

            // este sale en el centro
            Swal.fire({
                title: "Drag me!",
                icon: "success",
                draggable: true
            });
        }

    </script>
</body>

</html>
