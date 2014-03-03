<?php
namespace app\fruteria\controladores;

class usuario extends \nucleo\Controlador {
    
    public function validarUsuario($request){
        $usuario = \nucleo\Usuarios::validarUsuario($request);
        if($usuario){
            \nucleo\Usuarios::iniciarSesion($usuario);
            \nucleo\Sesion::setFlash("Bienvenido Amo {$usuario->getUsuario()}!");
        }
        \nucleo\URL::ir("fruteria/listado");
    }
    
    public function cerrarSesion(){
        \nucleo\Usuarios::cerrarSesion();
        \nucleo\URL::ir("fruteria/listado");
    }
    
//    public function tiempoInactividad(){
//        $this->renderizar();
//    }
    
}