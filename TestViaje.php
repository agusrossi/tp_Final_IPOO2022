<?php
// comprobar al menos el destino no se repita

// comprobar q el dni no se repita de pasajero
// comprobar si quiere eliminar un viaje( preguntar si quiere eliminar los pasajeros sino dar error)

include_once "ViajeFinal.php";
include_once "PasajeroFinal.php";
include_once "ResponsableVFinal.php";
include_once "Empresa.php";

function opcionesMenu() {
    echo "\n 'Bienvenido a \"Viaje Feliz\" !!'\n";
    echo "Elija una opcion del menú \n";
    echo "1- Cargar informacion del viaje \n";
    echo "2- Modificar informacion del viaje \n";
    echo "3- Eliminar datos \n";
    echo "4- Ver datos del viaje \n";
    echo "0- Salir del menu\n";
    $opc = trim(fgets(STDIN));
    return $opc;
}
function auxMenu() {

    do {
        $opc = opcionesMenu();
        switch ($opc) {
            case 1:
                //cargar viajes
                $objempresa = cargarEmpresa();
                $objResponsable = cargarResponsable();
                $objViaje = cargarViaje($objempresa, $objResponsable);
                $cantMax = $objViaje->getVcantMaxPasajero();
                echo "Cuantos pasajeros desea ingresar?\n";
                $cantPasaj = trim(fgets(STDIN));
                cargarPasajeros($cantPasaj, $cantMax, $objViaje);

                break;

            case 2:
                //modificar informacion del viaje
                echo "Que desea modificar?\n";
                echo "1- viaje\n";
                echo "2- pasajero\n";
                echo "3- responsable\n";
                echo "4- empresa\n";
                $opc = trim(fgets(STDIN));
                switch ($opc) {
                    case 1:
                        modificarViaje();
                        break;
                    case 2:
                        modificarPasajero();
                        break;
                    case 3:
                        modificarResponsable();
                        break;
                    case 4:
                        modificarEmpresa();
                        break;
                    default:;
                }


                break;

            case 3:
                //borrar informacion del viaje
                echo "Que desea eliminar?\n";
                echo "1- Viaje\n";
                echo "2- Pasajero\n";
                echo "3- Empresa\n";
                echo "4- Responsable\n";
                $respuesta = trim(fgets(STDIN));
                switch ($respuesta) {
                    case 1:
                        $viaje = elegirViaje();
                        eliminarViaje($viaje);
                        break;
                    case 2:
                        eliminarPasajero();
                        break;
                    case 3:
                        eliminarEmpresa();
                        break;
                    case 4:
                        echo "De que viaje desea eliminar el responsable?\n";
                        $viaje = elegirViaje();
                        eliminarResponsable($viaje->getObjResponsableV());
                        break;
                }
                break;
            case 4:
                //mostrar informacion del viaje
                $viaje = new ViajeFinal;
                $pasajeros = new PasajeroFinal;
                $responsable = new ResponsableVFinal;
                $empresa = new Empresa;
                echo "-----------VIAJE-----------\n";
                mostrarDatos($viaje->listar());
                echo "-----------EMPRESA-----------\n";
                mostrarDatos($empresa->listar());
                echo "-----------RESPONSABLE-----------\n";
                mostrarDatos($responsable->listar());
                echo "-----------PASAJEROS-----------\n";
                mostrarDatos($pasajeros->listar());
                break;
        }
    } while ($opc != 0);
}
function mostrarDatos($array) {
    foreach ($array as $item) {
        echo $item;
    }
}

