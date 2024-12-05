document.getElementById("mostrarTabla").addEventListener("click", function (event) {
    event.preventDefault();

    /* Obtener valores del formulario
    const nombre = document.getElementById("nombre").value;
    const email = document.getElementById("email").value;
    const telefono = document.getElementById("telefono").value;

     Transferir datos a la tabla
    document.getElementById("tablaNombre").innerText = nombre;
    document.getElementById("tablaEmail").innerText = email;
    document.getElementById("tablaTelefono").innerText = telefono;*/

    // Ajustar el ancho de los contenedores
    document.getElementById("formulario").style.width = "40%";
    document.getElementById("tablaClientes").style.width = "60%";
    document.getElementById("tablaClientes").style.display = "block";
});

document.getElementById("ocultarTabla").addEventListener("click", function (event) {
    event.preventDefault();

    // Restaurar el ancho de los contenedores
    document.getElementById("formulario").style.width = "50%";
    document.getElementById("tablaClientes").style.width = "50%";
    document.getElementById("tablaClientes").style.display = "none";
});
