<?php
namespace app\fruteria\controladores;

class errores extends \nucleo\Controlador {
    
    public function index(){
        $this->renderizar(array("error" => \nucleo\Sesion::getMensaje("error")));
    }
    
}