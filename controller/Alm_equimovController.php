<?php
require_once("model/Alm_equimovModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
require_once("model/Alm_equipoModel.php");
require_once("model/SalidasModel.php");


ob_start();
$menu=new Menu;
$obj=new MovEquipo;
$obj2=new Alm_equipo;
$user=new usuarios;
$obj3=new Salidas;

//$principal=$menu->get_principal_user($_SESSION["cedula"]);

$principal=$menu->get_principal();
//$estado=$obj->get_tipo2();
$d=array();
$detalle=array();
$serial=$obj->get_equipos();
$destino=$obj3->get_destino();
$localizacion=$obj2->get_localizacion();



if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


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
}

if(isset($_GET["b"])){
    if(!empty($_GET["b"])){
    $d=$obj->get_requi($_GET["b"]);
    }
}

if(isset($_GET["i"])){
    if(!empty($_GET["i"])){
    $d=$obj->get_requi($_GET["i"]);
    }
}

if(COUNT($d)>0){
            $detalle=$obj->get_detalle($d[0]["Consecutivo"]);
    }
    

require_once"view/Alm_equimov.phtml";
?>