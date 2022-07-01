<?php
include_once "BaseDatos.php";
class Empresa {
    private $idempresa;
    private $enombre;
    private $edireccion;
    private $mensajeoperacion;


    public function __construct() {
        $this->idempresa = null;
        $this->enombre = null;
        $this->edireccion = null;
    }

    public function cargar($idempresa, $enombre, $edireccion) {
        $this->idempresa = $idempresa;
        $this->enombre = $enombre;
        $this->edireccion = $edireccion;
    }

    /**
     * Get the value of idempresa
     */
    public function getIdempresa() {
        return $this->idempresa;
    }

    /**
     * Set the value of idempresa
     *
     * @return  self
     */
    public function setIdempresa($idempresa) {
        $this->idempresa = $idempresa;
    }

    /**
     * Get the value of enombre
     */
    public function getEnombre() {
        return $this->enombre;
    }

    /**
     * Set the value of enombre
     *
     * @return  self
     */
    public function setEnombre($enombre) {
        $this->enombre = $enombre;

        return $this;
    }

    /**
     * Get the value of edireccion
     */
    public function getEdireccion() {
        return $this->edireccion;
    }

    /**
     * Set the value of edireccion
     *
     * @return  self
     */
    public function setEdireccion($edireccion) {
        $this->edireccion = $edireccion;

        return $this;
    }
    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __toString() {
        $cadena = "Id empresa: " . $this->getIdempresa() . "\nNombre Empresa: " . $this->getEnombre() . "\n Direccion: " . $this->getEdireccion() . "\n";
        return $cadena;
    }

    // ----------------------------------------------




    /**
     * Recupera los datos de una persona por dni
     * @param int $idempresa
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($idempresa) {
        $base = new BaseDatos();
        $consulta = "Select * from empresa where idempresa=" . $idempresa;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdempresa($idempresa);
                    $this->setEnombre($row2['enombre']);
                    $this->setEdireccion($row2['edireccion']);
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    /**
     * @return Empresa[]
     */
    public function listar($condicion = "") {
        $arreglo = null;
        $base = new BaseDatos();
        $consulta = "Select * from empresa";
        if ($condicion != "") {
            $consulta = $consulta . ' where ' . $condicion;
        }
        $consulta .= " order by idempresa ";
        //echo $consulta;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {

                    $idempresa = $row2['idempresa'];
                    $enombre = $row2['enombre'];
                    $edireccion = $row2['edireccion'];
                    $empre = new Empresa();
                    $empre->cargar($idempresa, $enombre, $edireccion);
                    array_push($arreglo, $empre);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $arreglo;
    }



    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $consultaInsertar = "INSERT INTO empresa(enombre,edireccion) VALUES ('" .  $this->getEnombre() . "','" . $this->getEdireccion()  . "')";

        if ($base->Iniciar()) {

            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {

                $this->setIdempresa($id);
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }



    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $consultaModifica = "UPDATE empresa SET idempresa='" . $this->getIdempresa() . "',enombre='" . $this->getEnombre() . "'
                           ,edireccion='" . $this->getEdireccion() . "'
                            WHERE idempresa=" . $this->getIdempresa();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaModifica)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        if ($base->Iniciar()) {
            $consultaBorra = "DELETE FROM empresa WHERE idempresa=" . $this->getIdempresa();
            if ($base->Ejecutar($consultaBorra)) {
                $resp =  true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }
}
