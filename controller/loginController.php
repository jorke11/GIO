<?php
require_once"model/UsuariosModel.php";
ob_start();
$obj=new Usuarios;
if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){
    $obj->validar_login();
}
require_once"view/Login.phtml";
?>