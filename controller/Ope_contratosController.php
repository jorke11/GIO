<?php
require_once("model/RequisicionesModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
require_once("model/Ope_contratosModel.php");
ob_start();
$obj=new Requisicion;
$user=new usuarios;
$menu=new Menu;
$con=new Contratos;
$d=array();
$detalle=array();
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$listas=$obj->get_tipo();
$res=$user->get_centro($_SESSION["Cedula"]);

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}

if(isset($_POST["f_agregar"]) and $_POST["f_agregar"]=="si"){
    $con->agregar_contratista();
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

if(isset($d[0]["Estadodoc"])){
if($d[0]["Estadodoc"]==2){
    $imp="disabled";
    $bt="";
}else{
    $bt="abrir";
}

}

$contratistas=$con->get_contratistas();
$liquidacion=array();


require_once("view/Ope_contratos.phtml");

?>