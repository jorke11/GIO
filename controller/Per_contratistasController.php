<?php
require_once("model/Per_contratistasModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$obj=new Contratistas;
$user=new usuarios;
$menu=new Menu;
$d=array();
$detalle=array();
$error=0;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$listas=$obj->get_tipo();
$res=$user->get_centro($_SESSION["Cedula"]);



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
            $res2=$obj->nuevo_registro();    
            $d=$obj->get_requi($res2);  
            
}

if(isset($_GET["b"])){
    if(isset($_GET["error"])){
        $error=$_GET["error"];
    }

    $d=$obj->get_requi($_GET["b"]);
}

if(isset($_GET["i"])){
    $obj->bloquea_doc($_GET["i"]);
    $d=$obj->get_requi($_GET["i"]);
}

if(isset($_GET["Con"])){
    $obj->copiar($_GET["Con"]);
}





require_once("view/Per_contratistas.phtml");

?>