<?php
namespace nucleo;

class Autoload {

    public function __construct() {
        spl_autoload_register(array($this, 'cargarFicheros'));   
    }
    
    public function cargarFicheros($clase) {
        $nombre_clase = str_replace("\\", "/", $clase);
        require_once $nombre_clase.'.php';
    }

}
