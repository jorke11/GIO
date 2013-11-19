<?php
require_once("model/MenuModel.php");
ob_start();
$menu=new Menu;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();

require_once("view/Cambio.phtml");
?>