<?php

namespace nucleo;

class Distribuidor {

    private static $aplicacion;
    private static $controlador;
    private static $metodo;

    public static function mostrarVista() {

        self::comprobarTiempoSesion();

        $aplicacion = "";
        $controlador = "";
        $metodo = "";

        $aplicacion = \app\Configuracion::$aplicacion;
        
        if (!isset($_GET["controlador"])) {
            $controlador = \app\Configuracion::$controlador_defecto;
        } else {
            $controlador = $_GET["controlador"];
        }
        self::$controlador = $controlador;


        $controlador = "app\\$aplicacion\\controladores\\" . $controlador;
        if (is_file($controlador . ".php")) {
            $modulo = new $controlador();
            if (!isset($_GET["metodo"])) {
                $metodo = \app\Configuracion::$metodo_defecto;
            } else {
                $metodo = $_GET["metodo"];
            }

            if (method_exists($modulo, $metodo)) {
                self::$metodo = $metodo;
            } else {
                $controlador = "\\app\\$aplicacion\\controladores\\errores";
                self::$controlador = "errores";
                Sesion::setMensaje("error", "No existe el método");
                $modulo = new $controlador();
                $metodo = "index";
                self::$metodo = $metodo;
            }
        } else {
            $controlador = "\\app\\$aplicacion\\controladores\\errores";
            self::$controlador = "errores";
            Sesion::setMensaje("error", "No existe el controlador");
            $modulo = new $controlador();
            $metodo = "index";
            self::$metodo = $metodo;
        }


        if (isset($_GET["id"])) {
            $id = $_GET["id"];
            if (isset($_GET["id2"])) {
                $id2 = $_GET["id2"];
                $modulo->$metodo($_POST, $id, $id2);
            } else {
                $modulo->$metodo($_POST, $id);
            }
        } else {
            $modulo->$metodo($_POST);
        }
    }

    public static function getAplicacion(){
        return self::$aplicacion;
    }
    
    public static function getControlador() {
        return self::$controlador;
    }

    public static function getMetodo() {
        return self::$metodo;
    }

    public static function comprobarTiempoSesion() {
        if (Usuarios::sesionCaducada()) {
            Sesion::setFlash("Sesion caducada");
        } else {
            Sesion::setDatos("usuario_ultima_actividad", new \DateTime());
        }
    }

}