function cargarPasajeros($cantPasajeros, $cantMax, $objViaje) {
    $i = $objViaje->getcantidadDePasajeros();
    while ($i < $cantPasajeros && $i < $cantMax) {
        $objPasajero = new PasajeroFinal();
        $arregloNombre = generarColNombre();
        echo ("Ingrese su dni\n");
        $dni = trim(fgets(STDIN));
        $usado = $objPasajero->Buscar($dni);
        if (!$usado) {
            $objPasajero->setrdocumento($dni);
            $objPasajero->setNombre($arregloNombre[random_int(0, count($arregloNombre) - 1)][0]);
            $objPasajero->setApellido($arregloNombre[random_int(0, count($arregloNombre) - 1)][1]);
            $objPasajero->setptelefono(random_int(10000, 99999999));
            $objPasajero->setObjViaje($objViaje);
            if ($objPasajero->insertar()) {
                echo "Pasajero Ingresado con exito\n";
            } else {
                echo "Error al insertar\n";
            }
            $i++;
        } else {
            echo "Este DNI ya esta cargado\n";
        }
    }
    if ($i >= $cantMax) {
        echo "El vuelo esta lleno\n";
    }
}

function buscarPasajero($dni) {
    $objPasajero = new PasajeroFinal();
    $objPasajero->Buscar($dni);
    return $objPasajero;
}

function generarColNombre() {
    $preCarga[0] = ["guido", "di fiore"];
    $preCarga[1] =  ["agustina", "rossi"];
    $preCarga[2] =  ["marcos", "polo"];
    $preCarga[3] =  ["malena", "reza"];
    $preCarga[4] = ["maria", "perez"];
    $preCarga[5] = ["pepe", "lopez"];
    return $preCarga;
}
function cargarViaje($objempresa, $objResponsable) {
    $objViaje = new ViajeFinal();
    echo ("\nIngrese cantidad maxima de pasajeros\n");
    $maxPas = trim(fgets(STDIN));
    $objViaje->setVcantMaxPasajero($maxPas);

    $objViaje->setVimporte(random_int(100, 1000));
    echo ("Ingrese tipo asiento cama o semicama\n");
    $tipoAsiento = strtolower(trim(fgets(STDIN)));
    $objViaje->setTipoAsiento($tipoAsiento);
    echo ("El viaje es: \n");
    echo ("1-ida y vuelta: \n");
    echo ("2-solo ida o solo vuelta: \n");
    $opc = trim(fgets(STDIN));
    do {
        switch ($opc) {
            case 1:
                $objViaje->setIdayvuelta("si");
                break;
            case 2:
                $objViaje->setIdayvuelta("no");
                break;
            default:
                echo ("Opcion incorrecta\n");
        }
    } while ($opc != 1 && $opc != 2);
    echo "Destino al que viajar?\n";
    $destino = trim(fgets(STDIN));
    $objViaje->setVdestino($destino);
    $objViaje->setObjEmpresa($objempresa);
    $objViaje->setObjResponsableV($objResponsable);
    $objViaje->insertar();

    return $objViaje;
}

function cargarResponsable() {
    $arregloNombre = generarColNombre();
    $objResponsable = new ResponsableVFinal();
    $objResponsable->setNumLicencia(random_int(0, 300));
    $objResponsable->setNombre($arregloNombre[random_int(0, count($arregloNombre) - 1)][0]);
    $objResponsable->setApellido($arregloNombre[random_int(0, count($arregloNombre) - 1)][1]);
    $objResponsable->insertar();
    return $objResponsable;
}

function cargarEmpresa() {
    $objempresa = new Empresa();
    echo "Ingrese nombre de la empresa\n";
    $nombreEmp = trim(fgets(STDIN));
    $objempresa->setEnombre($nombreEmp);
    echo "Ingrese direccion de la empresa\n";
    $direccionEmp = trim(fgets(STDIN));
    $objempresa->setEdireccion($direccionEmp);
    $objempresa->insertar();
    return $objempresa;
}


function elegirViaje() {
    $objViaje = new ViajeFinal();
    echo "elija el numero viaje\n";
    $viajes = $objViaje->listar();
    foreach ($viajes as $viaje) {
        echo $viaje->getIdViaje() . " - " . $viaje->getVdestino() . "\n";
    }
    $opc = trim(fgets(STDIN));
    $objViaje->Buscar($opc);
    return $objViaje;
}

