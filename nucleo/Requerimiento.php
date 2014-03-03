<?php

namespace nucleo;

class Requerimiento {

    public $get;
    public $post;
    public $request;

    public function __construct() {

        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
    }

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
