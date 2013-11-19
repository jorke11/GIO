<?php
require_once("model/UsuariosModel.php");
ob_start();
require_once("model/MenuModel.php");
$obj=new Usuarios;
$menu=new Menu;

$principal=$menu->get_principal();
//$principal=$menu->get_principal_user($_SESSION["cedula"]);


if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$obj->get_centro($datos);
}



require_once"view/Home.phtml";
?>

