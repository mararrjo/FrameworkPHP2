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
    public function get($param) {
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

}
