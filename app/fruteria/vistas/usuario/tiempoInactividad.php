<?php
//namespace {
include_once '../../../nucleo/Usuarios.php';
include_once '../../../nucleo/Sesion.php';
echo nucleo\Usuarios::tiempoInactividad()." minutos";
//}