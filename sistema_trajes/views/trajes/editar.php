<h2>Editar traje</h2>

<form action="index.php?c=trajes&a=actualizar" method="POST">

    <input type="hidden" name="id" value="<?= $traje['id'] ?>">

    Código: <input value="<?= $traje['codigo'] ?>" name="codigo"><br><br>
    Nombre: <input value="<?= $traje['nombre'] ?>" name="nombre"><br><br>
    Categoría ID: <input value="<?= $traje['id_categoria'] ?>" name="id_categoria"><br><br>
    Tela ID: <input value="<?= $traje['id_tela'] ?>" name="id_tela"><br><br>
    Talla ID: <input value="<?= $traje['id_talla'] ?>" name="id_talla"><br><br>

    Nº piezas: <input value="<?= $traje['numero_piezas'] ?>" name="numero_piezas"><br><br>
    Color: <input value="<?= $traje['color'] ?>" name="color"><br><br>
    Tipo: <input value="<?= $traje['tipo'] ?>" name="tipo"><br><br>
    Precio: <input value="<?= $traje['precio_venta'] ?>" name="precio_venta"><br><br>
    Stock: <input value="<?= $traje['stock'] ?>" name="stock"><br><br>

    <button type="submit">Actualizar</button>
</form>
<script>
const ultimoTraje = {
    id: "<?= $traje['id'] ?>",
    codigo: "<?= $traje['codigo'] ?>",
    nombre: "<?= $traje['nombre'] ?>",
    precio: "<?= $traje['precio_venta'] ?>"
};

localStorage.setItem("ultimoTrajeVisto", JSON.stringify(ultimoTraje));
</script>
