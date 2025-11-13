<h1>Lista de Trajes</h1>

<a href="index.php?c=trajes&a=crear">➕ Nuevo traje</a>
<div class="card" style="margin-bottom:16px;">
    <h3>Filtros de búsqueda</h3>

    <div class="form-group" style="max-width:300px;">
        <label>Categoría</label>
        <input type="text" id="filtroCategoria">
    </div>

    <div class="form-group" style="max-width:300px;">
        <label>Talla</label>
        <input type="text" id="filtroTalla">
    </div>

    <div class="form-group" style="max-width:300px;">
        <label>Color</label>
        <input type="text" id="filtroColor">
    </div>

    <div class="form-group" style="max-width:300px;">
        <label>Precio máximo</label>
        <input type="number" id="filtroPrecio">
    </div>

    <button class="btn btn-primary" onclick="guardarFiltros()">Guardar filtros</button>
</div>
<script>
function guardarFiltros() {
    const filtros = {
        categoria: document.getElementById("filtroCategoria").value,
        talla: document.getElementById("filtroTalla").value,
        color: document.getElementById("filtroColor").value,
        precio: document.getElementById("filtroPrecio").value
    };

    localStorage.setItem("filtrosTrajes", JSON.stringify(filtros));
    alert("Filtros guardados en LocalStorage");
}

window.onload = function() {
    const guardado = localStorage.getItem("filtrosTrajes");
    if (guardado) {
        const f = JSON.parse(guardado);
        document.getElementById("filtroCategoria").value = f.categoria;
        document.getElementById("filtroTalla").value = f.talla;
        document.getElementById("filtroColor").value = f.color;
        document.getElementById("filtroPrecio").value = f.precio;
    }
};
</script>

<table border="1" cellpadding="8">
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

    <?php foreach ($trajes as $t): ?>
    <tr>
        <td><?= $t['codigo'] ?></td>
        <td><?= $t['nombre'] ?></td>
        <td><?= $t['categoria'] ?></td>
        <td><?= $t['tela'] ?></td>
        <td><?= $t['talla'] ?></td>
        <td><?= $t['numero_piezas'] ?></td>
        <td><?= $t['color'] ?></td>
        <td><?= $t['precio_venta'] ?></td>
        <td><?= $t['stock'] ?></td>

        <td>
            <a href="index.php?c=trajes&a=editar&id=<?= $t['id'] ?>">✏ Editar</a>
            <a href="index.php?c=trajes&a=eliminar&id=<?= $t['id'] ?>"
               onclick="return confirm('¿Eliminar este traje?');">❌ Eliminar</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<script>
window.onload = function() {
    const data = localStorage.getItem("ultimoTrajeVisto");
    if (data) {
        const t = JSON.parse(data);
        const aviso = document.createElement("div");

        aviso.style = `
            background:#dbeafe;
            padding:10px;
            margin-bottom:12px;
            border-radius:8px;
            font-size:14px;
        `;

        aviso.innerHTML = `
            🔔 Último traje visto: <b>${t.codigo}</b> - ${t.nombre}
            (Bs. ${t.precio})
        `;

        document.body.prepend(aviso);
    }
};
</script>
