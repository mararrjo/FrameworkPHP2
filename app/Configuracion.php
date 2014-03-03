<?php
namespace app;

class Configuracion extends \nucleo\Configuracion{
    
    public static $vista_plantilla = "plantilla";
    public static $aplicacion = "fruteria";
    public static $controlador_defecto = "articulos";
    public static $metodo_defecto = "listado";
    public static $forzadoBd = false;
    public static $dataBase = "fruteria2";
    public static $server = "localhost";
    public static $port = "3306";
    public static $user = "root";
    public static $password = "";
    public static $tiempoMaximoSesion = 30;
    public static $tiempoInactividad = 5;
    
}