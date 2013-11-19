<?php
require_once("model/Per_asistenciaModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$obj=new Requisicion;
$user=new usuarios;
$menu=new Menu;
$d=array();
$detalle=array();
$error=0;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$listas=$obj->get_tipo();
$res=$user->get_centro($_SESSION["Cedula"]);

$prod=$obj->get_productos();
$productos=implode(",", $prod);

$per=$obj->get_persona_sol();

$persona=implode(",", $per);

$control=$obj->get_EstadoDoc();
$Hora=$obj->get_Hora();


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
            $obj->nuevo_registro();      
}

if(isset($_GET["b"])){
    if(isset($_GET["error"])){
        $error=$_GET["error"];
    }

    $d=$obj->get_requi($_GET["b"]);
}

if(isset($_GET["d"])){
    if(isset($_GET["error"])){
        $error=$_GET["error"];
    }

    $d=$obj->get_requib($_GET["d"]);
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


if(!empty($d[0]["Id_estado"])){
    if($d[0]["Id_estado"]==2){
        $imp="disabled";
        $bt="";
    }else{
        $bt="abrir";
    }
}


require_once("view/Per_asistencia.phtml");

?>