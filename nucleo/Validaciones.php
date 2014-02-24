<?php

namespace nucleo;

/**
 * Esta clase se encarga de comprobar que los datos recibidos cumplen los
 * requisitos de las validaciones.
 */
class Validaciones {

    /**
     * 
     * @param array $campos
     * @param array $valores
     * @return mixed True si todos los campos son validos. En caso de algun error
     * devuelve un array con los campos y sus errores.
     * @throws \Exception
     */
    public static function validar(array $campos, array $valores) {
        if ($campos and is_array($campos) and $valores and is_array($valores)) {
            $validaciones = new Validaciones();
            $valido = true;
            $errores = array();
            foreach ($campos as $campo => $validacion) {
                if(!array_key_exists($campo, $valores)){
                    throw new \Exception("No existe el campo $campo");
                }
                if (method_exists($validaciones, $validacion)) {
                    if (!$validaciones->$validacion($valores[$campo])) {
                        $valido = false;
                        $errores[$campo] = $validacion;
                    }
                } else {
                    throw new \Exception("No existe la validacion $validacion");
                }
            }
            if ($valido) {
                return true;
            } else {
                return $errores;
            }
        } else {
            return false;
        }
    }

    public function requerido($valor="") {
        return $valor ? true : false;
    }

    public function entero($valor="") {
        if (preg_match("/^\d+$/", $valor)) {
            return true;
        } else {
            return false;
        }
    }

    public function entero_positivo($valor="") {
        if (preg_match("/^\d+$/", $valor)) {
            if ($valor > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        };
    }

    public function decimal($valor="") {
        if (preg_match("/^\d+(\.\d+)?$/", $valor)) {
            return true;
        } else {
            return false;
        }
    }

}
