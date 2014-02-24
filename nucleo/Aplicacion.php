<?php
namespace nucleo;

class Aplicacion {
    
    public $info = "datos";
    public function __construct() {
        
        $sesion = new \nucleo\Sesion();
        
        \nucleo\Distribuidor::mostrarVista();
    
        
    }
    
}
