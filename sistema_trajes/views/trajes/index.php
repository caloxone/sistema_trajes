<div class="section-title">
    <div>
        <h1>Inventario de trajes</h1>
        <p class="card-subtitle">Controla todos los modelos disponibles y su stock.</p>
    </div>
    <div class="acciones">
        <a class="btn btn-primary" href="index.php?c=trajes&a=crear">➕ Nuevo traje</a>
    </div>
</div>

<div id="avisoTraje"></div>

<div class="card">
    <div class="section-title" style="margin-bottom: 12px;">
        <h3>Filtros de búsqueda</h3>
        <button class="btn btn-secondary" type="button" onclick="guardarFiltros()">Guardar filtros</button>
    </div>
    <div class="form-grid">
        <div class="form-group">
            <label for="filtroCategoria">Categoría</label>
            <input type="text" id="filtroCategoria" placeholder="Ej. Smoking">
        </div>
        <div class="form-group">
            <label for="filtroTalla">Talla</label>
            <input type="text" id="filtroTalla" placeholder="M, L, XL">
        </div>
        <div class="form-group">
            <label for="filtroColor">Color</label>
            <input type="text" id="filtroColor" placeholder="Negro, azul…">
        </div>
        <div class="form-group">
            <label for="filtroPrecio">Precio máximo</label>
            <input type="number" id="filtroPrecio" placeholder="250">
        </div>
    </div>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Tela</th>
                <th>Talla</th>
                <th>Piezas</th>
                <th>Color</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($trajes)): ?>
                <?php foreach ($trajes as $t): ?>
                <tr class="fila-traje" data-traje='<?= json_encode(array(
                    "codigo" => $t['codigo'],
                    "nombre" => $t['nombre'],
                    "precio" => $t['precio_venta']
                ), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'>
                    <td><span class="tag">#<?= htmlspecialchars($t['codigo']) ?></span></td>
                    <td><?= htmlspecialchars($t['nombre']) ?></td>
                    <td><?= htmlspecialchars($t['categoria']) ?></td>
                    <td><?= htmlspecialchars($t['tela']) ?></td>
                    <td><?= htmlspecialchars($t['talla']) ?></td>
                    <td><?= htmlspecialchars($t['numero_piezas']) ?></td>
                    <td><?= htmlspecialchars($t['color']) ?></td>
                    <td>Bs. <?= number_format($t['precio_venta'], 2) ?></td>
                    <td><?= htmlspecialchars($t['stock']) ?></td>
                    <td class="acciones">
                        <a class="btn btn-secondary btn-small" href="index.php?c=trajes&a=editar&id=<?= $t['id'] ?>">✏ Editar</a>
                        <a class="btn btn-danger btn-small" href="index.php?c=trajes&a=eliminar&id=<?= $t['id'] ?>"
                           onclick="return confirm('¿Eliminar este traje?');">🗑 Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No hay trajes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
const guardarFiltros = () => {
    const filtros = {
        categoria: document.getElementById('filtroCategoria').value,
        talla: document.getElementById('filtroTalla').value,
        color: document.getElementById('filtroColor').value,
        precio: document.getElementById('filtroPrecio').value
    };
    localStorage.setItem('filtrosTrajes', JSON.stringify(filtros));
    alert('Filtros guardados');
};

document.addEventListener('DOMContentLoaded', () => {
    const guardado = localStorage.getItem('filtrosTrajes');
    if (guardado) {
        const f = JSON.parse(guardado);
        document.getElementById('filtroCategoria').value = f.categoria || '';
        document.getElementById('filtroTalla').value = f.talla || '';
        document.getElementById('filtroColor').value = f.color || '';
        document.getElementById('filtroPrecio').value = f.precio || '';
    }

    const avisoTraje = document.getElementById('avisoTraje');
    const data = localStorage.getItem('ultimoTrajeVisto');
    if (data) {
        const t = JSON.parse(data);
        avisoTraje.innerHTML = `
            <div class="card" style="background:#dbeafe; color:#1e3a8a;">
                🔔 Último traje visto: <strong>${t.codigo}</strong> - ${t.nombre} (Bs. ${t.precio})
            </div>
        `;
    }

    document.querySelectorAll('.fila-traje').forEach((fila) => {
        fila.addEventListener('click', () => {
            localStorage.setItem('ultimoTrajeVisto', fila.dataset.traje);
        });
    });
});
</script>
