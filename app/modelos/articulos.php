<?php
namespace app\modelos;

class articulos extends \nucleo\Clase_base {
    
    private $id;
    private $nombre;
    private $categoria_id;
    private $categoria;
    private $precio;
    private $cantidad;
    
    function __construct($id=0, $nombre="", $categoria_id=0, $precio=0, $cantidad=0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria_id = $categoria_id;
        $this->precio = $precio;
        $this->cantidad = $cantidad;
        $this->categoria = new \app\modelos\categoria();
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getCategoria_id() {
        return $this->categoria_id;
    }

    public function getCategoria() {
        return $this->categoria;
    }
    
    public function getPrecio() {
        return $this->precio;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setCategoria_id($categoria) {
        $this->categoria_id = $categoria;
    }
    
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }
    
    public function __toString() {
        return $this->nombre;
    }
    
//    public function obtenerArticulos(){
//        $articulos = $this->select("*")
//                ->from("articulos a")
//                ->join("categoria c", "c.id = a.categoria_id")
//                ->orderBy("a.nombre")
//                ->ejecutar();
//        
//        return $articulos;
//    }
    
}