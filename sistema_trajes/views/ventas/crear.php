<?php
$errores = isset($errores) ? $errores : [];
$old = isset($old) ? $old : [
    'id_cliente'    => '',
    'observaciones' => '',
    'items'         => []
];
?>

<div class="form-card">
    <div class="section-title">
        <div>
            <h2>Registrar nueva venta</h2>
            <p class="card-subtitle">Selecciona el cliente y agrega los trajes vendidos.</p>
        </div>
        <a href="index.php?c=ventas&a=index" class="btn btn-secondary">↩ Volver</a>
    </div>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-error">
            <strong>Revisa los siguientes puntos:</strong>
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="index.php?c=ventas&a=guardar" method="POST" id="formVenta">
        <div class="form-grid">
            <div class="form-group">
                <label>Cliente *</label>
                <select name="id_cliente" required>
                    <option value="">-- Seleccione un cliente --</option>
                    <?php foreach ($clientes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $old['id_cliente'] == $c['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nombre']) ?> (<?= htmlspecialchars($c['ci_nit']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea name="observaciones" rows="2" placeholder="Ej: Venta al contado, sin descuentos..."><?= htmlspecialchars($old['observaciones']) ?></textarea>
            </div>
        </div>

        <div class="form-actions" style="justify-content: flex-end;">
            <button type="button" class="btn btn-secondary" onclick="guardarCarrito()">💾 Guardar carrito temporal</button>
        </div>

        <h3>Detalle de la venta</h3>
        <p class="card-subtitle">Agrega uno o varios trajes. Puedes ajustar cantidades y precios.</p>

        <div class="table-wrapper">
            <table id="tablaDetalle">
                <thead>
                    <tr>
                        <th>Traje</th>
                        <th>Cantidad</th>
                        <th>Precio unitario (Bs.)</th>
                        <th>Subtotal (Bs.)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="agregarFila()">➕ Agregar traje</button>
        </div>

        <div class="panel" style="margin-top: 12px;">
            <strong>Total: <span id="totalTexto">0.00</span> Bs.</strong>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-success">💾 Guardar venta</button>
            <a href="index.php?c=ventas&a=index" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
const KEY_CARRITO = 'carritoVenta';
const trajesData = <?= json_encode($trajes) ?>;
const itemsServer = <?= json_encode($old['items']) ?>;
let tbody;
let totalTexto;

function crearSelectTraje(index) {
    let html = '<select name="id_traje[' + index + ']" required>';
    html += '<option value="">-- Seleccione --</option>';
    trajesData.forEach(t => {
        const label = `${t.codigo} - ${t.nombre} (Stock: ${t.stock})`;
        html += `<option value="${t.id}" data-precio="${t.precio_venta}">${label}</option>`;
    });
    html += '</select>';
    return html;
}

function agregarFila(datos = null) {
    const index = tbody.children.length;
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>${crearSelectTraje(index)}</td>
        <td><input type="number" name="cantidad[${index}]" min="1" value="${datos ? datos.cantidad : 1}" oninput="recalcularFila(this)"></td>
        <td><input type="number" step="0.01" min="0" name="precio_unitario[${index}]" value="${datos ? datos.precio_unitario : 0}" oninput="recalcularFila(this)"></td>
        <td class="text-right"><span class="subtotal">0.00</span></td>
        <td><button type="button" class="btn btn-danger btn-small" onclick="eliminarFila(this)">🗑</button></td>
    `;

    tbody.appendChild(tr);

    const select = tr.querySelector('select');
    select.addEventListener('change', function () {
        const option = this.options[this.selectedIndex];
        const precio = option.getAttribute('data-precio') || 0;
        const precioInput = tr.querySelector('input[name^="precio_unitario"]');
        precioInput.value = parseFloat(precio).toFixed(2);
        recalcularFila(precioInput);
    });

    if (datos) {
        select.value = datos.id_traje;
        recalcularFila(tr.querySelector('input[name^="cantidad"]'));
    } else if (select.options[1]) {
        select.selectedIndex = 0;
    }
}

function eliminarFila(btn) {
    const row = btn.closest('tr');
    row.remove();
    recalcularTotal();
}

function recalcularFila(input) {
    const row = input.closest('tr');
    const cant = parseFloat(row.querySelector('input[name^="cantidad"]').value) || 0;
    const precio = parseFloat(row.querySelector('input[name^="precio_unitario"]').value) || 0;
    const subtotal = cant * precio;
    row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
    recalcularTotal();
}

function recalcularTotal() {
    let total = 0;
    tbody.querySelectorAll('.subtotal').forEach(span => {
        total += parseFloat(span.textContent) || 0;
    });
    totalTexto.textContent = total.toFixed(2);
}

function guardarCarrito() {
    const datos = [];
    tbody.querySelectorAll('tr').forEach(row => {
        const select = row.querySelector('select');
        const cantidad = row.querySelector('input[name^="cantidad"]').value;
        const precio = row.querySelector('input[name^="precio_unitario"]').value;

        if (select.value && cantidad > 0 && precio > 0) {
            datos.push({
                id_traje: select.value,
                cantidad: cantidad,
                precio_unitario: precio
            });
        }
    });
    localStorage.setItem(KEY_CARRITO, JSON.stringify(datos));
    alert('Carrito guardado en este equipo.');
}

function cargarDesdeGuardado() {
    const guardado = localStorage.getItem(KEY_CARRITO);
    if (!guardado) {
        agregarFila();
        return;
    }
    JSON.parse(guardado).forEach(item => agregarFila(item));
}

document.addEventListener('DOMContentLoaded', () => {
    tbody = document.querySelector('#tablaDetalle tbody');
    totalTexto = document.getElementById('totalTexto');
    const formVenta = document.getElementById('formVenta');

    if (!tbody || !totalTexto) {
        return;
    }

    if (itemsServer.length) {
        itemsServer.forEach(item => agregarFila(item));
    } else {
        cargarDesdeGuardado();
    }

    tbody.querySelectorAll('input').forEach(input => recalcularFila(input));
    formVenta.addEventListener('submit', () => localStorage.removeItem(KEY_CARRITO));
});
</script>
