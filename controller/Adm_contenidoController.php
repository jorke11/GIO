<?php
require_once("model/RegistrarModel.php");
require_once("model/MenuModel.php");
require_once("model/Adm_contenidoModel.php");
require_once("model/UsuariosModel.php");
ob_start();

$obj=new Registro;
$menu=new Menu;
$con=new Adm_contenido;
$user=new usuarios;

$centros=$obj->get_centro();
$datos=array();


$principal=$menu->get_principal();
$centro_user=$obj->centros_usuarios($_SESSION["Cedula"]);
$control=$con->get_controles();

if(isset($_SESSION["Cedula"])){
    $dato["Cedula"]=$_SESSION["Cedula"];
    $dato["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($dato);
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
            $d=$obj->get_requi($res);  
            
}

if(isset($_GET["id"])){
    $con->agregar_control($_GET["id"],$_GET["txt"]);
    $datos=$con->get_listas($_GET["id"]);
}

if(isset($_GET["Centro"])){
    $con->agregar_centro();
}
if(isset($_GET["Centro_b"])){
    $det=$con->get_centros();
}


if(isset($_GET["con"])){
    $con->agregar_tit($_GET["con"]);
}

if(isset($_GET["b"])){
    $d=$obj->get_requi($_GET["b"]);

}


require_once("view/Adm_contenido.phtml");

?>