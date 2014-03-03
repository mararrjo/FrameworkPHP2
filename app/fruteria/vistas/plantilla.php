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
        <?php if(nucleo\Usuarios::estaValidado()): ?>
        <div>
            <?php $usuario = nucleo\Usuarios::obtenerUsuario(); ?>
            <span style="background-color: #eee;">Usuario: <b><?php echo $usuario->getUsuario() ?></b></span>
            <span style="background-color: #eee;"> <?php 
            $tiempoInicio = nucleo\Usuarios::fechaInicioSesion()->format("h:i a");
            $tiempoSesion = nucleo\Usuarios::tiempoSesion();
            $tiempoInactividad = nucleo\Usuarios::tiempoInactividad();
            $horaUltimaActividad = nucleo\Usuarios::horaUltimaActividad()->format("h:i a");
            echo "Inicio de sesion: $tiempoInicio - Tiempo: $tiempoSesion minutos - Ultima Actividad: $horaUltimaActividad";
            ?></span>
            <a href="<?php echo \nucleo\URL::ruta(array("usuario","cerrarSesion")) ?>">Cerrar Sesion</a>
        </div>
        <?php endif; ?>
        <div id="cabecera" class="ui-widget-header">
            <h1>Fruteria</h1>
            <?php // echo \nucleo\Recursos::imagen("", "carrito.jpg", array("alt" => "imagen", "width" => "200")); ?>
        </div>
        <div id="cuerpo" class="ui-widget-content">
            <div id="div_menu">
                <ul id="menu">
                    <!--<li><a href="<?php // echo \nucleo\URL::ruta(array("carrito","listaCarritos"))    ?>">Ver carrito</a></li>-->
                    <li><a href="<?php echo \nucleo\URL::ruta(array("fruteria", "listado")) ?>">Ver articulos</a></li>
                </ul>
            </div>
            <div id="contenido">
                <?php
                if ($flash = \nucleo\Sesion::getFlash()) {
                    echo "<div id='flash'><span>" . $flash . "</span></div>";
                }
                ?>
                <?php
                echo $contenido;
                ?>
            </div>
        </div>
        <div id="pie" class="ui-widget-header">
            <div>
                <a href="" onclick="$('#conectar').slideDown(); return false">Conectarse</a>
                <div id="conectar" style="display: none;">
                    <form name="validarUsuario" method="POST" action="<?php echo \nucleo\URL::ruta(array("usuario", "validarUsuario")) ?>">
                        Usuario: <input type="text" name="usuario"><br>
                        Password: <input type="password" name="password"><br>
                        <input type="submit" name="bAceptar" value="Aceptar">
                        <input type="button" name="bCancelar" value="Cancelar" onclick="$('#conectar').slideUp()">
                    </form>
                </div>
                
            </div>
        </div>
    </body>
</html>