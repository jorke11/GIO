<?php
//require_once("model/RequisicionModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");

ob_start();
//$obj=new Requisicion;
$user=new usuarios;
$menu=new Menu;
$principal=$menu->get_principal();
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
//$listas=$obj->get_tipo();
$res=$user->get_centro($_SESSION["cedula"]);

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
    $d=$obj->get_requi($_GET["b"]);
}

if(isset($_GET["i"])){
    $obj->bloquea_doc($_GET["i"]);
    $d=$obj->get_requi($_GET["i"]);
}

if(isset($_GET["Con"])){
    $obj->copiar($_GET["Con"]);
}

if(isset($_GET["ed"])){
    $detalle_id=$obj->get_detalle_por_id($_GET["ed"],$_GET["b"]);
}

if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Consecutivo"]);
    }


require_once("view/Adm_centros.phtml");

?>