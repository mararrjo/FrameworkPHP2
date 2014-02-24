<?php

namespace nucleo;

class Formularios {

    /**
     * Almacena un array con la forma array("id"=>"tipo")
     *  o array("id"=>array(
     *              "type"=>"text", 
     *              "multiple"=>true,
     *              "expandido"=>true, 
     *              "opciones"=>array("opcion1","opcion2",...) o "nombre_tabla"))
     * 
     * @var array $campos Contiene los campos que se mostraran en el formulario
     * con su tipo y parametros.
     */
    private $campos = array();

    /**
     * Almacena un array con la forma array("campo1"=>"validacion", "campo2"=>"validacion",...
     *
     * @var array Contiene los campos del formulario con su validacion.
     */
    private $validaciones = array();

    /**
     * Contiene los campos con los errores obtenidos al validar
     * array("campo1"=>"error","campo2"=>"error")
     * 
     * @var array
     */
    private $errores = array();
    
    /**
     * Es un array de la forma array("campo1"=>"valor1","campo2"=>"valor2",...)
     * 
     * @var array $datos Contiene los datos de los campos del formulario con 
     * sus valores 
     */
    private $datos = array();

    /**
     *
     * @var String
     */
    private $nombre_clase;

    /**
     *
     * @var boolean
     */
    private $valido;

    public function __construct($objeto = null) {

        $this->configuracion();
        if ($objeto) {
            $this->datos = $objeto->obtenerArrayCampos($this->campos);
        }
        $this->nombre_clase = str_getcsv(get_class($this), "_")[1];
    }

    /**
     * Metodo que sirve para configurar los campos que van a tener los formularios.
     * Se usa para llamar a los metodos setCampos y setValidaciones.
     * 
     */
    public function configuracion() {
        
    }

    public function setCampos(array $campos) {
        $this->campos = $campos;
    }

    public function setValidaciones(array $validaciones) {
        $this->validaciones = $validaciones;
    }

    public function obtenerCampos() {
        return $this->campos;
    }

    public function obtenerDatos() {
        return $this->datos;
    }

    public function procesarFormulario(array $request, &$clase) {
        $arrayDatos = array();
        foreach ($this->campos as $clave => $tipo) {
            if (isset($request[$clave])) {
                $arrayDatos[$clave] = $request[$clave];
            }
        }
        if (isset($request["id"])) {
            $arrayDatos["id"] = $request["id"];
        }
        $this->datos = $arrayDatos;

        //Hacer validacion aqui
        if (count($this->validaciones) > 0) {
            $resultado = \nucleo\Validaciones::validar($this->validaciones, $this->datos);
            if ($resultado === true) {
                $this->valido = true;
            } else {
                $this->errores = $resultado;
                $this->valido = false;
            }
        } else {
            $this->valido = true;
        }
        
        $clase->guardarDatosDeArray($this->datos);
    }

    public function esValido() {
        return $this->valido;
    }

    public function renderizarFormulario($action = "") {
        $id = isset($this->datos["id"]) ? $this->datos["id"] : "";
        if ($action) {
            $accion = $action;
        } else {
            if (!preg_match("/validar/i", \nucleo\Distribuidor::getMetodo()))
                $accion = \nucleo\URL::ruta(array(\nucleo\Distribuidor::getControlador(), \nucleo\Distribuidor::getMetodo() . "_validar", $id));
            else
                $accion = \nucleo\URL::ruta(array(\nucleo\Distribuidor::getControlador(), \nucleo\Distribuidor::getMetodo(), $id));
        }
        $form = "<form action='" . $accion . "'  class='formulario' name='form_" . $this->nombre_clase . "_" . \nucleo\Distribuidor::getMetodo() . "' method='post'>";

        if (isset($this->datos["id"])) {
            $form .= \nucleo\Widgets::hidden("id", $id);
        }
        foreach ($this->campos as $campo => $parametros) {
            $value = isset($this->datos[$campo]) ? $this->datos[$campo] : "";
            $form .= "<div class='campo_" . $this->nombre_clase . "'>";
            if (!is_array($parametros)) {
                $tipo = $parametros;
                $form .= \nucleo\Widgets::$tipo($campo, $value);
            } else {
                $tipo = $parametros["type"];
                $form .= \nucleo\Widgets::$tipo($campo, $value, $parametros);
            }
            if(count($this->errores) > 0 and isset($this->errores[$campo])){
                $form .= "<span class='error_$this->nombre_clase' id='error_$campo'>{$this->errores[$campo]}</span>";
            }
            $form .= "</div>";
        }

        $form .= "</form>";
        return $form;
    }

}
