<?php

namespace nucleo;

class BD implements InterfazBD {

    private static $conexion = null;
    private $tipo;
    private $from;
    private $alias;
    private $where;
    private $orden;
    private $grupo;

    public function conectar() {
        self::$conexion = mysqli_connect(\app\Configuracion::$server, \app\Configuracion::$user, \app\Configuracion::$password, \app\Configuracion::$dataBase);
        if(!self::$conexion){
            URL::generarError("Error en la conexión");
        }
        return self::$conexion;
    }

    public function desconectar() {
        return mysqli_close(self::$conexion);
    }

    public function query($query) {
        $this->conectar();
        $resultado = mysqli_query(self::$conexion, $query);
        $this->desconectar(self::$conexion);
        
        if(!$resultado){
            URL::generarError("No se ha obtenido ningun resultado");
        }
        
        if(!$this->tipo){
            return $resultado;
        }
        
        if($this->tipo=="SELECT"){
            return $this->resultadoToArray($resultado);
        }
    }
    
    private function resultadoToArray($result){
        $lista = array();
        foreach($result as $fila){
            $lista[$fila["id"]] = $fila;
        }
        return $lista;
    }

    public function findAll($clausulas = null) {
        $tabla = get_class($this);
        $tabla = str_getcsv($tabla, "\\")[2];
        $query = "SELECT * FROM $tabla $clausulas";
        $resultado = $this->query($query);
        $lista = array();
        foreach ($resultado as $fila) {
            $lista[$fila["id"]] = $fila;
        }
        return $lista;
    }

    public function findById($id){
        $tabla = get_class($this);
        $tabla = str_getcsv($tabla, "\\")[2];
        $query = "SELECT * FROM $tabla WHERE id=$id";
        $resultado = $this->query($query);
        $lista = array();
        foreach ($resultado as $fila) {
            $lista[$fila["id"]] = $fila;
        }
        return array_pop($lista);
    }
    
    public function select($query = null) {
        $this->tipo = "SELECT";
        $tabla = get_class($this);
        $tabla = str_getcsv($tabla, "\\")[2];
        $this->from = $tabla;
        return $this;
    }

    public function delete() {
        
    }

    public function insert() {
        
    }

    public function update() {
        
    }

    public function from($tabla) {
        if (stristr(" ", $tabla)) {
            $this->alias = str_getcsv($tabla, " ")[1];
            $this->from = str_getcsv($tabla, " ")[0];
        } else {
            $this->from = $tabla;
        }
        return $this;
    }

    public function where($where) {
        $this->where = $where;
        return $this;
    }

    public function orderBy($orden) {
        $this->orden = $orden;
        return $this;
    }
    
    public function groupBy($grupo) {
        $this->grupo = $grupo;
        return $this;
    }

    public function ejecutar() {
        if ($this->tipo == "SELECT") {
            $query = "$this->tipo * FROM $this->from $this->alias";
            $this->where ? $query .= " WHERE $this->where" : null;
            $this->orden ? $query .= " ORDER BY $this->orden" : null;
            $this->grupo ? $query .= " GROUP BY $this->grupo" : null;
            return $this->query($query);
        }
    }

}
