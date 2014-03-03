<?php

namespace nucleo {

    class Utiles {

        public static function obtenerTablaSinNamespace($obj) {
            $tabla = get_class($obj);
            $arrayTabla = str_getcsv($tabla, "\\");
            $tabla = $arrayTabla[count($arrayTabla) - 1];
            return $tabla;
        }

        public static function obtenerTablaConNamespace($obj) {
            return get_class($obj);
        }

        public static function nuevoObjeto($tabla) {
//            echo $tabla;
            $tablaNamespace = "\\app\\". Distribuidor::getAplicacion()."\\modelos\\" . $tabla;
            if (class_exists($tablaNamespace, false)) {
                return new $tablaNamespace();
            } else {
                $tablaNamespace = "\\nucleo\\" . $tabla;
//                $tablaNamespace = $tabla;
                if (class_exists($tablaNamespace, false)) {
                    return new $tablaNamespace();
                }
            }
            return null;
        }

    }

}

namespace {
    function newObjeto($tabla){
        return nucleo\Utiles::nuevoObjeto($tabla);
    }
}