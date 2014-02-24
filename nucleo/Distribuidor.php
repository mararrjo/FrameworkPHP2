<?php

namespace nucleo;

class Distribuidor {

    private static $controlador;
    private static $metodo;

    public static function mostrarVista() {

        $controlador = "";
        $metodo = "";

        if (!isset($_GET["controlador"])) {
            $controlador = \app\Configuracion::$controlador_defecto;
        } else {
            $controlador = $_GET["controlador"];
        }
        self::$controlador = $controlador;

        require_once "app/controladores/$controlador.php";

        $controlador = "app\\controladores\\" . $controlador;
        $modulo = new $controlador();

        if (!isset($_GET["metodo"])) {
            $metodo = \app\Configuracion::$metodo_defecto;
        } else {
            $metodo = $_GET["metodo"];
        }
        self::$metodo = $metodo;

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

    public static function getControlador() {
        return self::$controlador;
    }

    public static function getMetodo() {
        return self::$metodo;
    }

}
