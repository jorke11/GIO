<?php
require_once("model/Alm_vehiModel.php");
require_once("model/Alm_equipoModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
ob_start();
$menu=new Menu;
$obj=new Alm_vehi;
$obj2=new Alm_equipo;
$user=new usuarios;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();

$marca=$obj2->get_marca();
$modelo=$obj2->get_modelo();
$tipoVehiculo=$obj2->get_tipoVehiculo();

$detalle=array();

$personal=$obj->get_personal();
$personales=implode(",", $personal);


if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


$proyecto=$obj->get_proyectos();
$proyectos=implode(",", $proyecto);

if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){
    $obj->guardar_registro();
    exit;
}

if(isset($_POST["grabar_formu"]) and $_POST["grabar_formu"]=="si"){
	$obj->guardar_detalle();    
    exit;
}
if(isset($_POST["grabar_formu"]) and $_POST["grabar_formu"]=="si2"){
    $obj->edit_detalle();
    exit;
}

if(isset($_GET["e"])){
    $obj->eliminar_detalle($_GET["e"]);
}

if(isset($_GET["n"])){
            $res=$obj->nuevo_registro();    
            $d=$obj->get_requi($res);  
}

if(isset($_GET["b"])){
    if(!empty($_GET["b"])){
    $d=$obj->get_requi2($_GET["b"]);
    }
}
if(isset($_GET["b2"])){
    if(!empty($_GET["b2"])){
    $d=$obj->get_requi($_GET["b2"]);
    }
}

/*if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Id"]);
    }*/


require_once"view/Alm_vehi.phtml";
?>