function modificarPasajero() {
    $viaje = elegirViaje();
    echo "Ingrese dni del pasajero que desea cambiar\n";
    $dni = trim(fgets(STDIN));
    $pasaj = new PasajeroFinal();
    $pasaj = $pasaj->listar(['idviaje =' . $viaje->getIdViaje() . 'AND rdocumento=' . $dni])[0];

    if ($pasaj) {
        echo "ingrese el nuevo apellido\n";
        $apellido = trim(fgets(STDIN));
        echo "ingrese el nuevo nombre\n";
        $nombre = trim(fgets(STDIN));
        echo "ingrese el nuevo dni\n";
        $dni = trim(fgets(STDIN));
        echo "ingrese el nuevo numero de telefono\n";
        $telefono = trim(fgets(STDIN));
        if ($pasaj->Buscar($dni)) {
            echo "ese dni ya fue ingresado\n";
        } else {
            $pasaj->cargar($nombre, $apellido, $dni, $telefono, $pasaj->getObjViaje());
            $pasaj->modificar();
        }
    } else {
        echo "No se encontro el pasajero en este viaje\n";
    }
}


function modificarViaje() {
    $viaje = elegirViaje();
    if ($viaje->getVdestino() != null) {
        echo "Modificar:\n";
        echo "1) Id viaje\n";
        echo "2) Destino\n";
        echo "3) Cantidad máxima de pasajeros\n";
        echo "4) Importe\n";
        echo "5) Tipo Asiento(cama semicama)\n";
        echo "6) Ida y vuelta(si no)\n";
        echo "7) Agregar pasajeros\n";

        $opc = trim(fgets(STDIN));

        switch ($opc) {
            case 1:
                echo "Ingrese nuevo Id\n";
                $codigo = trim(fgets(STDIN));
                $viaje->setIdViaje($codigo);
                $viaje->modificar();
                break;
            case 2:
                echo "Ingrese nuevo destino";
                $destino = trim(fgets(STDIN));
                $viaje->setVdestino($destino);
                $viaje->modificar();
                break;
            case 3:
                echo "Ingrese nueva cantidad maxima de pasajeros\n";
                $cMax = trim(fgets(STDIN));
                if ($viaje->getcantidadDePasajeros() < $cMax) {
                    $viaje->setVcantMaxPasajero($cMax);
                    $viaje->modificar();
                } else {
                    echo "No es posible poner una cantidad menor a la cantidad de pasajeros existentes\n";
                }
                break;
            case 4:
                echo "Ingrese nuevo importe\n";
                $importe = trim(fgets(STDIN));
                $viaje->setVimporte($importe);
                $viaje->modificar();
                break;
            case 5:
                echo "Ingrese nuevo tipo (cama semicama)\n";
                $tipoA = trim(fgets(STDIN));
                $viaje->setTipoAsiento($tipoA);
                $viaje->modificar();
                break;
            case 6:
                echo "Ida y vuelta(si no)\n";
                $idayvuelta = trim(fgets(STDIN));
                $viaje->setIdayvuelta($idayvuelta);
                $viaje->modificar();
                break;
            case 7:
                echo "ingrese la cantidad de pasajeros a ingresar\n";
                $cantP = trim(fgets(STDIN));
                cargarPasajeros($cantP, $viaje->getVcantMaxPasajero(), $viaje);
                break;
        }
    } else {
        echo "viaje invalido";
    }
}

function elegirEmpresa() {
    $objempresa = new Empresa;
    echo "elija la empresa\n";
    $empresas = $objempresa->listar();
    foreach ($empresas as $empresa) {
        echo $empresa->getIdempresa() . " - " . $empresa->getEnombre() . "\n";
    }
    $opc = trim(fgets(STDIN));
    $objempresa->Buscar($opc);
    return $objempresa;
}
function modificarEmpresa() {
    $empresa = new Empresa;

    echo "Nuevo id empresa\n";
    $codigo = trim(fgets(STDIN));
    echo "Nuevo nombre\n";
    $nombre = trim(fgets(STDIN));
    echo "Nueva direccion\n";
    $direccion = trim(fgets(STDIN));

    $empresa->cargar($codigo, $nombre, $direccion);
    $empresa->modificar();
}

