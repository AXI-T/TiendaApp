document.addEventListener("DOMContentLoaded", function () {
            // Base de datos de productos desde PHP
            const productosDB = < ? php echo json_encode(array_column($productos, null, 'id_articulo')); ? > ;

            // Delegación de eventos para el input de búsqueda rápida
            document.getElementById('entrada-rapida')?.addEventListener('keyup', function (e) {
                if (e.key === 'Enter') {
                    procesarEntradaRapida(this.value.trim());
                    this.value = '';
                }
            });

            // Delegación de eventos para los botones de "Agregar"
            document.querySelector(".product-container").addEventListener("click", function (event) {
                if (event.target.classList.contains("add-button")) {
                    agregarProductoDesdeCard(event.target);
                }
            });

            // Función para procesar entrada rápida
            function procesarEntradaRapida(valor) {
                if (!valor) return;

                // Patrones aceptados: cantidad*código o solo código
                const patron = /^(\d+(?:\.\d+)?)?\*?([A-Za-z0-9]+)$/;
                const match = valor.match(patron);

                if (match) {
                    const cantidad = match[1] ? parseFloat(match[1]) : 1;
                    const codigoProducto = match[2];

                    // Buscar producto por código (ajusta según tu estructura)
                    const producto = Object.values(productosDB).find(p =>
                        p.codigo === codigoProducto || p.id_articulo == codigoProducto
                    );

                    if (producto) {
                        if (producto.unidad === 'pza' || !isNaN(cantidad)) {
                            agregarAlCarrito({
                                id: producto.id_articulo,
                                nombre: producto.nombre,
                                precio: producto.precio,
                                cantidad: cantidad,
                                unidad: producto.unidad
                            });
                        } else {
                            solicitarCantidad(producto, cantidad);
                        }
                    } else {
                        Swal.fire('Error', `Producto "${codigoProducto}" no encontrado`, 'error');
                    }
                }
            }

            // Función unificada para agregar desde card
            function agregarProductoDesdeCard(boton) {
                const card = boton.closest(".product-card");
                const producto = {
                    id: parseInt(card.querySelector(".oculto").textContent.trim()),
                    nombre: card.querySelector(".product-name").textContent.trim(),
                    precio: parseFloat(card.querySelector(".product-price").textContent.replace("$", "").trim()),
                    unidad: card.querySelector(".product-unit").textContent.trim(),
                    cantidad: 1
                };

                if (producto.unidad === "pza" || producto.unidad === "unidad") {
                    agregarAlCarrito(producto);
                } else {
                    solicitarCantidad(producto);
                }
            }

            // Función para solicitar cantidad (reutilizable)
            function solicitarCantidad(producto, cantidadInicial = 1) {
                Swal.fire({
                    title: `Ingrese la cantidad (${producto.unidad})`,
                    input: "number",
                    inputValue: cantidadInicial,
                    inputAttributes: {
                        min: 0.01,
                        step: producto.unidad === 'kg' ? 0.1 : 0.01,
                        placeholder: `Cantidad en ${producto.unidad}`
                    },
                    showCancelButton: true,
                    confirmButtonText: "Agregar",
                    cancelButtonText: "Cancelar",
                    inputValidator: (value) => {
                        if (!value || parseFloat(value) <= 0) {
                            return "Ingrese una cantidad válida";
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const cantidad = parseFloat(result.value);
                        producto.cantidad = cantidad;
                        producto.precioTotal = producto.precio * cantidad;
                        agregarAlCarrito(producto);
                    }
                });
            }

            // Función optimizada para agregar al carrito
            function agregarAlCarrito(producto) {
                const filas = document.querySelectorAll("#carrito-body tr");
                const productoExistente = Array.from(filas).find(fila =>
                    fila.querySelector(".cod-producto")?.textContent.trim() == producto.id
                );

                if (productoExistente) {
                    // Actualizar producto existente
                    const cantidadElemento = productoExistente.querySelector(".cantidad-producto");
                    const precioElemento = productoExistente.querySelector(".precio-producto");

                    const cantidadActual = parseFloat(cantidadElemento.textContent);
                    const cantidadNueva = cantidadActual + producto.cantidad;
                    const precioUnitario = parseFloat(precioElemento.textContent.replace("$", "")) / cantidadActual;

                    cantidadElemento.textContent = cantidadNueva.toFixed(3);
                    precioElemento.textContent = "$ " + (precioUnitario * cantidadNueva).toFixed(2);
                } else {
                    // Agregar nuevo producto
                    const nuevaFila = document.createElement("tr");
                    const precioTotal = producto.precioTotal || (producto.precio * producto.cantidad);

                    nuevaFila.innerHTML = ' <
                        td class = "cod-producto"
                    style = "display:none" > $ {
                            producto.id
                        } < /td> <
                        td class = "nombre-producto" >
                        $ {
                            producto.nombre
                        }
                    $ {
                        producto.unidad && producto.unidad !== 'pza' ?
                            <
                            small class = "text-muted" > ($ {
                                producto.unidad
                            }) < /small>` : ''} <
                            /td> <
                            td class = "cantidad-producto" > $ {
                                producto.cantidad.toFixed(3)
                            } < /td> <
                            td class = "precio-unitario"
                        style = "display:none" > $ {
                                producto.precio.toFixed(2)
                            } < /td> <
                            td class = "precio-producto" > $ $ {
                                precioTotal.toFixed(2)
                            } < /td> <
                            td >
                            <
                            button class = "btn btn-sm btn-outline-danger eliminar-producto" >
                            <
                            i class = "bi bi-trash" > < /i> <
                            /button> <
                            button class = "btn btn-sm btn-outline-warning ms-1 editar-cantidad" >
                            <
                            i class = "bi bi-pencil" > < /i> <
                            /button> <
                            /td>
                        ';

                        document.querySelector("#carrito-body").appendChild(nuevaFila);

                        // Agregar eventos a los botones
                        nuevaFila.querySelector(".eliminar-producto").addEventListener("click", function () {
                            nuevaFila.remove();
                            actualizarTotal();
                        });

                        nuevaFila.querySelector(".editar-cantidad").addEventListener("click", function () {
                            editarCantidadProducto(nuevaFila, producto);
                        });
                    }

                    actualizarTotal();

                    // Feedback visual
                    Swal.fire({
                        icon: 'success',
                        title: 'Producto agregado',
                        text: `${producto.cantidad} ${producto.unidad || 'unidad'} de ${producto.nombre}`,
                        timer: 1500,
                        showConfirmButton: false
                    });
                }

                // Función para editar cantidad desde el carrito
                function editarCantidadProducto(fila, productoOriginal) {
                    const cantidadActual = parseFloat(fila.querySelector(".cantidad-producto").textContent);
                    const precioUnitario = parseFloat(fila.querySelector(".precio-unitario")?.textContent || productoOriginal.precio);

                    Swal.fire({
                        title: `Editar cantidad de ${productoOriginal.nombre}`,
                        input: "number",
                        inputValue: cantidadActual,
                        inputAttributes: {
                            min: 0.01,
                            step: productoOriginal.unidad === 'kg' ? 0.1 : 0.01,
                        },
                        showCancelButton: true,
                        confirmButtonText: "Actualizar",
                        cancelButtonText: "Cancelar",
                        inputValidator: (value) => {
                            if (!value || parseFloat(value) <= 0) {
                                return "Ingrese una cantidad válida";
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const nuevaCantidad = parseFloat(result.value);
                            fila.querySelector(".cantidad-producto").textContent = nuevaCantidad.toFixed(3);
                            fila.querySelector(".precio-producto").textContent = "$ " + (precioUnitario * nuevaCantidad).toFixed(2);
                            actualizarTotal();
                        }
                    });
                }

                // Función optimizada para actualizar el total
                function actualizarTotal() {
                    let total = 0;
                    const filas = document.querySelectorAll("#carrito-body tr");

                    filas.forEach(fila => {
                        const precioTexto = fila.querySelector(".precio-producto").textContent.replace("$", "").trim();
                        total += parseFloat(precioTexto);
                    });

                    document.querySelector("#total-precio").innerHTML = `
            <strong>Total:</strong> 
            <span class="text-success">$ ${total.toFixed(2)}</span>
        `;

                    // Actualizar contador de productos
                    const totalItems = Array.from(filas).reduce((sum, fila) => {
                        return sum + parseFloat(fila.querySelector(".cantidad-producto").textContent);
                    }, 0);

                    document.querySelector("#contador-carrito")?.textContent =
                        `Productos: ${totalItems.toFixed(2)}`;
                }
            });
