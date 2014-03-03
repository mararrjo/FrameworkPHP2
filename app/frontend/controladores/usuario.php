<?php
namespace app\frontend\controladores;

class usuario extends \nucleo\Controlador {
    
    public function validarUsuario($request){
        $usuario = \nucleo\Usuarios::validarUsuario($request);
        if($usuario){
            \nucleo\Usuarios::iniciarSesion($usuario);
            \nucleo\Sesion::setFlash("Bienvenido Amo {$usuario->getUsuario()}!");
        }
        \nucleo\URL::ir("articulos/listado");
    }
    
    public function cerrarSesion(){
        \nucleo\Usuarios::cerrarSesion();
        \nucleo\URL::ir("articulos/listado");
    }
    
//    public function tiempoInactividad(){
//        $this->renderizar();
//    }
    
}