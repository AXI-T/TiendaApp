/**
 * vmodalf.js — Lógica del modal de venta (versión standalone / de referencia)
 *
 * NOTA: Este archivo NO se carga en iventas2.php porque esa lógica
 *       ya está incluida inline (más completa, con mucredito/impcredito).
 *       Se conserva aquí limpio como módulo reutilizable si se necesita
 *       en otra vista.
 *
 * FIXES aplicados:
 *  - APP.totalV  → App.totalV   (nombre unificado del objeto global)
 *  - alert()     → console.log / Swal.fire
 *  - publicoGeneralBtn: ahora también llena los campos ocultos
 */

(function () {
    // Verificar que los elementos existen antes de proceder
    const ventaModal        = document.getElementById('ventaModal');
    const cashBtn           = document.getElementById('cashBtn');
    const creditBtn         = document.getElementById('creditBtn');
    const amountSection     = document.getElementById('amountSection');
    const publicoGeneralBtn = document.getElementById('publicoGeneralBtn');
    const clienteInput      = document.getElementById('cliente');
    const form              = document.getElementById('ventaForm');

    if (!ventaModal || !form) {
        console.warn("vmodalf.js: elementos del modal no encontrados en el DOM.");
        return;
    }

    let selectedMethod = null;
    let totalVenta     = 0;

    // Al abrir el modal
    ventaModal.addEventListener('show.bs.modal', function () {
        // FIX: APP → App
        totalVenta = (typeof App !== 'undefined') ? App.totalV : 0;
        resetModal();
    });

    // Botón Público en General
    publicoGeneralBtn?.addEventListener('click', () => {
        clienteInput.value = 'Publico en General';
        const idField = document.getElementById('cliente_id');
        if (idField) idField.value = '0';
    });

    // Efectivo
    cashBtn?.addEventListener('click', () => {
        setActiveMethod('cash');
        updateAmountSection();
    });

    // Crédito
    creditBtn?.addEventListener('click', () => {
        setActiveMethod('credit');
        updateAmountSection();
    });

    function setActiveMethod(method) {
        selectedMethod = method;
        cashBtn?.classList.remove('active');
        creditBtn?.classList.remove('active');
        (method === 'cash' ? cashBtn : creditBtn)?.classList.add('active');
    }

    function updateAmountSection() {
        if (!selectedMethod || !amountSection) return;

        // FIX: APP → App
        totalVenta = (typeof App !== 'undefined') ? App.totalV : totalVenta;
        amountSection.style.display = 'block';

        if (selectedMethod === 'cash') {
            amountSection.innerHTML = `
                <div class="mb-2">
                    <label class="form-label">Cantidad a pagar:</label>
                    <input type="number" class="form-control" id="cashAmount"
                           step="0.01" min="0" value="${totalVenta.toFixed(2)}" placeholder="0.00">
                </div>
                <div class="alert alert-info mb-0">
                    Total de venta: <strong>$ ${totalVenta.toFixed(2)}</strong>
                </div>`;
        } else {
            amountSection.innerHTML = `
                <div class="alert alert-warning mb-2">
                    Total a crédito: <strong>$ ${totalVenta.toFixed(2)}</strong>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="advanceCheckbox">
                    <label class="form-check-label" for="advanceCheckbox">¿Adelantar pago?</label>
                </div>
                <div id="advanceInputContainer" style="display:none;">
                    <label class="form-label">Monto a adelantar:</label>
                    <input type="number" class="form-control" id="advanceAmount"
                           step="0.01" min="0" max="${totalVenta.toFixed(2)}"
                           value="0.00" placeholder="0.00">
                </div>`;

            document.getElementById('advanceCheckbox').addEventListener('change', function () {
                document.getElementById('advanceInputContainer').style.display =
                    this.checked ? 'block' : 'none';
            });
        }
    }

    function resetModal() {
        const vendedorEl = document.getElementById('vendedor');
        if (vendedorEl) vendedorEl.value = '';
        if (clienteInput) clienteInput.value = '';
        selectedMethod = null;
        cashBtn?.classList.remove('active');
        creditBtn?.classList.remove('active');
        if (amountSection) {
            amountSection.style.display = 'none';
            amountSection.innerHTML     = '';
        }
    }

    // Envío
    form.addEventListener('submit', e => {
        e.preventDefault();

        const vendedor = document.getElementById('vendedor')?.value.trim() || '';
        const cliente  = clienteInput?.value.trim() || '';

        if (!vendedor || !cliente) {
            Swal.fire("Atención", "Por favor seleccione vendedor y cliente.", "warning");
            return;
        }
        if (!selectedMethod) {
            Swal.fire("Atención", "Seleccione un método de pago.", "warning");
            return;
        }

        let pagoInfo = {};

        if (selectedMethod === 'cash') {
            const cashAmount = parseFloat(document.getElementById('cashAmount')?.value) || totalVenta;
            pagoInfo = { metodo: 'Efectivo', cantidad: cashAmount };
        } else {
            const advCheck  = document.getElementById('advanceCheckbox')?.checked || false;
            const advAmount = advCheck
                ? parseFloat(document.getElementById('advanceAmount')?.value || 0)
                : 0;
            pagoInfo = {
                metodo:   'Crédito',
                total:    totalVenta,
                adelanto: advAmount,
                saldo:    totalVenta - advAmount
            };
        }

        console.log('Venta procesada:', { vendedor, cliente, ...pagoInfo });

        Swal.fire("Éxito", "Venta registrada correctamente.", "success").then(() => {
            bootstrap.Modal.getInstance(ventaModal)?.hide();
        });
    });

})();
