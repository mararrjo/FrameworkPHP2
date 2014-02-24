<?php
namespace nucleo;

interface InterfazBD {
    public function conectar();
    public function query($query);
    public function select($query=null);
    public function insert();
    public function update();
    public function delete();
    public function desconectar();
}