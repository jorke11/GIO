<?php
require_once("model/PermisosModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();

$user=new usuarios;
$menu=new Menu;
$obj=new Permisos;
$modulo=array();
$d=array();

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}

// $principal=$menu->get_principal_user($_SESSION["Trama_prin"]);
$principal=$menu->get_principal();


$res=$user->get_centro($_SESSION["Cedula"]);

if(isset($_POST["guardar"]) and $_POST["guardar"]=="si"){
    $obj->guardar_registro();
    exit;
}

if(isset($_POST["f_principal"]) and $_POST["f_principal"]=="si"){
    $obj->guardar_principal();
    exit;
}


if(isset($_GET["b"])){
    $d=$obj->getUsuario($_GET["b"]);
}

if(count($d) > 0){
    $modulo=$obj->getModulos();
}


require_once("view/Adm_permisos.phtml");

?>