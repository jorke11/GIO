<?php
require_once("model/Ope_presobraModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$obj=new PresObra;
$user=new usuarios;
$menu=new Menu;
$d=array();
$detalle=array();
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$listas=$obj->get_tipo();
$res=$user->get_centro($_SESSION["Cedula"]);

$apu=$obj->getApu();
$apus=implode(",", $apu);


if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}

if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){
    $obj->guardar_registro();
    exit;
}

if(isset($_POST["f_grabar"]) and $_POST["f_grabar"]=="si"){
    $obj->guardar_detalle();    
    exit;
}
if(isset($_POST["f_grabar"]) and $_POST["f_grabar"]=="si2"){
    $obj->edit_detalle();
    exit;
}

if(isset($_GET["id"])){
    $obj->copiar($_GET["id"]);
}

if(isset($_GET["e"])){
    $obj->eliminar_detalle($_GET["e"],$_GET["Con"]);
}

if(isset($_GET["n"])){
    $obj->nuevo_registro();    
}

if(isset($_GET["b"])){
    $detalle=$obj->get_detalle($_GET["b"]);
}

if(isset($_GET["Sav"])){
    $obj->guardar_fila($_GET["Sav"]);
}

$d=$obj->get_presobra();


if(isset($_GET["Con"])){
    $obj->copiar($_GET["Con"]);
}



require_once("view/Ope_presobra.phtml");

?>