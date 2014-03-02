<?php
namespace nucleo;

class usuario extends Clase_base{
    private $id;
    private $usuario;
    private $password;
    private $correo;
    private $activo;
    private $rol;
    
    public function getId() {
        return $this->id;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }
    
}