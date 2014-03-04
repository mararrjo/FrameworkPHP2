<ul>
    <li>Id: <?php echo $articulo->getId() ?></li>
    <li>Nombre: <?php echo $articulo->getNombre() ?></li>
    <li>Categoria: <?php echo $articulo->getCategoria()?></li>
    <li>Precio: <?php echo $articulo->getPrecio() ?></li>
    <li>Cantidad: <?php echo $articulo->getCantidad() ?></li>
</ul>
<br>
<a href="<?php echo nucleo\URL::ruta(array("articulos","listado")) ?>">Volver atras</a>