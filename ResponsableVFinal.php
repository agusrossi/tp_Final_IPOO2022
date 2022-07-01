<?php
include_once "BaseDatos.php";
class ResponsableVFinal {
    private $rnumeroempleado;
    private $rnumerolicencia;
    private $rnombre;
    private $rapellido;
    private $mensajeoperacion;

    public function __construct() {
    }


    public function cargar($numEmpleado, $numLicencia, $nombre, $apellido) {
        $this->rnumeroempleado = $numEmpleado;
        $this->rnumerolicencia = $numLicencia;
        $this->rnombre = $nombre;
        $this->rapellido = $apellido;
    }



    public function getApellido() {
        return $this->rapellido;
    }

    public function setApellido($apellido) {
        $this->rapellido = $apellido;
    }

    public function getNumEmpleado() {
        return $this->rnumeroempleado;
    }

    public function setNumEmpleado($numEmpleado) {
        $this->rnumeroempleado = $numEmpleado;
    }

    public function getNumLicencia() {
        return $this->rnumerolicencia;
    }

    public function setNumLicencia($numLicencia) {
        $this->rnumerolicencia = $numLicencia;
    }

    public function getNombre() {
        return $this->rnombre;
    }

    public function setNombre($nombre) {
        $this->rnombre = $nombre;
    }

    public function __toString() {
        $cadena = "\nNombre: " . $this->getNombre() . "\nApellido: " . $this->getApellido() . "\nNumero empleado: " . $this->getNumEmpleado() . "\nNumero licencia: " . $this->getNumLicencia() . "\n";
        return $cadena;
    }

    public function getmensajeoperacion() {
        return $this->mensajeoperacion;
    }
    public function setmensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }


    // ----------------------------------------------




    /**
     * Recupera los datos de una persona por dni
     * @param int $idempresa
     * @return true en caso de encontrar los datos, false en caso contrario 
     */
    public function Buscar($rnumeroempleado) {
        $base = new BaseDatos();
        $consulta = "Select * from responsable where rnumeroempleado=" . $rnumeroempleado;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $this->setNumEmpleado($rnumeroempleado);
                    $this->setNombre($row2['rnombre']);
                    $this->setApellido($row2['rapellido']);
                    $this->setNumLicencia($row2['rnumerolicencia']);
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
     * @return ResponsableVFinal[]
     */
    public function listar($condicion = "") {
        $arreglo = null;
        $base = new BaseDatos();
        $consulta = "Select * from responsable";
        if ($condicion != "") {
            $consulta = $consulta . ' where ' . $condicion;
        }
        $consulta .= " order by rnumeroempleado ";
        //echo $consulta;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $arreglo = array();
                while ($row2 = $base->Registro()) {

                    $rnumeroempleado = $row2['rnumeroempleado'];
                    $rnumerolicencia = $row2['rnumerolicencia'];
                    $rnombre = $row2['rnombre'];
                    $rapellido = $row2['rapellido'];
                    $resp = new ResponsableVFinal();
                    $resp->cargar($rnumeroempleado, $rnumerolicencia, $rnombre, $rapellido);
                    array_push($arreglo, $resp);
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
        $consultaInsertar = "INSERT INTO responsable(rnumerolicencia,rnombre,rapellido) 
				VALUES (" . $this->getNumLicencia() . ",'" . $this->getNombre()  . "','" . $this->getApellido() . "')";

        if ($base->Iniciar()) {
          
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {

                $this->setNumEmpleado($id);
                $resp = true;
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
        $consultaModifica = "UPDATE responsable SET rnumeroempleado={$this->getNumEmpleado()},rnumerolicencia={$this->getNumLicencia()},rnombre='{$this->getNombre()}',rapellido='{$this->getApellido()}' WHERE rnumeroempleado={$this->getNumEmpleado()}";
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
            $consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado=" . $this->getNumEmpleado();
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
