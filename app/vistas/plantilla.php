<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Fruteria</title>
        <link type="text/css" rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/vader/jquery-ui.css" />
        <?php echo \nucleo\Recursos::css("main"); ?>
        <?php echo \nucleo\Recursos::css("fruteria", "listado"); ?>
        <?php echo \nucleo\Recursos::js("jquery"); ?>
        <?php echo \nucleo\Recursos::js("jquery-ui"); ?>
        <?php echo \nucleo\Recursos::js("main"); ?>
        <?php echo \nucleo\Recursos::js("fruteria", "main"); ?>
    </head>
    <body>
        <div id="cabecera" class="ui-widget-header">
            <h1>Fruteria</h1>
            <?php // echo \nucleo\Recursos::imagen("", "carrito.jpg", array("alt" => "imagen", "width" => "200")); ?>
        </div>
        <div id="cuerpo" class="ui-widget-content">
            <div id="div_menu">
                <ul id="menu">
                    <!--<li><a href="<?php // echo \nucleo\URL::ruta(array("carrito","listaCarritos")) ?>">Ver carrito</a></li>-->
                    <li><a href="<?php echo \nucleo\URL::ruta(array("fruteria","listado")) ?>">Ver articulos</a></li>
                </ul>
            </div>
            <div id="contenido">
                <?php if($flash = \nucleo\Sesion::getFlash()){
                    echo "<div id='flash'><span>".$flash."</span></div>";
                } 
                ?>
                <?php
                echo $contenido;
                ?>
            </div>
        </div>
        <div id="pie"></div>
    </body>
</html>