<?php
include_once "ViajeFinal.php";
include_once "BaseDatos.php";
class PasajeroFinal {
    private $pnombre;
    private $papellido;
    private $rdocumento;
    private $ptelefono;
    private $objViaje;
    private $mensajeoperacion;

    public function __construct() {
        $this->pnombre = null;
        $this->papellido = null;
        $this->rdocumento = null;
        $this->ptelefono = null;
        $this->objViaje = null;
    }
    public function cargar($pnombre, $papellido, $rdocumento, $ptelefono, $objViaje) {
        $this->pnombre = $pnombre;
        $this->papellido = $papellido;
        $this->rdocumento = $rdocumento;
        $this->ptelefono = $ptelefono;
        $this->objViaje = $objViaje;
    }

    public function getptelefono() {
        return $this->ptelefono;
    }

    public function setptelefono($ptelefono) {
        $this->ptelefono = $ptelefono;
    }

    public function getrdocumento() {
        return $this->rdocumento;
    }

    public function setrdocumento($rdocumento) {
        $this->rdocumento = $rdocumento;
    }

    public function getApellido() {
        return $this->papellido;
    }

    public function setApellido($papellido) {
        $this->papellido = $papellido;
    }

    public function getNombre() {
        return $this->pnombre;
    }

    public function setNombre($pnombre) {
        $this->pnombre = $pnombre;
    }

    /**
     * @return  ViajeFinal
     */
    public function getObjViaje() {
        return $this->objViaje;
    }

    public function setObjViaje($objViaje) {
        $this->objViaje = $objViaje;
    }


    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }




    /**
     * Recupera los datos de una persona por dni
     * @param int $rdocumento
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($rdocumento) {
        $base = new BaseDatos();
        $consulta = "Select * from pasajero where rdocumento=" . $rdocumento;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setrdocumento($rdocumento);
                    $this->setNombre($row2['pnombre']);
                    $this->setApellido($row2['papellido']);
                    $this->setptelefono($row2['ptelefono']);
                    $objViaje1 = new ViajeFinal();
                    $objViaje1->Buscar($row2['idviaje']);
                    $this->setObjViaje($objViaje1);
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
     * @return PasajeroFinal[]
     */

    public function listar($condicion = "") {
        $arreglo = null;
        $base = new BaseDatos();
        $consulta = "Select * from pasajero";
        if ($condicion != "") {
            $consulta = $consulta . ' where ' . $condicion;
        }
        $consulta .= " order by idviaje ";
        //echo $consulta;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {

                    $nroDoc = $row2['rdocumento'];
                    $pnombre = $row2['pnombre'];
                    $papellido = $row2['papellido'];
                    $ptelefono = $row2['ptelefono'];
                    $idViaje = $row2['idviaje'];
                    $objViaje = new ViajeFinal();
                    $objViaje->Buscar($idViaje);
                    $perso = new PasajeroFinal();
                    $perso->cargar($pnombre, $papellido, $nroDoc, $ptelefono, $objViaje);
                    array_push($arreglo, $perso);
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
        $idViaje = $this->getObjViaje()->getIdViaje();
        $consultaInsertar = "INSERT INTO pasajero(rdocumento, papellido, pnombre,ptelefono,idviaje) 
				VALUES (" . $this->getrdocumento() . ",'" . $this->getApellido() . "','" . $this->getNombre() . "'," . $this->getptelefono() . "," . $idViaje . ")";

        if ($base->Iniciar()) {

            if ($base->Ejecutar($consultaInsertar)) {

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
        $consultaModifica = "UPDATE pasajero SET papellido='" . $this->getApellido() . "',pnombre='" . $this->getNombre() . "'
                           ,ptelefono='" . $this->getptelefono() . "'
                           ,idviaje='" . $this->getObjViaje()->getIdViaje() . "' WHERE rdocumento=" . $this->getrdocumento();
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
            $consultaBorra = "DELETE FROM pasajero WHERE rdocumento=" . $this->getrdocumento();
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

    public function __toString() {
        $cadena =  "\n" . $this->getObjViaje() . "\n-----------PASAJEROS-----------\n" . "Nombre: " . $this->getNombre() . "\nApellido: " . $this->getApellido() . "\nDNI: " . $this->getrdocumento() . "\nptelefono: " . $this->getptelefono() . "\n";
        return $cadena;
    }
}
