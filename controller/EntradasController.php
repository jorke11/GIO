<?php
require_once("model/EntradasModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$menu=new Menu;
$obj=new Entradas;
$user=new usuarios;
$d=array();
$detalle=array();
$imp="";
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$estado=$obj->get_tipo2();
$res=$user->get_centro($_SESSION["Cedula"]);

$proyecto=$obj->get_proyectos();
$proyectos=implode(",", $proyecto);

$prod=$obj->get_productos();
$productos=implode(",", $prod);

$proveedor=$obj->get_proveedor();
$proveedores=implode(",", $proveedor);

$control=$obj->get_EstadoDoc();

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}



if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){
    $obj->guardar_registro();
    exit;
}

if(isset($_POST["grabar_pro"]) and $_POST["grabar_pro"]=="si"){
    $obj->guarda_proveedor();
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

if(isset($_GET["i2"])){
    $obj->no_tiene_order($_GET["i2"]);
    $d=$obj->get_requi($_GET["i2"]);
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
    

if(isset($d[0]["Estadodoc"])){
    if($d[0]["Estadodoc"]==2){
        $imp="disabled";
        $sel="disabled='disabled'";
        $bt="";
    }else{
        $bt="abrir";
    }
}else{
    $bt="abrir";
}


require_once"view/Entradas.phtml";

?>
