<?php

namespace app\fruteria\controladores;

use nucleo\Controlador;
use app\fruteria\modelos\form_articulos;

class articulos extends Controlador {

    public function listado() {
        $articulos = new \app\fruteria\modelos\articulos();
        $listaArticulos = $articulos->obtenerArticulos();
        $this->renderizar(array("articulos" => $listaArticulos));
    }

    public function mostrar($request, $id = 0) {
        $articulo = new \app\fruteria\modelos\articulos();
        $articulo = $articulo->obtenerArticulo($id);
        $this->renderizar(array("articulo" => $articulo));
    }

    public function anadir() {
        $form = new form_articulos();
        $this->renderizar(array("form" => $form->renderizarFormulario()));
    }

    public function anadir_validar($request) {
        $articulo = new \app\fruteria\modelos\articulos();
        $formulario = new form_articulos($articulo);
        $formulario->procesarFormulario($request, $articulo);
        if ($formulario->esValido()) {
            $articulo->insert();
            \nucleo\Sesion::setFlash("Se ha añadido el articulo " . $articulo->getNombre());
            $this->redireccionar("articulos", "listado");
        } else {
            $this->renderizarPlantilla("plantilla", "articulos", "anadir", array("form" => $formulario->renderizarFormulario()));
        }
    }

    public function modificar($request, $id = 0) {
        $articulo = new \app\fruteria\modelos\articulos();
        $articulo = $articulo->obtenerArticulo($id);
        $form = new form_articulos($articulo);
        $this->renderizar(array("form" => $form->renderizarFormulario()));
    }

    public function modificar_validar($request) {
        $articulo = new \app\fruteria\modelos\articulos();
        $formulario = new form_articulos($articulo);
        $formulario->procesarFormulario($request, $articulo);
        if ($formulario->esValido()) {
            $articulo->update();
            \nucleo\Sesion::setFlash("Se ha modificado el articulo " . $articulo->getNombre());
            $this->redireccionar("articulos", "listado");
        } else {
            $this->renderizarPlantilla("plantilla", "articulos", "modificar", array("form" => $formulario->renderizarFormulario()));
        }
    }

    public function eliminar($request, $id = 0) {
        $articulo = new \app\fruteria\modelos\articulos();
        $articulo = $articulo->obtenerArticulo($id);
        $this->renderizar(array("articulo" => $articulo));
    }

    public function eliminar_validar($request) {
        if (isset($request["id"])) {
            $id = $request["id"];
            $articulo = new \app\fruteria\modelos\articulos();
             $articulo = $articulo->obtenerArticulo($id);
            $articulo->delete();
            \nucleo\Sesion::setFlash("Se ha eliminado el articulo " . $articulo->getNombre());
            $this->redireccionar("articulos", "listado");
        } else {
            $this->redireccionar("articulos", "listado");
        }
    }

}
