<div class="seccion">
    <h3>Articulos:</h3>
    <table id="tabla_listado">
        <thead>
            <tr class="ui-widget-header">
                <th>Id</th>
                <th>Nombre</th>
                <th>Categoria</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articulos as $articulo): ?>
                <tr>
                    <td><?php echo $articulo->getId(); ?></td>
                    <td><?php echo $articulo->getNombre(); ?></td>
                    <td><?php echo $articulo->getCategoria(); ?></td>
                    <td><?php echo $articulo->getPrecio(); ?></td>
                    <td><?php echo $articulo->getCantidad(); ?></td>
                    <td><a href="<?php echo nucleo\URL::ruta(array("articulos", "mostrar", $articulo->getId())) ?>"><button class="boton">Ver</button></a>
                        <?php if(nucleo\Usuarios::estaValidado()): ?>
                        <a href="<?php echo nucleo\URL::ruta(array("articulos", "modificar", $articulo->getId())) ?>"><button class="boton">Modificar</button></a>
                        <a href="<?php echo nucleo\URL::ruta(array("articulos", "eliminar", $articulo->getId())) ?>"><button class="boton">Eliminar</button></a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6">
                    <a href="<?php echo nucleo\URL::ruta(array("articulos", "anadir")); ?>"><button class="boton">Añadir</button></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>