<?php
require_once("model/Alm_equipoModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");

ob_start();
$menu=new Menu;
$obj=new Alm_equipo;
$user=new usuarios;
$res=$user->get_centro($_SESSION["Cedula"]);
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$marca=$obj->get_marca();
$modelo=$obj->get_modelo();
$tipovehiculo=$obj->get_tipoVehiculo();
$propiedad=$obj->get_propiedad();
$localizacion=$obj->get_localizacion();

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}

$proyecto=$obj->get_proyectos();
$proyectos=implode(",", $proyecto);

$detalle=array();

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
            $res2=$obj->nuevo_registro();   
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


require_once"view/Alm_Equipos.phtml";
?>