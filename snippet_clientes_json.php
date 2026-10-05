<?php
/**
 * SNIPPET para iventas2.php
 * ─────────────────────────────────────────────────────────────────────────────
 * Coloca este bloque <script> DESPUÉS del try/catch de PHP y ANTES de cargar
 * v_filtros.js. Con esto, CLIENTES_DATA está disponible cuando el script arranca.
 *
 * REQUIERE que la consulta de iventas2.php traiga las columnas del JOIN:
 *   id_cliente, Nombre_Cliente, alias, curp, credito_disponible, Limite_credito
 *
 * Si tu consulta actual de $clientes en iventas2.php es la simple sin JOIN,
 * reemplázala por la de filtro_cliente.php (que ya tiene el JOIN con credito_cliente).
 * ─────────────────────────────────────────────────────────────────────────────
 */
?>

<!-- Datos de clientes embebidos como JSON — cero AJAX al buscar -->
<script>
    const CLIENTES_DATA = <?php
        // Normalizar las columnas al formato que espera v_filtros.js
        $clientesJson = array_map(function($c) {
            return [
                'id'      => (int) $c['id_cliente'],
                'nombre'  => trim($c['Nombre_Cliente']  ?? ($c['nombre'] . ' ' . $c['apellido_1'])),
                'alias'   => $c['alias']             ?? '',
                'curp'    => $c['curp']               ?? '',
                'credito' => (float)($c['credito_disponible'] ?? 0),
                'limite'  => (float)($c['Limite_credito']     ?? 0),
            ];
        }, $clientes);
        echo json_encode($clientesJson, JSON_UNESCAPED_UNICODE);
    ?>;
</script>

<?php
/**
 * ─────────────────────────────────────────────────────────────────────────────
 * TAMBIÉN actualiza la consulta $clientes en el try/catch principal de iventas2.php.
 * Reemplaza la línea:
 *
 *     $sqlB = "SELECT * FROM cliente_tb WHERE id_estatus = 2";
 *
 * por esta (trae los datos de crédito en una sola consulta, sin doble llamada):
 *
 *     $sqlB = "SELECT
 *                  c.id_cliente,
 *                  CONCAT(c.nombre,' ',c.apellido_1,' ',c.apellido_2) AS Nombre_Cliente,
 *                  c.curp,
 *                  c.alias,
 *                  COALESCE(cc.c_disponible, 0) AS credito_disponible,
 *                  COALESCE(cc.c_autorizado, 0) AS Limite_credito
 *              FROM cliente_tb c
 *              LEFT JOIN credito_cliente cc ON c.id_cliente = cc.id_cliente
 *              WHERE c.id_estatus = 2
 *              ORDER BY c.nombre ASC";
 *
 * El ORDER BY nombre ASC ayuda al datalist a mostrar resultados en orden alfabético.
 * ─────────────────────────────────────────────────────────────────────────────
 */
?>
