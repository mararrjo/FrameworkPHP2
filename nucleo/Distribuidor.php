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

        $request = new Requerimiento();
        $request->analizar_requerimiento();
        
        if (!$request->get("aplicacion")) {
            $aplicacion = \app\Configuracion::$aplicacion;
        } else {
            $aplicacion = $request->get("aplicacion");
        }
        self::$aplicacion = $aplicacion;

        if (!$request->get("controlador")) {
            $controlador = \app\Configuracion::$controlador_defecto;
        } else {
            $controlador = $request->get("controlador");
        }
        self::$controlador = $controlador;

        $controlador = "app\\$aplicacion\\controladores\\" . $controlador;
        if (is_file($controlador . ".php")) {
            $modulo = new $controlador();
            if (!$request->get("metodo")) {
                $metodo = \app\Configuracion::$metodo_defecto;
            } else {
                $metodo = $request->get("metodo");
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
        


        if ($request->get("id")) {
            $id = $request->get("id");
            if ($request->get("id2")) {
                $id2 = $request->get("id2");
                $modulo->$metodo($request, $id, $id2);
            } else {
                $modulo->$metodo($request, $id);
            }
        } else {
            $modulo->$metodo($request);
        }
    }

    public static function getAplicacion() {
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
