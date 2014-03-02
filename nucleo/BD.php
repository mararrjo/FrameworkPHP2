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
    private $set;

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

        if ($this->tipo == "SELECT") {
            return $this->procesarResultado($resultado);
        }

        return $resultado;
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
//                $tableNamespace = "\\app\\modelos\\" . $nombreTabla;
//                if (class_exists($tableNamespace, false)) {
//                    $obj = new $tableNamespace();
                $obj = newObjeto($nombreTabla);
                if ($obj) {
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

//    public function findAll($clausulas = null) {
//        $tabla = get_class($this);
//        $tabla = str_getcsv($tabla, "\\")[2];
//        $query = "SELECT * FROM $tabla $clausulas";
//        $resultado = $this->query($query);
//        $lista = array();
//        foreach ($resultado as $fila) {
//            $lista[$fila["id"]] = $fila;
//        }
//        return $lista;
//    }
//
//    public function findById($id) {
//        $tabla = get_class($this);
//        $tabla = str_getcsv($tabla, "\\")[2];
//        $query = "SELECT * FROM $tabla WHERE id=$id";
//        $resultado = $this->query($query);
//        $lista = array();
//        foreach ($resultado as $fila) {
//            $lista[$fila["id"]] = $fila;
//        }
//        return array_pop($lista);
//    }

    public function desc() {
        $this->tipo = "DESC";
        $this->from = Utiles::obtenerTablaSinNamespace($this);
        return $this->query("$this->tipo $this->from");
    }

    public function select($columnas = null) {
        $this->tipo = "SELECT";
        $this->columnas = $columnas;
        $this->from = Utiles::obtenerTablaSinNamespace($this);
        return $this;
    }

    public function delete() {
        $this->tipo = "DELETE";
        $this->from = Utiles::obtenerTablaSinNamespace($this);
        $this->where = "id={$this->getId()}";
        return $this->ejecutar();
    }

    public function insert() {
        $this->tipo = "INSERT";
        $tabla = get_class($this);
        $tabla = str_getcsv($tabla, "\\")[2];
        $this->from = Utiles::obtenerTablaSinNamespace($this);
        $this->set = $this->obtenerStringColumnas();
        return $this->ejecutar();
    }

    public function update() {
        $this->tipo = "UPDATE";
        $tabla = get_class($this);
        $tabla = str_getcsv($tabla, "\\")[2];
        $this->from = Utiles::obtenerTablaSinNamespace($this);
        $this->set = $this->obtenerStringColumnas();
        $this->where = "id={$this->getId()}";
        return $this->ejecutar();
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
        switch ($this->tipo) {
            case "SELECT":
                $query = $this->tipo . " ";
                $this->columnas ? $query .= "$this->columnas " : $query .= " * ";
                $query .= "FROM $this->from $this->alias";
                $this->tablaJoin ? $query .= " LEFT JOIN $this->tablaJoin ON $this->on" : null;
                $this->where ? $query .= " WHERE $this->where" : null;
                $this->orden ? $query .= " ORDER BY $this->orden" : null;
                $this->grupo ? $query .= " GROUP BY $this->grupo" : null;
                return $this->query($query);
            case "UPDATE":
                $query = $this->tipo . " ";
                $query .= $this->from;
                $this->set ? $query .= " SET $this->set" : null;
                $this->where ? $query .= " WHERE $this->where" : null;
                return $this->query($query);
            case "INSERT":
                $query = $this->tipo . " ";
                $query .= "INTO $this->from";
                $this->set ? $query .= " SET $this->set" : null;
                return $this->query($query);
            case "DELETE":
                $query = $this->tipo . " ";
                $query .= "FROM $this->from";
                $this->where ? $query .= " WHERE $this->where" : null;
                return $this->query($query);
        }
    }

    public function join($tablaJoin, $on) {
        $this->tablaJoin = $tablaJoin;
        $this->on = $on;
        return $this;
    }

    public function set($columnas) {

        if (is_array($columnas)) {
            $cadenaColumnas = "";
            foreach ($columnas as $nombreColumna => $valor) {
                $cadenaColumnas .= "$nombreColumna=$valor,";
            }

            $this->set = $cadenaColumnas;
        } else {
            $this->set = $columnas;
        }

        return $this;
    }

}
