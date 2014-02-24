<?php
namespace app\modelos;

class categoria extends \nucleo\Clase_base {
    
    private $id=0;
    private $nombre="";
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function __toString() {
        return $this->nombre;
    }
    
}