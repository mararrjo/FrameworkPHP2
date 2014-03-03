<?php
namespace nucleo;

/**
 * Genera la respuesta http correspondiente.
 */
class Respuesta {
    
    private $plantilla;
    private $contenido;
    
    public function __construct() {
        $this->plantilla = "";
        $this->contenido = "";
    }
    
    /**
     * Genera la vista correspondiente al controlador y metodo pasados por 
     * parametros. Si no se pasan, se usaran el controlador y metodo que han
     * invocado a esta función.
     * 
     * @param array $datos Variables que se utilizaran en las vistas
     * @param string $controlador El controlador que se usará
     * @param string $metodo El metodo que se ejecutará
     * @param string $plantilla Plantilla que se usará
     */
    public function generar(array $datos = array(), $controlador = "", $metodo = "", $plantilla = ""){
        $contenido = $this->generarContenido($datos, $controlador, $metodo);
        $plantilla = $this->generarPlantilla($contenido,$plantilla);
        echo $plantilla;
    }
    
    /**
     * Genera la vista de la plantilla con el contenido pasado por parametro.
     * Si no se añade el parametro de plantilla se usará el que esta por defecto.
     * 
     * @param string $contenido HTML que se corresponde al contenido generado 
     * por el controlador.
     * @param string $plantilla
     * @return string Genera el documento HTML completo.
     */
    public function generarPlantilla($contenido, $plantilla = ""){
        if(!$plantilla){
            $plantilla = \app\Configuracion::$vista_plantilla;
        }
        ob_start();
        include "app/". \app\Configuracion::$aplicacion."/vistas/" . $plantilla . ".php";
        $plantilla = ob_get_clean();
        $this->plantilla = $plantilla;
        return $plantilla;
    }
    
    /**
     * Genera la vista del controlador con las variables que necesite. Si no se
     * añaden los parametros $controlador y $metodo usará el que haya llamado
     * a esta función.
     * 
     * @param array $datos Lista de variables que se pasarán a la vista.
     * @param string $controlador Controlador que usará.
     * @param string $metodo Archivo PHP que incluirá.
     * @return string Genera el contenido HTML del controlador. 
     */
    public function generarContenido(array $datos, $controlador = "", $metodo = ""){
        foreach ($datos as $variable => $valor) {
            $$variable = $valor;
        }
        
        if(!$controlador){
            $controlador = Distribuidor::getControlador();
        }
        if(!$metodo){
            $metodo = Distribuidor::getMetodo();
        }
        
        ob_start();
        include "app/". \nucleo\Distribuidor::getAplicacion()."/vistas/" . $controlador . "/" . $metodo . ".php";
        $contenido = ob_get_clean();
        $this->contenido = $contenido;
        return $contenido;
    }
    
}