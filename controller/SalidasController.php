<?php
require_once("model/EntradasModel.php");
require_once("model/SalidasModel.php");
require_once("model/UsuariosModel.php");
require_once("model/MenuModel.php");
ob_start();
$user=new usuarios;
$menu=new Menu;
$obj=new Salidas;
$obj2=new Entradas;
$d=array();
$detalle=array();

//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$listas=$obj->get_tipo();
$estado=$obj->get_tipo2();
$destino=$obj->get_destino();

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}




$producto=$obj2->get_productos();
$productos=implode(",", $producto);

$proyecto=$obj->get_proyectos();
$proyectos=implode(",", $proyecto);

$personal=$obj->get_personal();
$personales=implode(",", $personal);



if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){

    $obj->guardar_registro();
    
    exit;
}
if(isset($_POST["grabar"]) and $_POST["grabar"]=="si2"){
    $obj->guardar_sin_order();
    exit;
}

if(isset($_POST["f_salidas"]) and $_POST["f_salidas"]=="si"){
    $obj->guardar_detalle();    
    exit;
}
if(isset($_POST["f_salidas"]) and $_POST["f_salidas"]=="si2"){
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
    if(!empty($_GET["b"])){
    $d=$obj->get_requi($_GET["b"]);
    }
}
if(isset($_GET["error"])){
    if(!empty($_GET["error"])){
    $d=$obj->get_requi($_GET["error"]);
    }
}

if(isset($_GET["i"])){
    $obj->imprime_documento($_GET["i"]);
    $d=$obj->get_requi($_GET["i"]);
}

if(isset($_GET["ed"])){
    $detalle_id=$obj->get_detalle_por_id($_GET["ed"],$_GET["b"]);
}

if(isset($_GET["Con"])){
    $obj->copiar($_GET["Con"]);
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


require_once"view/Salidas.phtml";
?>