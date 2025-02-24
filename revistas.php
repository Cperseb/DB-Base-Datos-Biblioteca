<?php

include_once("query.php");
include_once("documentos.php");

class Revista extends Documentos {
    
    private $ISBN;
    private $Frecuencia;
    private $Titulo;

    function __construct($ISBN, $Frecuencia){
        parent::__construct();
        $this->ISBN = $ISBN;
        $this->Frecuencia = $Frecuencia;
    }

    function getFrecuencia() {
        return $this->Frecuencia;
    }

    function setFrecuencia($Frecuencia) {
        $this->Frecuencia = $Frecuencia;
    }

    function getTitulo() {
        return $this->Titulo;
    }

    function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    function getISBN() {
        return $this->ISBN;
    }

    function setISBN($ISBN) {
        $this->ISBN = $ISBN;
    }
    public function buscarFrecuenciaPorISBN($ISBN) {
        $query = new Query();
        $condition = "ISBN = '$ISBN'";
        $resultado = $query->select('revistas', $condition);
        
        if ($resultado && count($resultado) > 0) {
            return $resultado[0]['Frecuencia'];
        }
        return false;
    }

}

