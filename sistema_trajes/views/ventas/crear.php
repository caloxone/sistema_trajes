<script>
const KEY_CARRITO = "carritoVenta";

// cargar carrito si existe
window.onload = function() {
    const guardado = localStorage.getItem(KEY_CARRITO);
    if (guardado) {
        const items = JSON.parse(guardado);
        items.forEach(item => agregarFilaDesdeCarrito(item));
    }
};

function agregarFilaDesdeCarrito(item) {
    agregarFila(); // agrega fila vacía
    const fila = tbody.lastElementChild;
    fila.querySelector('select').value = item.id_traje;
    fila.querySelector('input[name^="cantidad"]').value = item.cantidad;
    fila.querySelector('input[name^="precio_unitario"]').value = item.precio_unitario;
    recalcularFila(fila.querySelector('input[name^="cantidad"]'));
}

function guardarCarrito() {
    const datos = [];
    const filas = tbody.querySelectorAll('tr');

    filas.forEach(f => {
        const id = f.querySelector('select').value;
        const cantidad = f.querySelector('input[name^="cantidad"]').value;
        const precio = f.querySelector('input[name^="precio_unitario"]').value;

        if (id !== "" && cantidad > 0 && precio > 0) {
            datos.push({
                id_traje: id,
                cantidad: cantidad,
                precio_unitario: precio
            });
        }
    });

    localStorage.setItem(KEY_CARRITO, JSON.stringify(datos));
}
</script>

<style>
    .form-card {
        max-width: 1100px;
        margin: 20px auto;
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 24px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }
    .form-card h2 { margin:0 0 6px; font-size:22px; }
    .form-card p { margin:0 0 14px; color:#6b7280; font-size:14px; }
    .form-group { margin-bottom:12px; }
    .form-group label { display:block; font-size:14px; font-weight:600; margin-bottom:4px; }
    .form-group select, .form-group textarea, .form-group input {
        padding:8px 10px; border-radius:8px; border:1px solid #d1d5db; width:100%;
        font-size:14px; box-sizing:border-box;
    }
    .btn {
        display:inline-block; padding:8px 14px; border-radius:8px;
        border:none; text-decoration:none; font-size:14px; cursor:pointer;
    }
    .btn-primary { background:#22c55e; color:#fff; }
    .btn-primary:hover { background:#16a34a; }
    .btn-secondary { background:#e5e7eb; color:#374151; }
    .btn-secondary:hover { background:#d1d5db; }
    .btn-small { padding:4px 8px; font-size:12px; border-radius:6px; }
    .btn-danger { background:#ef4444; color:#fff; }
    .btn-danger:hover { background:#dc2626; }

    .table-wrapper { margin-top:12px; overflow-x:auto; }
    table.detalle {
        width:100%; border-collapse:collapse; font-size:14px;
    }
    table.detalle th, table.detalle td {
        padding:8px 6px; border-bottom:1px solid #e5e7eb; white-space:nowrap;
    }
    table.detalle thead { background:#f3f4f6; }
    .text-right { text-align:right; }
    .mt-2 { margin-top:8px; }
    .mt-3 { margin-top:16px; }
    .total-box {
        margin-top:10px; text-align:right; font-size:16px; font-weight:bold;
    }
</style>

<div class="form-card">
    <h2>Registrar nueva venta</h2>
    <p>Selecciona el cliente y agrega los trajes vendidos. El total se calculará automáticamente.</p>

   <button type="button" class="btn btn-secondary" onclick="guardarCarrito()">
    💾 Guardar carrito temporal
</button>

    <form action="index.php?c=ventas&a=guardar" method="POST" id="formVenta">

        <div class="form-group">
            <label>Cliente *</label>
            <select name="id_cliente" required>
                <option value="">-- Seleccione un cliente --</option>
                <?php foreach ($clientes as $c): ?>
                    <option value="<?= $c['id'] ?>">
                        <?= htmlspecialchars($c['nombre']) ?> (<?= htmlspecialchars($c['ci_nit']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Observaciones</label>
            <textarea name="observaciones" rows="2" placeholder="Ej: Venta al contado, sin descuentos..."></textarea>
        </div>

        <h3>Detalle de la venta</h3>
        <p>Agrega uno o varios trajes. Puedes ajustar cantidades y precios.</p>

        <div class="table-wrapper">
            <table class="detalle" id="tablaDetalle">
                <thead>
                    <tr>
                        <th>Traje</th>
                        <th>Cantidad</th>
                        <th>Precio unitario (Bs.)</th>
                        <th>Subtotal (Bs.)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas se agregarán dinámicamente con JS -->
                </tbody>
            </table>
        </div>

        <button type="button" class="btn btn-secondary mt-2" onclick="agregarFila()">➕ Agregar traje</button>

        <div class="total-box">
            Total: <span id="totalTexto">0.00</span> Bs.
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">💾 Guardar venta</button>
            <a href="index.php?c=ventas&a=index" class="btn btn-secondary">↩ Cancelar</a>
        </div>
    </form>
</div>

<script>
    const trajesData = <?= json_encode($trajes) ?>;
    const tbody = document.querySelector('#tablaDetalle tbody');
    const totalTexto = document.getElementById('totalTexto');

    function crearSelectTraje(index) {
        let html = '<select name="id_traje['+index+']" required>';
        html += '<option value="">-- Seleccione --</option>';
        trajesData.forEach(t => {
            const label = t.codigo + " - " + t.nombre + " (Stock: " + t.stock + ")";
            html += '<option value="'+t.id+'" data-precio="'+t.precio_venta+'">'+label+'</option>';
        });
        html += '</select>';
        return html;
    }

    function agregarFila() {
        const index = tbody.children.length;
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${crearSelectTraje(index)}</td>
            <td><input type="number" name="cantidad[${index}]" min="1" value="1" oninput="recalcularFila(this)"></td>
            <td><input type="number" step="0.01" min="0" name="precio_unitario[${index}]" value="0" oninput="recalcularFila(this)"></td>
            <td class="text-right"><span class="subtotal">0.00</span></td>
            <td><button type="button" class="btn btn-small btn-danger" onclick="eliminarFila(this)">🗑</button></td>
        `;

        tbody.appendChild(tr);

        // Pre-cargar precio al seleccionar traje
        const select = tr.querySelector('select');
        select.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const precio = option.getAttribute('data-precio') || 0;
            const precioInput = tr.querySelector('input[name^="precio_unitario"]');
            precioInput.value = parseFloat(precio).toFixed(2);
            recalcularFila(precioInput);
        });
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
</script>
echo "<script>localStorage.removeItem('carritoVenta');</script>";
