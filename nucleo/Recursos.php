<?php
namespace nucleo;

class Recursos {

    /**
     * Devuelve las etiquetas html con el enlace externo
     * 
     * @param string $controlador Selecciona el css del controlador pasado, si 
     * se omite se usara el css general
     * @param string $hoja Selecciona el fichero css, no hace falta poner la extension
     * @return string Etiqueta html
     */
    public static function css($controlador="", $hoja="") {

        if (!$hoja) {
            $hoja = $controlador;
            $controlador = "";
        }
        $link = "<link type='text/css' rel='stylesheet' href='";
        if($controlador)
            $href = URL . "recursos/$controlador/css/";
        else
            $href = URL . "recursos/css/";
        
        return $link . $href . $hoja . ".css' />";
    }

    /**
     * Devuelve las etiquetas html con el enlace externo
     * 
     * @param string $controlador Selecciona el js del controlador pasado, si 
     * se omite se usara el js general
     * @param string $hoja Selecciona el fichero jx, no hace falta poner la extension
     * @return string Etiqueta html
     */
    public static function js($controlador="", $js="") {

        if (!$js) {
            $js = $controlador;
            $controlador = "";
        }
        $link = "<script type='text/javascript' src='";
        
        if($controlador)
            $src = URL . "recursos/$controlador/js/";
        else
            $src = URL . "recursos/js/";
        
        return $link . $src . $js . ".js' ></script>";
    }
    
    public static function imagen($controlador="",$img="", array $parametros = array()){
        
        if (!$img) {
            $img = $controlador;
            $controlador = "";
        }
        if($parametros){
        $string_parametros = "";
        foreach($parametros as $nombre => $parametro){
            $string_parametros .= $nombre."='".$parametro."' ";
        }
        $link = "<img $string_parametros src='";
        }else{
            $link = "<img src='";
        }
        
        if($controlador)
            $src = URL . "recursos/$controlador/imagenes/";
        else
            $src = URL . "recursos/imagenes/";
        
        return $link . $src . $img."' >";
    }
    
}