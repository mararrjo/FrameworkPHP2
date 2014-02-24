<?php

namespace nucleo;

class Sesion {

    private $idSesion;

    public function __construct() {
        session_start();
        $this->idSesion = session_id();
    }

    public function getIdSesion() {
        return $this->idSesion;
    }

    public static function setFlash($mensaje) {
        $_SESSION["flash"] = $mensaje;
    }

    public static function getFlash() {
        if (isset($_SESSION["flash"])) {
            $flash = $_SESSION["flash"];
            unset($_SESSION["flash"]);
            return $flash;
        } else {
            return "";
        }
    }

    public static function setMensaje($variable, $mensaje) {
        $_SESSION[$variable] = $mensaje;
    }

    public static function getMensaje($variable) {
        if (isset($_SESSION[$variable])) {
            return $_SESSION[$variable];
        } else {
            return "";
        }
    }

    public static function setDatos($lugar, $datos) {
        $_SESSION[$lugar] = $datos;
    }

    public static function getDatos($lugar, $id = 0) {
        if (isset($_SESSION[$lugar])) {
            if ($id) {
                if (isset($_SESSION[$lugar][$id])) {
                    return $_SESSION[$lugar][$id];
                } else {
                    return null;
                }
            } else {
                return $_SESSION[$lugar];
            }
        } else {
            return null;
        }
    }

}
