<?php
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
require_once("model/Caja_menorModel.php");
require_once("model/Alm_vehiModel.php");
ob_start();

$obj=new C_menor;
$user=new usuarios;
$menu=new Menu;
$vehi=new Alm_vehi;
$d=array();
$detalle=array();
//$principal=$menu->get_principal_user($_SESSION["Cedula"]);
$principal=$menu->get_principal();
$res=$user->get_centro($_SESSION["Cedula"]);

$personal=$vehi->get_personal();
$personales=implode(",", $personal);

$Proveerdores=$obj->get_proveedores();
$Proveerdoresb=implode(",", $Proveerdores);

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
if(isset($_POST["a_grabar_formu"]) and $_POST["a_grabar_formu"]=="si"){
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
    $obj->copiar($_GET["Con"]);
}

if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Consecutivo"]);
    }


require_once("view/Cont_caja.phtml");

?>