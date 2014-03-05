<?php

namespace nucleo;

/**
 * Clase que gestiona los arrays $_GET, $_POST Y $_REQUEST
 */
class Requerimiento {

    public $get;
    public $post;
    public $request;

    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
    }

    /**
     * 
     * @param mixed $param Si es un string devuelve el valor de $_GET[$param]
     * si es un array devuelve un array con los parametros que coincidan.
     * @return string o array Si el parametro de entrada era un String devuelve
     * $_GET[$param] si existe y "" si no existe. Si $param es un array,
     * devuelve un array con las coincidencias que se encuentren en $_GET.
     */
    public function get($param, $valor = "") {
        if ($valor) {
            $this->get[$param] = $valor;
            $_GET[$param] = $valor;
            return $this->get;
        }
        if (is_array($param)) {
            $parametros = array();
            foreach ($param as $p) {
                if (isset($this->get[$p])) {
                    $parametros[$p] = $this->get[$p];
                }
            }
            return $parametros;
        } else {
            if (isset($this->get[$param])) {
                return $this->get[$param];
            } else {
                return "";
            }
        }
    }

    /**
     * 
     * @param mixed $param Si es un string devuelve el valor de $_POST[$param]
     * si es un array devuelve un array con los parametros que coincidan.
     * @return string o array Si el parametro de entrada era un String devuelve
     * $_POST[$param] si existe y "" si no existe. Si $param es un array,
     * devuelve un array con las coincidencias que se encuentren en $_POST.
     */
    public function post($param) {
        if (is_array($param)) {
            $parametros = array();
            foreach ($param as $p) {
                if (isset($this->post[$p])) {
                    $parametros[$p] = $this->post[$p];
                }
            }
            return $parametros;
        } else {
            if (isset($this->post[$param])) {
                return $this->post[$param];
            } else {
                return "";
            }
        }
    }

    /**
     * 
     * @param mixed $param Si es un string devuelve el valor de $_REQUEST[$param]
     * si es un array devuelve un array con los parametros que coincidan.
     * @return string o array Si el parametro de entrada era un String devuelve
     * $_REQUEST[$param] si existe y "" si no existe. Si $param es un array,
     * devuelve un array con las coincidencias que se encuentren en $_REQUEST.
     */
    public function request($param) {
        if (is_array($param)) {
            $parametros = array();
            foreach ($param as $p) {
                if (isset($this->request[$p])) {
                    $parametros[$p] = $this->request[$p];
                }
            }
            return $parametros;
        } else {
            if (isset($this->request[$param])) {
                return $this->request[$param];
            } else {
                return "";
            }
        }
    }

    public function analizar_requerimiento() {

        if ($this->get("p1") == "frontend" || $this->get("p1") == "backend" || $this->get("p1")== "admin") {

            $url = "";
            if ($this->get("p1")) {
                if($this->get("p1")=="admin"){
                    $this->get("aplicacion", "backend");
                }else
                    $this->get("aplicacion", $this->get("p1"));
            }
            if ($this->get("p2")) {
                $this->get("controlador", $this->get("p2"));
                $url .= $this->get("controlador");
            }
            if ($this->get("p3")) {
                $this->get("metodo", $this->get("p3"));
                $url .= "/" . $this->get("metodo");
            }
            if ($this->get("p4")) {
                $this->get("id", $this->get("p4"));
                $url .= "/" . $this->get("id");
            }
            if ($this->get("p5")) {
                $this->get("id2", $this->get("p5"));
                $url .= "/" . $this->get("id2");
            }

            if ($this->get("aplicacion") == "frontend") {
                header("Location: " . URL . $url);
            }
            
        }else{

            $this->get("aplicacion", "frontend");

            if ($this->get("p1")) {
                $this->get("controlador", $this->get("p1"));
                $this->quitar_get("p1");
            }
            if ($this->get("p2")) {
                $this->get("metodo", $this->get("p2"));
                $this->quitar_get("p2");
            }
            if ($this->get("p3")) {
                $this->get("id", $this->get("p3"));
                $this->quitar_get("p3");
            }
            if ($this->get("p4")) {
                $this->get("id2", $this->get("p4"));
                $this->quitar_get("p4");
            }
        }
    }
    
    public function quitar_get($param){
        unset($this->get[$param]);
        unset($_GET[$param]);
    }
    
    public function quitar_post($param){
        unset($this->post[$param]);
        unset($_POST[$param]);
    }
    
    public function quitar_request($param){
        unset($this->request[$param]);
        unset($_REQUEST[$param]);
    }

}
