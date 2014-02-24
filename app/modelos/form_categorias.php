<?php
namespace app\modelos;

class form_categorias extends \nucleo\Formularios {
    
    public function configuracion() {
        
        $this->setCampos(array("Nombre"=>"text","Aceptar"=>"submit"));
        
    }
    
}