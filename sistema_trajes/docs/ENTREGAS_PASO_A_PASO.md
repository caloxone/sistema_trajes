# Secuencia sugerida para copiar archivos

Para facilitar el copiado gradual en instalaciones como XAMPP, los archivos se han agrupado en lotes de dos. Cada lote puede copiarse y probarse antes de pasar al siguiente.

| Lote | Archivos |
| --- | --- |
| 1 | `controllers/ClientesController.php`, `controllers/TrajesController.php` |
| 2 | `controllers/AuthController.php`, `controllers/VentasController.php` |
| 3 | `controllers/CategoriasController.php`, `controllers/TelasController.php` |
| 4 | `controllers/TallasController.php`, `views/layout/header.php` |
| 5 | `views/trajes/index.php`, `views/trajes/crear.php` |
| 6 | `views/trajes/editar.php`, `views/clientes/index.php` |
| 7 | `views/clientes/formulario.php`, `views/ventas/index.php` |
| 8 | `views/ventas/crear.php`, `views/ventas/ver.php` |
| 9 | `views/dashboard/index.php`, `docs/CODIGOS_ACTUALIZADOS.md` |

> Cada archivo listado en esta tabla ya está actualizado y es compatible con versiones antiguas de PHP (sin el operador `??`). Puedes abrirlos directamente en `docs/CODIGOS_ACTUALIZADOS.md` o dentro de la carpeta `sistema_trajes/` para copiarlos según el lote que estés trabajando.

## Nueva etapa: lotes de tres archivos

El equipo solicitó continuar con bloques de **tres archivos a la vez** para acelerar la copia manual. Si prefieres este formato, sigue la tabla siguiente. Cada lote retoma donde quedó la tabla anterior y agrupa vistas relacionadas para que las pegues y pruebes juntas.

| Lote | Archivos |
| --- | --- |
| 10 | `views/tallas/index.php`, `views/tallas/formulario.php`, `views/telas/index.php` |
| 11 | `views/telas/formulario.php`, `views/categorias/index.php`, `views/categorias/formulario.php` |
| 12 | `views/auth/login.php`, `views/layout/header.php`, `views/dashboard/index.php` |
| 13 | `views/clientes/index.php`, `views/clientes/formulario.php`, `views/trajes/index.php` |
| 14 | `views/trajes/crear.php`, `views/trajes/editar.php`, `views/ventas/index.php` |
| 15 | `views/ventas/crear.php`, `views/ventas/ver.php`, `docs/CODIGOS_ACTUALIZADOS.md` |
| 16 | `controllers/TrajesController.php`, `views/trajes/crear.php`, `views/trajes/editar.php` *(ver `docs/LOTE_TRAJES_FK.md`)* |

> Sugerencia: si necesitas otro agrupamiento (por ejemplo, controladores adicionales o módulos nuevos), añade un renglón a la tabla con los archivos que quieras copiar juntos para mantener un registro claro de tu avance.
