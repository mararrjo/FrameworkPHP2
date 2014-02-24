<?php

namespace nucleo;

class Widgets {

    private static function crearInput($tipo, $campo, $value = "", array $parametros = NULL) {
        if ($parametros) {
            if (isset($parametros["label"])) {
                $label = $parametros["label"];
                unset($parametros["label"]);
            } else {
                $label = $campo;
//                $campo = strtolower($campo);
            }
            if(isset($parametros["class"])){
                $clase = $parametros["class"];
                unset($parametros["class"]);
            }else{
                $clase = "";
            }
            $string_parametros = "";
            foreach ($parametros as $parametro => $valor) {
                $string_parametros .= $parametro . "='" . $valor . "' ";
            }
            return $label . ": <input " . $string_parametros . " name='$campo' id='$campo' class='input_" . $campo . " $clase' value='" . $value . "'>";
        } else {
            $label = $campo;
//            $campo = strtolower($campo);
            return $label . ": <input type='$tipo' name='$campo' id='$campo' class='input_" . $campo . "' value='" . $value . "'>";
        }
    }

    private static function crearBoton($tipo, $campo, $value = "", array $parametros = NULL) {
        if ($parametros) {
            if (isset($parametros["label"])) {
                $label = $parametros["label"];
                unset($parametros["label"]);
            } else {
                $label = $campo;
//                $campo = strtolower($campo);
            }
            if(isset($parametros["class"])){
                $clase = $parametros["class"];
                unset($parametros["class"]);
            }else{
                $clase = "";
            }
            $string_parametros = "";
            foreach ($parametros as $parametro => $valor) {
                $string_parametros .= $parametro . "='" . $valor . "' ";
            }
            return "<input type='$tipo' name='b_$campo' id='b_$campo' class='input_" . $campo . " $clase' value='" . $label . "' $string_parametros>";
        } else {
//            $campo = strtolower($campo);
            return "<input type='$tipo' name='b_$campo' id='b_$campo' class='input_" . $campo . "' value='" . $value . "'>";
        }
    }

    public static function hidden($campo, $value = "", array $parametros = NULL) {
        return "<input type='hidden' name='$campo' id='$campo' value='$value' >";
    }

    public static function text($campo, $value = "", array $parametros = NULL) {
        return self::crearInput("text", $campo, $value, $parametros);
    }

    public static function number($campo, $value = "", array $parametros = NULL) {
        return self::crearInput("number", $campo, $value, $parametros);
    }

    public static function button($campo, $value = "", array $parametros = NULL) {
        return self::crearBoton("button", $campo, $campo, $parametros);
    }

    public static function submit($campo, $value = "", array $parametros = NULL) {
        return self::crearBoton("submit", $campo, $campo, $parametros);
    }
    
    public static function reset($campo, $value = "", array $parametros = NULL){
        return self::crearBoton("reset", $campo, $campo, $parametros);
    }

    public static function seleccion($campo, $value = "", array $parametros = null) {
        if (!is_array($parametros["opciones"])) {
            $opciones = array();
//            $bd = new \nucleo\BD();
            $clase = "\\app\\modelos\\".$parametros["opciones"];
            $objeto = new $clase();
            $objetos = $objeto->obtenerTodo();
            foreach ($objetos as $objeto) {
                $opciones[$objeto->getId()] = $objeto;
            }
            $parametros["opciones"] = $opciones;
        }else{
            $opciones = array();
            foreach ($parametros["opciones"] as $opcion) {
                $opciones[$opcion] = $opcion;
            }
            $parametros["opciones"] = $opciones;
        }
        $expandido = isset($parametros["expandido"]) ? ($parametros["expandido"] ? "expandido" : "") : "";
        if ($expandido) {
            return self::radio($campo, $value, $parametros);
        } else {
            return self::select($campo, $value, $parametros);
        }
    }

    public static function select($campo, $value = "", array $parametros = null) {
        if (isset($parametros["label"])) {
            $label = $parametros["label"];
            unset($parametros["label"]);
        } else {
            $label = $campo;
        }
        if(isset($parametros["class"])){
                $clase = $parametros["class"];
                unset($parametros["class"]);
            }else{
                $clase = "";
            }
//        $campo = strtolower($campo);
        $input = "";
        $input .= $label . ":<br>";
        $input .= isset($parametros["multiple"]) ?
                ($parametros["multiple"] ? "<select class='input_$campo $clase' id='$campo' name='" . $campo . "[]' multiple>" : "<select class='input_$campo $clase' id='$campo' name='$campo'>") : "<select name='$campo'>";
        foreach ($parametros["opciones"] as $id => $opcion) {
            $selected = "";
            if (is_array($value)){
                if(is_object(array_pop($value))){
                    $cosas = array();
                    foreach($value as $id => $obj){
                        $cosas[$id] = $obj->getId();
                    }
                    $value = $cosas;
                }
                if (in_array($id, $value)){
                    $selected = "selected";
                }
            }else {
                if (strtolower($value) == strtolower($id))
                    $selected = "selected";
            }
            $input .= "<option value='$id' $selected>$opcion</option>";
        }
        $input .= "</select>";
        return $input;
    }

    public static function radio($campo, $value = "", array $parametros = NULL) {
        if (isset($parametros["label"])) {
            $label = $parametros["label"];
            unset($parametros["label"]);
        } else {
            $label = $campo;
        }
        if(isset($parametros["class"])){
                $clase = $parametros["class"];
                unset($parametros["class"]);
            }else{
                $clase = "";
            }
//        $campo = strtolower($campo);
        $multiple = isset($parametros["multiple"]) ? ($parametros["multiple"] ? $campo.="[]" : "") : "";
        $input = "";
        $input .= $label . ":<br>";
        foreach ($parametros["opciones"] as $id => $opcion) {
            $checked = "";
            if (is_array($value)) {
                if(is_object(array_values($value)[0])){
                    $cosas = array();
                    foreach($value as $id => $obj){
                        $cosas[$id] = $id;
                    }
                    $value = $cosas;
                }
                if (in_array($id, $value))
                    $checked = "checked";
            }else {
                if (strtolower($value) == strtolower($id))
                    $checked = "checked";
            }
            if (!$multiple)
                $input .= "<input type='radio' name='$campo' id='$campo' class='input_$campo $clase' value='$id' $checked>$opcion";
            else
                $input .= "<input type='checkbox' name='$campo' id='$campo' class='input_$campo $clase' value='$id' $checked>$opcion";
        }
        return $input;
    }

}
