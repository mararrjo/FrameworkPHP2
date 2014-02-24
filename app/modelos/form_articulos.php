<?php

namespace app\modelos;

use nucleo\Formularios;

class form_articulos extends Formularios {

    public function configuracion() {
        $this->setCampos(array(
            "nombre" => array("type" => "text", "maxLength" => 20),
            "categoria_id" => array("type" =>"seleccion", 
                "label"=>"Elige categoria", 
                "expandido"=>false,
                "multiple"=>false,
//                "opciones" => array("Fruta","Verdura","Legumbre")),
                "opciones" => "categoria"),
            "Precio" => "text",
            "Cantidad" => "number",
            "Aceptar" => array("type"=>"submit","class"=>"boton"),
            "Vaciar" => array("type" => "reset", "label" => "Vaciar campos", "class"=>"boton")
        ));
        
        $this->setValidaciones(array(
            "nombre" => "requerido",
            "Precio" => "decimal",
            "Cantidad"=>"entero_positivo"));
        
    }

}
