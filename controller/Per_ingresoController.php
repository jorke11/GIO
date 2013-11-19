<?php
require_once("model/Per_ingresoModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
ob_start();
$menu=new Menu;
$user=new usuarios;
$obj=new Log_equi;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();

$productos=$obj->get_productos();
$cargo=$obj->get_cargo();
$Tipocontrato=$obj->get_Tipocontrato();
$Centrotrabajo=$obj->get_Centrotrabajo();
$Periodopago=$obj->get_Periodopago();
$tpagos=$obj->get_tipopagos();


if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){
    $obj->guardar_personal();    
    exit;
}


if(isset($_GET["n"])){
            $res=$obj->nuevo_registro();    
}

if(isset($_GET["b2"])){
    if(!empty($_GET["b2"])){
        $d=$obj->get_requi($_GET["b2"]);
    }
}

if(isset($_GET["b"])){
    if(!empty($_GET["b"])){
        $d=$obj->get_requi2($_GET["b"]);
    }
}
//GUARDAR ACTIVIDAD

if(isset($_POST["f_pagos"]) and $_POST["f_pagos"]=="si"){
    $obj->guardar_pagos();    
    exit;
}


/*if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Consecutivo"]);
    }*/
require_once"view/Per_ingreso.phtml";
?>