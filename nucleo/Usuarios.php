<?php

namespace nucleo;

class Usuarios {

    public static function crearTabla() {
        $bd = new BD();
        $password = md5("1234");
        $query = "CREATE TABLE IF NOT EXISTS `usuario` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `usuario` varchar(30) NOT NULL,
            `password` varchar(36) NOT NULL,
            `correo` varchar(30),
            `activo` boolean NOT NULL,
            `rol` mediumint,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
            INSERT INTO `usuario` VALUES(0,'chema','81dc9bdb52d04dc20036dbd8313ed055',null,true,1);
            CREATE TABLE IF NOT EXISTS `rol` (
            `id` mediumint NOT NULL,
            `nombre` varchar(20) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB;
            INSERT INTO `rol` VALUES(1,'Administrador');
            INSERT INTO `rol` VALUES(2,'Usuario');
            INSERT INTO `rol` VALUES(3,'Anonimo');";
        $bd->query($query);
    }

    public static function validarUsuario($request) {
        $usuarios = new usuario();
        $usuarios = $usuarios->select()->ejecutar();
        foreach ($usuarios as $usuario) {
            if ($usuario->getUsuario() == $request["usuario"] and $usuario->getPassword() == md5($request["password"]) and $usuario->getActivo()) {
                return $usuario;
            }
        }
    }

    public static function estaValidado() {
        $usuario = Sesion::getDatos("usuario_nombre");
        if ($usuario and $usuario != "anonimo") {
            return $usuario;
        } else {
            return false;
        }
    }

    public static function obtenerUsuario($id = 0) {
        if (!$id) {
            $id = Sesion::getDatos("usuario_id");
        }
        if ($id) {
            $usuario = new usuario();
            $usuario = $usuario->select()->where("id=$id")->ejecutar();
            return $usuario[0];
        } else {
            return null;
        }
    }

    public static function tiempoSesion() {
        $tiempo = self::fechaInicioSesion();
        if ($tiempo) {
            $tiempo = $tiempo->format("h:i");
            $tiempoActual = new \DateTime();
            $tiempoActual = $tiempoActual->format("h:i");
            $diferencia = (strtotime($tiempo) - strtotime($tiempoActual)) / 60;
            $diferencia = abs($diferencia);
            $diferencia = round($diferencia);
            return $diferencia;
        } else {
            return 0;
        }
    }

    public static function tiempoInactividad() {
        $tiempo = Sesion::getDatos("usuario_ultima_actividad");
        if ($tiempo) {
            $tiempo = $tiempo->format("h:i");
            $tiempoActual = new \DateTime();
            $tiempoActual = $tiempoActual->format("h:i");
            $diferencia = (strtotime($tiempo) - strtotime($tiempoActual)) / 60;
            $diferencia = abs($diferencia);
            $diferencia = round($diferencia);
            return $diferencia;
        } else {
            return 0;
        }
    }

    public static function horaUltimaActividad() {
        return Sesion::getDatos("usuario_ultima_actividad");
    }

    public static function fechaInicioSesion() {
        return Sesion::getDatos("usuario_tiempo_sesion");
    }

    public static function iniciarSesion($usuario) {
        session_start();
        Sesion::setDatos("usuario_id", $usuario->getId());
        Sesion::setDatos("usuario_nombre", $usuario->getUsuario());
        $fecha = new \DateTime();
        Sesion::setDatos("usuario_tiempo_sesion", $fecha);
    }

    public static function cerrarSesion() {
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }

    public static function sesionCaducada() {
        if (self::tiempoSesion() >= \app\Configuracion::$tiempoMaximoSesion or self::tiempoInactividad() >= \app\Configuracion::$tiempoInactividad) {
            self::cerrarSesion();
            return true;
        } else {
            return false;
        }
    }

    public static function estaActivo() {
        $usuario = self::obtenerUsuario();
        if ($usuario->getActivo()) {
            return true;
        } else {
            return false;
        }
    }

    public static function rol() {
        
    }

}
