// query para la funcion de cliente en la pesta;a de pago
$(document).ready(function () {
    // Variables para almacenar los datos del cliente seleccionado
    let clienteSeleccionado = {
        id: null,
        nombre: null,
        credito: null
    };

    // Función para extraer datos de la opción seleccionada
    function obtenerDatosClienteSeleccionado(valorSeleccionado) {
        // Buscar la opción dentro del datalist cuyo value coincida
        const $option = $(`#clientes option[value="${valorSeleccionado}"]`);

        if ($option.length) {
            return {
                id: $option.data('id'), //1
                nombre: $option.val(), //Publico en General
                credito: $option.data('credito') //0
            };
        }
        return null;
    }

    // Método 1: Usar el evento 'change' (se dispara cuando el input pierde foco)
    $("#cliente").on('change', function () {
        const valor = $(this).val();
        const datosCliente = obtenerDatosClienteSeleccionado(valor);

        if (datosCliente) {
            // Guardar en variables
            clienteSeleccionado = datosCliente;

            console.log('Cliente seleccionado:', clienteSeleccionado);
            console.log('ID:', clienteSeleccionado.id);
            console.log('Crédito:', clienteSeleccionado.credito);

            // Mostrar crédito en un label si lo tienes
            if (clienteSeleccionado.credito) {
                $('#creditoLabel').text(`$${parseFloat(clienteSeleccionado.credito).toFixed(2)}`);
            }

            // Guardar en campos ocultos si los tienes
            $('#cliente_id').val(clienteSeleccionado.id);
            $('#cliente_credito').val(clienteSeleccionado.credito);

        } else {
            // No se seleccionó un cliente válido
            clienteSeleccionado = {
                id: null,
                nombre: null,
                credito: null
            };
            $('#cliente_id').val('');
            $('#cliente_credito').val('');
            $('#creditoLabel').text('$0.00');
        }
    });

    // Método 2: Capturar cuando se hace clic en la lista (más inmediato)
    // Nota: El evento 'click' en options de datalist no es directamente accesible
    // Alternativa: usar 'input' con setTimeout para capturar selección
    let timeout;
    $("#cliente").on('input', function () {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const valor = $(this).val();
            const datosCliente = obtenerDatosClienteSeleccionado(valor);

            if (datosCliente) {
                clienteSeleccionado = datosCliente;
                console.log('Selección detectada (input):', clienteSeleccionado);

                // Actualizar campos
                $('#cliente_id').val(clienteSeleccionado.id);
                $('#cliente_credito').val(clienteSeleccionado.credito);
                if (clienteSeleccionado.credito) {
                    $('#creditoLabel').text(`$${parseFloat(clienteSeleccionado.credito).toFixed(2)}`);
                    alert("este es el credito: " + clienteSeleccionado.credito);
                }
            }
        }, 100);
    });

    // Método 3: Botón "Público General"
    $("#publicoGeneralBtn").on('click', function () {
        // Buscar el cliente "Público en General" en el datalist
        const $option = $(`#clientes option[value="Público en General"]`);

        if ($option.length) {
            clienteSeleccionado = {
                id: $option.data('id'),
                nombre: 'Público en General',
                credito: $option.data('credito')
            };

            $("#cliente").val('Público en General');
            $('#cliente_id').val(clienteSeleccionado.id);
            $('#cliente_credito').val(clienteSeleccionado.credito);

            if (clienteSeleccionado.credito) {
                $('#creditoLabel').text(`$${parseFloat(clienteSeleccionado.credito).toFixed(2)}`);
            }

            console.log('Público General seleccionado:', clienteSeleccionado);
        }
    });

    // Función para obtener los datos actuales (útil cuando necesites usar las variables)
    window.obtenerClienteSeleccionado = function () {
        return clienteSeleccionado;
    };
});

// Función para actualizar la interfaz con los datos del cliente
function actualizarInterfazCliente(datosCliente) {
    if (datosCliente && datosCliente.id) {
        // Actualizar campos ocultos
        $('#cliente_id').val(datosCliente.id);
        $('#cliente_credito').val(datosCliente.credito);

        // Actualizar label de crédito
        if (datosCliente.credito) {
            $('#creditoLabel').text(`$${parseFloat(datosCliente.credito).toFixed(2)}`);

            // Cambiar color según el crédito
            if (datosCliente.credito > 1000) {
                $('#creditoLabel').removeClass('text-danger').addClass('text-success');
            } else if (datosCliente.credito > 0) {
                $('#creditoLabel').removeClass('text-danger text-success').addClass('text-warning');
            } else {
                $('#creditoLabel').removeClass('text-success text-warning').addClass('text-danger');
            }
        }

        // Mostrar resumen
        $('#clienteNombre').text(datosCliente.nombre);
        $('#clienteCredito').text(`Crédito: $${parseFloat(datosCliente.credito).toFixed(2)}`);
        $('#resumenCliente').show();
    } else {
        // Limpiar si no hay cliente válido
        $('#cliente_id').val('');
        $('#cliente_credito').val('');
        $('#creditoLabel').text('$0.00');
        $('#resumenCliente').hide();
    }
}

// Modificar los eventos para usar la función de actualización
$("#cliente").on('change', function () {
    const valor = $(this).val();
    const datosCliente = obtenerDatosClienteSeleccionado(valor);

    if (datosCliente) {
        clienteSeleccionado = datosCliente;
        actualizarInterfazCliente(clienteSeleccionado);
        console.log('Cliente seleccionado:', clienteSeleccionado);
    } else {
        clienteSeleccionado = {
            id: null,
            nombre: null,
            credito: null
        };
        actualizarInterfazCliente(null);
    }
});