function elegirResponsable() {
    $objResponsable = new ResponsableVFinal;
    echo "elija el numero de responsable\n";
    $responsables = $objResponsable->listar();
    foreach ($responsables as $responsable) {
        echo $responsable->getNumEmpleado() . " - " . $responsable->getNombre() . " " . $responsable->getApellido() . "\n";
    }
    $opc = trim(fgets(STDIN));
    $objResponsable->Buscar($opc);
    return $objResponsable;
}
function modificarResponsable() {
    $responsable = new ResponsableVFinal;

    echo "Nuevo numero de empleado\n";
    $numEmp = trim(fgets(STDIN));
    echo "Nuevo numero de licencia\n";
    $numLic = trim(fgets(STDIN));
    echo "Nuevo nombre\n";
    $nombre = trim(fgets(STDIN));
    echo "Nuevo apellido\n";
    $direccion = trim(fgets(STDIN));

    $responsable->cargar($numEmp, $numLic, $nombre, $direccion);
    $responsable->modificar();
}
/** @param ViajeFinal $viaje */
function eliminarViaje($viaje) {

    $pasaj = new PasajeroFinal();
    $colpasaj = $pasaj->listar('idviaje =' . $viaje->getIdViaje());
    if ($colpasaj != null) {
        echo "El viaje tiene pasajeros\n";
        echo "Desea eliminarlos?\n";
        echo "1- Si\n";
        echo "2- No\n";
        $opc = trim(fgets(STDIN));
        switch ($opc) {
            case 1:
                foreach ($colpasaj as $pasajero) {
                    $pasajero->eliminar();
                }
                $viaje->getObjResponsableV()->eliminar();
                if ($viaje->eliminar()) {
                    echo "se elimino con exito\n";
                } else {
                    echo "no pudo eliminarse";
                }
                break;
            case 2:
                echo "El viaje no puede eliminarse\n";
                break;
        }
    } else {
        if ($viaje->eliminar()) {
            echo "se elimino con exito\n";
        } else {
            echo "no pudo eliminarse";
        }
    }
}

function eliminarPasajero() {
    $viaje = elegirViaje();
    echo "Ingrese el dni del pasajero que desea eliminar\n";
    $pasaj = new PasajeroFinal();
    $pasajeros = $pasaj->listar(['idviaje =' . $viaje->getIdViaje()]);
    foreach ($pasajeros as $pasajero) {
        echo "DNI: " . $pasajero->getrdocumento() . "\n";
    }
    $dni = trim(fgets(STDIN));
    $pasaj->Buscar($dni);
    if ($pasaj->eliminar()) {
        echo "Pasajero eliminado con exito\n";
    } else {
        echo "No se pudo eliminar pasajero\n";
    }
}

function eliminarEmpresa() {
    $empresa = elegirEmpresa();
    $viaje = new ViajeFinal;
    $colviaje = $viaje->listar('idempresa =' . $empresa->getIdempresa());
    if ($colviaje != null) {
        echo "La Empresa posee viajes, desea eliminarlos?\n";
        echo "1- si\n";
        echo "2- no\n";
        $opc = trim(fgets(STDIN));
        switch ($opc) {
            case 1:
                foreach ($colviaje as $v) {
                    eliminarViaje($v);
                }
                if ($empresa->eliminar()) {
                    echo "se elimino con exito la empresa\n";
                } else {
                    echo "no se pudo eliminar la empresa\n";
                }
                break;
            case 2:
                echo "El viaje no puede eliminarse por que posee viajes\n";
                break;
        }
    } else {
        if ($empresa->eliminar()) {
            echo "Se elimino con exito!!\n";
        } else {
            echo "No se pudo eliminar\n";
        }
    }
}
function eliminarResponsable($responsable) {

    if ($responsable->eliminar()) {
        echo "Responsable eliminado con exito\n";
    } else {
        echo "No se pudo eliminar el responsable\n";
    }
}
auxmenu();
