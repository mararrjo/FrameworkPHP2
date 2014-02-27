<?php

namespace app\controladores;

use nucleo\Controlador;
use app\modelos\form_articulos;

class fruteria extends Controlador {

    public function listado() {
        $articulos = new \app\modelos\articulos();
        
        $listaArticulos = $this->getBaseDatos("articulos")->select()
                ->from("articulos a")
                ->join("categoria c", "c.id = a.categoria_id")
//                ->where("a.id = 2")
//                ->orderBy("a.nombre")
                ->ejecutar();
        
//        $listaArticulos = $this->getBaseDatos("articulos")->findAll("order by nombre");
        
//        $listaArticulos = $articulos->obtenerArticulos();
        
        $this->renderizar(array("articulos" => $listaArticulos));
    }

    public function mostrar($request, $id = 0) {
        $articulo = new \app\modelos\articulos();
        
//        $articulo = $this->getBaseDatos("articulos")->select()
//                ->where("id = $id")
//                ->ejecutar();
        
        $articulo = $this->getBaseDatos("articulos")->findById($id);
        $this->renderizar(array("articulo" => $articulo));
    }

    public function ver($request, $id = 0) {
        $this->redireccionar("fruteria", "mostrar", array("id" => $id));
    }

    public function anadir() {
        $form = new form_articulos();
        $this->renderizar(array("form" => $form->renderizarFormulario()));
    }

    public function anadir_validar($request) {
        $articulo = new \app\modelos\articulos();
        $formulario = new form_articulos($articulo);
        $formulario->procesarFormulario($request, $articulo);
        if ($formulario->esValido()) {
            $articulo->persistir();
            \nucleo\Sesion::setFlash("Se ha añadido el articulo " . $articulo->getNombre());
            $this->redireccionar("fruteria", "listado");
        } else {
            $this->renderizarPlantilla("plantilla", "fruteria", "anadir", array("form" => $formulario->renderizarFormulario()));
        }
    }

    public function modificar($request, $id = 0) {
        $articulo = new \app\modelos\articulos();
        $articulo->obtenerPorId($id);
        $form = new form_articulos($articulo);
        $this->renderizar(array("form" => $form->renderizarFormulario()));
    }

    public function modificar_validar($request) {
        $articulo = new \app\modelos\articulos();
        $formulario = new form_articulos($articulo);
        $formulario->procesarFormulario($request, $articulo);
        if ($formulario->esValido()) {
            $articulo->persistir();
            \nucleo\Sesion::setFlash("Se ha modificado el articulo " . $articulo->getNombre());
            $this->redireccionar("fruteria", "listado");
        } else {
            $this->renderizarPlantilla("plantilla", "fruteria", "modificar", array("form" => $formulario->renderizarFormulario()));
        }
    }

    public function eliminar($request, $id = 0) {
        $articulo = new \app\modelos\articulos();
        $articulo->obtenerPorId($id);
        $this->renderizar(array("articulo" => $articulo));
    }

    public function eliminar_validar($request) {
        if (isset($request["id"])) {
            $id = $request["id"];
            $articulo = new \app\modelos\articulos();
            $articulo->obtenerPorId($id);
            $articulo->delete();
            \nucleo\Sesion::setFlash("Se ha eliminado el articulo " . $articulo->getNombre());
            $this->redireccionar("fruteria", "listado");
        } else {
            $this->redireccionar("fruteria", "listado");
        }
    }

    public function anadirCategoria() {
        $form = new \app\modelos\form_categorias();
        $this->renderizarPlantilla("plantilla", "fruteria", "anadirCategorias", array("form" => $form->renderizarFormulario()));
    }

    public function anadirCategoria_validar($request) {
        $categoria = new \app\modelos\categoria();
        $form = new \app\modelos\form_categorias($categoria);
        $form->procesarFormulario($request, $categoria);
        if ($form->esValido()) {
            $categoria->persistir();
            \nucleo\Sesion::setFlash("Se ha añadido la categoria " . $categoria->getNombre());
        }
        $this->redireccionar("fruteria", "listado");
    }

}
