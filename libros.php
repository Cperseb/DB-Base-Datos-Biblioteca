<?php

include_once("query.php");
include_once("documentos.php");

class Libros extends Documentos {
    
    private $ISBN;
    private $NumPaginas;
    private $titulo;

    function __construct($ISBN, $NumPaginas, $titulo) {
        parent::__construct();
        $this->ISBN = $ISBN;
        $this->NumPaginas = $NumPaginas;
    }

    function getNumeroPaginas(){
        return $this->NumPaginas;
    }

    function setNumeroPaginas($NumPaginas) {
        $this->NumPaginas = $NumPaginas;
    }

    function getISBN() {
        return $this->ISBN;
    }

    function setISBN($ISBN) {
        $this->ISBN = $ISBN;
    }
    public function buscarPaginasPorISBN($ISBN) {
        $query = new Query();
        $condition = "ISBN = '$ISBN'";
        $resultado = $query->select('libros', $condition);
        
        if ($resultado && count($resultado) > 0) {
            return $resultado[0]['NumPaginas'];
        }
        return false;
    }

}

