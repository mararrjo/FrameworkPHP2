<?php

namespace nucleo;

class BD implements InterfazBD {

    private static $conexion = null;
    private $tipo;
    private $columnas;
    private $from;
    private $alias;
    private $where;
    private $orden;
    private $grupo;
    private $tablaJoin;
    private $on;

    public function conectar() {
        self::$conexion = mysqli_connect(\app\Configuracion::$server, \app\Configuracion::$user, \app\Configuracion::$password, \app\Configuracion::$dataBase);
        if (!self::$conexion) {
            URL::generarError("Error en la conexion");
            throw new Exception("Error conexon");
        }
        return self::$conexion;
    }

    public function desconectar() {
        return mysqli_close(self::$conexion);
    }

    public function query($query) {
//        echo $query . "<br>";
        $this->conectar();
        $resultado = mysqli_query(self::$conexion, $query);
        $this->desconectar(self::$conexion);

        if (!$this->tipo) {
            return $resultado;
        }

        if ($this->tipo == "SELECT") {
            return $this->procesarResultado($resultado);
        }
    }

    private function procesarResultado($result) {

        //Obtengo la lista de columnas
        $lista = array();
        while ($fila = mysqli_fetch_field($result)) {
            $lista[$fila->orgtable][$fila->name] = "";
        }

        //Obtengo un array con todas las filas , tablas y columnas con sus valores
        $filas = 0;
        $listaProcesada = array();
        while ($fila = mysqli_fetch_array($result)) {
            $cont = 0;
            foreach ($lista as $nombreTabla => $tabla) {

                foreach ($tabla as $nombreColumna => $columna) {
                    $listaProcesada[$filas][$nombreTabla][$nombreColumna] = $fila[$cont];
                    $cont++;
                }
            }
            $filas++;
        }

        //Transformo el array de datos en un array de objetos.
        $filas = 0;
        $listaObjetos = array();
        foreach ($listaProcesada as $fila) {
            foreach ($fila as $nombreTabla => $tabla) {
                $tableNamespace = "\\app\\modelos\\" . $nombreTabla;
                if (class_exists($tableNamespace, false)) {
                    $obj = new $tableNamespace();
                    $obj->guardarDatosDeArray($tabla);
                    $listaObjetos[$nombreTabla][$filas] = $obj;
                }
            }
            $filas++;
        }


        if ($this->tablaJoin) {
            return $listaObjetos;
        } else {
            return array_pop($listaObjetos);
        }
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

    public function findById($id) {
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

    public function select($columnas = null) {
        $this->tipo = "SELECT";
        $tabla = get_class($this);
        $tabla = str_getcsv($tabla, "\\")[2];
        $this->columnas = $columnas;
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
            $query = $this->tipo . " ";
            $this->columnas ? $query .= "$this->columnas " : $query .= " * ";
            $query .= "FROM $this->from $this->alias";
            $this->tablaJoin ? $query .= " LEFT JOIN $this->tablaJoin ON $this->on" : null;
            $this->where ? $query .= " WHERE $this->where" : null;
            $this->orden ? $query .= " ORDER BY $this->orden" : null;
            $this->grupo ? $query .= " GROUP BY $this->grupo" : null;
            return $this->query($query);
        }
    }

    public function join($tablaJoin, $on) {
        $this->tablaJoin = $tablaJoin;
        $this->on = $on;
        return $this;
    }

}
