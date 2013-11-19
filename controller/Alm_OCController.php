<?php
require_once("model/RequisicionesModel.php");
require_once("model/OCModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$menu=new Menu;
$obj=new Orden;
$obj2=new Requisicion;
$user=new usuarios;
$res=$user->get_centro($_SESSION["Cedula"]);

$res=$user->get_centro($_SESSION["Cedula"]);
$principal=$menu->get_principal();
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$listas=$obj->get_tipo();
$estado=$obj->get_tipo2();

$proveedor=$obj->get_proveedor();
$proveedores=implode(",", $proveedor);

$prod=$obj2->get_productos();
$productos=implode(",", $prod);



if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


$d=array();
$detalle=array();



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
    $obj->eliminar_detalle($_GET["e"],$_GET["b"]);
}

if(isset($_GET["n"])){
            $res2=$obj->nuevo_registro();    
            $d=$obj->get_requi($res2);  
}

if(isset($_GET["b"])){
    $d=$obj->get_requi($_GET["b"]);
}

if(isset($_GET["i"])){
    $obj->bloquea_doc($_GET["i"]);
    $d=$obj->get_requi($_GET["i"]);
}

if(isset($_GET["Con"])){
    $obj->copiar($_GET["Con"],$_GET["id"]);
}


if(isset($_GET["ed"])){
    $detalle_id=$obj->get_detalle_por_id($_GET["ed"],$_GET["b"]);
}

if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Consecutivo"]);
    }

if(isset($d[0]["Id_estado"])){
    if($d[0]["Id_estado"]==2){
        $imp="disabled";
        $bt="";
    }else{
        $bt="abrir";
    }
}



require_once"view/Alm_OC.phtml";
?>