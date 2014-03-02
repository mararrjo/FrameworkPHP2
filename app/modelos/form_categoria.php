<?php
namespace app\modelos;

class form_categoria extends \nucleo\Formularios {
    
    public function configuracion() {
        
        $this->setCampos(array("Nombre"=>"text","Aceptar"=>"submit"));
        
    }
    
}