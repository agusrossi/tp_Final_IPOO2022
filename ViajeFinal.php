<?php
include_once "BaseDatos.php";
class ViajeFinal {
    private $idViaje;
    private $vdestino;
    private $vcantMaxPasajero;
    private $vimporte;
    private $tipoAsiento;
    private $idayvuelta;

    private $objEmpresa;
    private $objResponsableV;
    private $mensajeoperacion;

    function __construct() {
        $this->idViaje = null;
        $this->vdestino = null;
        $this->vcantMaxPasajero = null;
        $this->vimporte = null;
        $this->tipoAsiento = null;
        $this->idayvuelta = null;
        $this->objPasajeros = null;
        $this->objEmpresa = null;
        $this->objResponsableV = null;
    }
    function cargar($vdestino, $cantMP, $vimporte, $tipoAsiento, $idayvuelta, $empresa, $objResponsableV) {
        $this->vdestino = $vdestino;
        $this->vcantMaxPasajero = $cantMP;
        $this->vimporte = $vimporte;
        $this->tipoAsiento = $tipoAsiento;
        $this->idayvuelta = $idayvuelta;
        $this->objResponsableV = $objResponsableV;
        $this->objEmpresa = $empresa;
    }



    public function getIdViaje() {
        return $this->idViaje;
    }

    public function setIdViaje($idViaje) {
        $this->idViaje = $idViaje;
    }

    public function getVdestino() {
        return $this->vdestino;
    }


    public function setVdestino($vdestino) {
        $this->vdestino = $vdestino;
    }

    public function getVcantMaxPasajero() {
        return $this->vcantMaxPasajero;
    }


    public function setVcantMaxPasajero($vcantMaxPasajero) {
        $this->vcantMaxPasajero = $vcantMaxPasajero;
    }


    public function getVimporte() {
        return $this->vimporte;
    }


    public function setVimporte($vimporte) {
        $this->vimporte = $vimporte;
    }


    public function getTipoAsiento() {
        return $this->tipoAsiento;
    }


    public function setTipoAsiento($tipoAsiento) {
        $this->tipoAsiento = $tipoAsiento;
    }

    public function getIdayvuelta() {
        return $this->idayvuelta;
    }


    public function setIdayvuelta($idayvuelta) {
        $this->idayvuelta = $idayvuelta;
    }

    /**
     * @return Empresa
     */
    public function getObjempresa() {
        return $this->objEmpresa;
    }


    public function setObjEmpresa($objEmpresa) {
        $this->objEmpresa = $objEmpresa;
    }

    public function setObjResponsableV($objResponsableV) {
        $this->objResponsableV = $objResponsableV;
    }
    /**
     * @return ResponsableVFinal
     */
    public function getObjResponsableV() {
        return $this->objResponsableV;
    }

    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function getcantidadDePasajeros() {
        $pasajero = new PasajeroFinal();
        $colP = $pasajero->listar('idviaje=' . $this->getIdViaje());
        return count($colP);
    }
    /* -------------------------------------------------------------------------*/

    /**
     * Recupera los datos de una persona por dni
     * @param int $rdocumento
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($idViaje) {
        $base = new BaseDatos();
        $consulta = "Select * from viaje where idviaje=" . $idViaje;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setIdViaje($idViaje);
                    $this->setVdestino($row2['vdestino']);
                    $this->setVcantMaxPasajero($row2['vcantmaxpasajeros']);
                    $this->setTipoAsiento($row2['tipoAsiento']);
                    $this->setIdayvuelta($row2['idayvuelta']);
                    $this->setVimporte($row2['vimporte']);

                    $objempresa = new Empresa();
                    $objempresa->Buscar($row2['idempresa']);
                    $this->setObjEmpresa($objempresa);

                    $objresponsable = new ResponsableVFinal();
                    $objresponsable->Buscar($row2['rnumeroempleado']);
                    $this->setObjResponsableV($objresponsable);
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
     * @return ViajeFinal[]
     */

    public function listar($condicion = "") {
        $arreglo = null;
        $base = new BaseDatos();
        $consulta = "Select * from viaje";
        if ($condicion != "") {
            $consulta = $consulta . ' where ' . $condicion;
        }
        $consulta .= " order by vdestino ";
        //echo $consulta;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {
                    $idViaje = $row2['idviaje'];
                    $vdestino = $row2['vdestino'];
                    $vcantMaxPasajero = $row2['vcantmaxpasajeros'];
                    $vimporte = $row2['vimporte'];
                    $tipoAsiento = $row2['tipoAsiento'];
                    $idayvuelta = $row2['idayvuelta'];

                    $objempresa = new Empresa();
                    $objempresa->Buscar($row2['idempresa']);
                    $objresponsable = new ResponsableVFinal();
                    $objresponsable->Buscar($row2['rnumeroempleado']);

                    $viaje = new ViajeFinal();
                    $viaje->cargar($vdestino, $vcantMaxPasajero, $vimporte, $tipoAsiento, $idayvuelta, $objempresa, $objresponsable);
                    $viaje->setIdViaje($idViaje);
                    array_push($arreglo, $viaje);
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

        $consultaInsertar = "INSERT INTO viaje(vdestino, vcantmaxpasajeros,idempresa,
        rnumeroempleado,vimporte,tipoAsiento,idayvuelta) 
				VALUES ('" . $this->getVdestino() . "'," . $this->getVcantMaxPasajero() . "," . $this->getObjResponsableV()->getnumEmpleado() . "," . $this->getObjempresa()->getIdempresa() . ","  .  $this->getVimporte() . ",'" . $this->getTipoAsiento() . "','" . $this->getIdayvuelta() . "')";

        if ($base->Iniciar()) {

            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {

                $this->setIdViaje($id);
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
        $idempresa = $this->getObjEmpresa()->getIdempresa();
        $rnumeroempleado = $this->getObjResponsableV()->getNumEmpleado();
        $base = new BaseDatos();
        $consultaModifica = "UPDATE viaje SET idviaje={$this->getIdViaje()},vdestino='{$this->getVdestino()} '
                           ,vcantmaxpasajeros={$this->getVcantMaxPasajero()},idempresa={$idempresa},rnumeroempleado={$rnumeroempleado},vimporte={$this->getVimporte()}, tipoAsiento='{$this->getTipoAsiento()}',idayvuelta='{$this->getIdayvuelta()}' WHERE idviaje={$this->getIdViaje()}";
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
            $consultaBorra = "DELETE FROM viaje WHERE idviaje={$this->getIdViaje()}";
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

    function __toString() {
        $cadena = "\n-----------VIAJE-----------\n" . "\nId viaje: " . $this->getIdViaje() . "\nDestino: " . $this->getVdestino() . "\nCantidad maxima de pasajeros: " . $this->getVcantMaxPasajero()  . "\nimporte: " . $this->getVimporte() . "\ntipo asiento: " .  $this->getTipoAsiento() . "\nIda y vuelta: " . $this->getIdayvuelta() . "\n" . $this->getObjEmpresa() .   $this->getObjResponsableV() . "\n";
        return $cadena;
    }
}
