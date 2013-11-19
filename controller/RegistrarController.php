<?php
require_once("model/RegistrarModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
ob_start();

$obj=new Registro;
$menu=new Menu;
$user=new usuarios;


$principal=$menu->get_principal();
$centro_user=$obj->centros_usuarios($_SESSION["Cedula"]);

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
    $centrop=$obj->get_centro();
    $roles=$obj->getRoles();
}

$sucursal=$user->get_Sucursales();

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
    $obj->nuevo_registro();             
}

if(isset($_GET["b"])){
    $d=$obj->Buscar($_GET["b"]);
}

if(isset($_GET["Con"])){
    $d=$obj->BuscarCon($_GET["Con"]);
}


/*if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Id"]);
    }*/


require_once("view/Registrar.phtml");

?>