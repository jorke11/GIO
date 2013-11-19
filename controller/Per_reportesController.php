<?php
require_once("model/Per_reportesModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$obj=new C_menor;
$user=new usuarios;
$menu=new Menu;

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}

//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$res=$user->get_centro($_SESSION["Cedula"]);
$detalle=$obj->get_cajas();

require_once("view/Per_reportes.phtml");

?>