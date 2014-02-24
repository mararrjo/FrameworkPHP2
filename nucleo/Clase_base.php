<?php

namespace nucleo;

class Clase_base extends \nucleo\BD {

    /**
     * Devuelve un array con las propiedades del objeto y sus valores, que tengan
     * su método get. Se le puede pasar un array con los campos que se quieren
     * obtener.
     * 
     * @param array $campos Es un array con los campos que se quieren obtener.
     * Debe ser de la forma array("campo1"=>"","campo2"=>""). Si no se aporta se
     * obtendras todos los campos del objeto.
     * @return array Array con las propiedades del objeto.
     */
    public function obtenerArrayCampos(array $campos = array()) {
        if ($campos == null) {
            $metodos = get_class_methods(get_class($this));
            $campos = array();
            foreach ($metodos as $metodo) {
                if (stristr($metodo, "get")) {
                    $campos[strtolower(str_replace("get", "", $metodo))] = $this->$metodo();
                }
            }
            return $campos;
        } else {
            $arrayDatos = array();
            foreach ($campos as $clave => $tipo) {
                $atributo = "get" . strtoupper(substr($clave, 0, 1)) . substr($clave, 1);
                if (method_exists($this, $atributo)) {
                    $arrayDatos[$clave] = $this->$atributo();
                }
            }
            $arrayDatos["id"] = $this->getId();
            return $arrayDatos;
        }
    }

    public function obtenerStringCampos() {
        //Extraigo las columnas que tiene la tabla en la base de datos
        $clase = get_class($this);
        $clase = str_getcsv($clase, "\\")[2];
        $filas = $this->select("desc " . $clase);
        $camposTabla = array();
        foreach ($filas as $fila) {
            $camposTabla[$fila->Field] = $fila->Field . " ";
        }

        //Extraigo todos los metodos de la clase. Pero quedandome solo con los
        // que empiezan con get.
        $metodos = get_class_methods(get_class($this));
        $campos = "";
        foreach ($metodos as $indice => $metodo) {
            if (stristr($metodo, "get")) {
                //Si el nombre de la propiedad del metodo get coincide con el
                // de la columna de la base de datos lo añado al string.
                $propiedad = strtolower(str_replace("get", "", $metodo));
                if (array_key_exists($propiedad, $camposTabla)) {
                    $valor = $this->$metodo();
                    if (is_array($valor)) {
                        $valor = serialize($valor);
                    }
                    if (!is_numeric($valor)) {
                        $valor = "'$valor'";
                    }
                    $campos .= strtolower(str_replace("get", "", $metodo)) . " = " . $valor . ", ";
                }
            }
        }
        $campos = substr($campos, 0, count($campos) - 3);
        ;
        return $campos;
    }

    /**
     * Guarda los datos del array en el objeto en sus respectivos campos.
     * 
     * @param array $datos El array con los campos y sus valores. De la forma
     * array("campo1"=>"valor1","campo2"=>"valor2")
     */
    public function guardarDatosDeArray($datos) {
        //Recorro todo el array para ir objeniendo la columna y su valor
        foreach ($datos as $campo => $valor) {
            //Pongo en mayusculas la primera letra del nombre de la columna.
            $campo = strtoupper(substr($campo, 0, 1)) . substr($campo, 1);

            //Obtengo el nombre del metodo
            $metodo = "get" . $campo;
//            $es_array = $this->$metodo();
            //Comprueba si hay alguna clave ajena mirando si un campo contiene
            // _id o id_
            if (stristr($campo, "_id") or stristr($campo, "id_")) {
                $tabla = strtolower(str_replace("_id", "", $campo));
                $clase = "\\app\\modelos\\" . $tabla;
                $objeto = new $clase();
                $metodoTabla = strtoupper(substr($tabla, 0, 1)) . substr($tabla, 1);
                $metodoTabla = "set" . $metodoTabla;
                if (method_exists($this, $metodoTabla)) {
                    $objeto->findById($valor);
                    $this->$metodoTabla($objeto);
                }
            }

//            if (is_array($es_array) and !is_array($valor)) {
//                $valor = unserialize($valor);
//            }
            //Asigno los valores en los campos usando los setters
            $metodo = "set" . $campo;
            if (method_exists($this, $metodo)) {
                $this->$metodo($valor);
            }
        }
        return clone $this;
    }

    /**
     * Transforma los datos de la propiedad del objeto de un tipo simple a un
     * objeto o array de objetos a los que hace refererencia. 
     * Un ejemplo para obtener un objeto simple:
     *  $empleado->cambiarTipoPropiedadPorObjetos("deptartamento","dept","deptno","informatica")
     * Ejemplo para obtener un array de objetos:
     *  $empleado->cambiarTipoPropiedadPorObjetos("listaDeptartamentos","dept","deptno",array("informatica","matematicas","tecnologia"))
     * 
     * @param string $campoObjeto Propiedad del objeto
     * @param string $tabla Nombre de la tabla de la que se quieren obtener los datos
     * @param string $campoTabla Propiedad de la tabla de la que se quiere obtener los datos
     * @param mixed $datos Valor o valores que se pasan para obtener los datos de la tabla
     */
    public function cambiarTipoPropiedadPorObjetos($campoObjeto, $tabla, $campoTabla, $datos) {

        $bd = new \nucleo\BD();
        $objetos = $bd->obtenerPorColumna($tabla, array($campoTabla => $datos));
        $metodo = "set" . strtoupper(substr($campoObjeto, 0, 1)) . substr($campoObjeto, 1);
//        echo "$tabla $campoTabla ";
//        print_r($datos);
        $this->$metodo($objetos);
    }

    public function reasignarObjeto($obj) {
        $this->guardarDatosDeArray($obj->obtenerArrayCampos());
    }

    public function ejecutar() {
        $lista = parent::ejecutar();
        if (count($lista) == 1) {
            return $this->guardarDatosDeArray(array_pop($lista));
        } else {
            $objetos = array();
            foreach ($lista as $fila) {
                $objetos[$fila["id"]] = $this->guardarDatosDeArray($fila);
            }
            return $objetos;
        }
    }

    public function findAll($clausulas = null) {
        $lista = parent::findAll($clausulas);
        $objetos = array();
        foreach ($lista as $fila) {
            $objetos[$fila["id"]] = $this->guardarDatosDeArray($fila);
        }
        return $objetos;
    }

    public function findById($id) {
        $resultado = parent::findById($id);
        return $this->guardarDatosDeArray($resultado);
    }

}
