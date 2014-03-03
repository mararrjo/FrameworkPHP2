<?php

namespace nucleo;

class Controlador {

    protected $form;
    protected $baseDatos;

    public function __construct() {
        $this->baseDatos = new \nucleo\BD();
    }

    /**
     * Genera el documento html con todos los datos ya preparados.
     * 
     * @param array $datos Es un array que contiene datos o variables que se
     * utilizarán en las vistas. Ejemplo array("lista"=>$listaArticulos). Se
     * creará una variable de nombre: $lista que contendrá lo que tenia 
     * $listaArticulos.
     */
    public function renderizar(array $datos = array()) {
        foreach ($datos as $variable => $valor) {
            $$variable = $valor;
        }
        ob_start();
        include "app/". \nucleo\Distribuidor::getAplicacion()."/vistas/" . \nucleo\Distribuidor::getControlador() . "/" . \nucleo\Distribuidor::getMetodo() . ".php";
        $contenido = ob_get_clean();
        ob_start();
        include "app/". \app\Configuracion::$aplicacion."/vistas/" . \app\Configuracion::$vista_plantilla . ".php";
        echo ob_get_clean();
    }

    /**
     * Genera el documento html con todos los datos ya preparados.
     * 
     * @param string $plantilla Nombre de la plantilla que se utilizará.
     * @param string $controlador Nombre del controlador.
     * @param string $metodo Metodo que se usará.
     * @param array $datos Es un array que contiene datos o variables que se
     * utilizarán en las vistas. Ejemplo array("lista"=>$listaArticulos). Se
     * creará una variable de nombre: $lista que contendrá lo que tenia 
     * $listaArticulos.
     */
    public function renderizarPlantilla($plantilla, $controlador, $metodo, array $datos = array()) {
        foreach ($datos as $variable => $valor) {
            $$variable = $valor;
        }
        ob_start();
        include "app/". \nucleo\Distribuidor::getAplicacion()."/vistas/" . $controlador . "/" . $metodo . ".php";
        $contenido = ob_get_clean();
        ob_start();
        include "app/". \nucleo\Distribuidor::getAplicacion()."/vistas/" . $plantilla . ".php";
        echo ob_get_clean();
    }

    /**
     * Redirecciona el metodo a otro lugar.
     * 
     * @param string $controlador Controlador que se usará
     * @param string $metodo Metodo que se llamará
     * @param array $datos Es un array con informacion adicional, como puede
     * ser el id de un objeto. Ejemplo array("id"=>$id)
     */
    public function redireccionar($controlador, $metodo, array $datos = array()) {
        if (isset($datos["id"])) {
            $id = $datos["id"];
            $url = \nucleo\URL::ruta(array($controlador, $metodo, $id));
        } else {
            $url = \nucleo\URL::ruta(array($controlador, $metodo));
        }
        echo "<script type='text/javascript'>location.assign('$url')</script>";
    }

    /**
     * Obtiene el gestor de base de datos.
     * 
     * @param String $tabla Si se pasa este argumento se obtiene una instancia
     * de ese objeto si existe para usar el gestor de la base de datos suya.
     * @return \nucleo\BD o \app\modelos\tabla
     */
    public function getBaseDatos($tabla = "") {
        if ($tabla) {
            $clase = "\\app\\". Distribuidor::getAplicacion()."\\modelos\\$tabla";
            if (class_exists($clase,false)) {
                $obj = new $clase();
                return $obj;
            } else {
                URL::generarError("No existe ese objeto en la base de datos");
            }
        }
        return $this->baseDatos;
    }

}
