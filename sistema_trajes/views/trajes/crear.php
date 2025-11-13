<h2>Registrar nuevo traje</h2>

<form action="index.php?c=trajes&a=guardar" method="POST">

    Código: <input type="text" name="codigo" required><br><br>
    Nombre: <input type="text" name="nombre" required><br><br>
    Categoría (ID): <input type="number" name="id_categoria"><br><br>
    Tela (ID): <input type="number" name="id_tela"><br><br>
    Talla (ID): <input type="number" name="id_talla"><br><br>

    Nº piezas: <input type="number" name="numero_piezas" value="2"><br><br>
    Color: <input type="text" name="color"><br><br>
    Tipo: <input type="text" name="tipo"><br><br>
    Precio: <input type="text" name="precio_venta" required><br><br>
    Stock: <input type="number" name="stock" required><br><br>

    <button type="submit">Guardar</button>
</form>
