<?php

require_once("model/Log_equiModel.php");
require_once("model/Log_transModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
require_once("model/Ope_apuModel.php");

$obj=new Apu;
$menu=new Menu;
$obj1=new Log_vehi;
$obj2=new Log_equi;
$user=new usuarios;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();

$prod=$obj2->get_productos();
$productos=implode(",", $prod);

$cod=$obj->get_materialesdet();
$codigos=implode(",", $cod);

$codmante=$obj->get_mantedet();
$codigos_mt=implode(",", $codmante);

$trans=$obj->get_transdet();
$transportes=implode(",", $trans);

$equi=$obj->get_equipodet();
$equipos=implode(",", $equi);

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


if(isset($_POST["grabar"]) and $_POST["grabar"]=="si"){
    $obj->guardar_registro();
}

if(isset($_GET["Con"])){
    $d=$obj->get_requi($_GET["Con"]);
    switch ($_GET["tipo"]) {
        
        case 'Materiales':
            $con="display:inline;";
            $via="display:none;";
            $tan="display:none;";
            $par="display:none;";
            
            $detalle=$obj->get_materiales($_GET["Con"]);
            break;
            
         case 'Mantenimientos':
            $con="display:none;";
            $via="display:inline;";
            $tan="display:none;";
            $par="display:none;";
            
            $detalle1=$obj->get_mantenimientos($_GET["Con"]);

            break;
            
         case 'Transportes':
            $con="display:none;";
            $via="display:none;";
            $tan="display:inline;";
            $par="display:none;";
            
            $detalle2=$obj->get_transportes($_GET["Con"]);

            break;
            
         case 'Equipos':
            $con="display:none;";
            $via="display:none;";
            $tan="display:none;";
            $par="display:inline;";
            
            $detalle3=$obj->get_equipos($_GET["Con"]);
            break;
            default:
            # code...
            break;
            
    }

}else{
    $con="display:none;";
    $via="display:none;";
    $tan="display:none;";
    $par="display:none;";
}

/*FORMULARIO DE MATERIALES*/

if(isset($_POST["f_materiales"]) and $_POST["f_materiales"]=="si"){
    $obj->guardar_materiales();    
    exit;
}

if(isset($_POST["f_materiales"]) and $_POST["f_materiales"]=="si2"){
    $obj->edit_materiales();
    exit;
}

if(isset($_POST["d_materiales"]) and $_POST["d_materiales"]=="si"){
    $obj->crear_materiales();
    exit;
}


/*FORMULARIO DE MANTENIMIENTO*/
if(isset($_POST["f_mantenimiento"]) and $_POST["f_mantenimiento"]=="si"){
    $obj->guardar_mantenimientos();
    exit;
}
if(isset($_POST["f_mantenimiento"]) and $_POST["f_mantenimiento"]=="si2"){
    $obj->edit_mantenimientos();
    exit;
}

if(isset($_POST["d_mantenimiento"]) and $_POST["d_mantenimiento"]=="si"){
    $obj->crear_mante();
    exit;
}

/*FORMULARIO DE TRANSPORTES*/
if(isset($_POST["f_transporte"]) and $_POST["f_transporte"]=="si"){
    $obj->guardar_transportes();
    exit;
}
if(isset($_POST["f_transporte"]) and $_POST["f_transporte"]=="si2"){
    $obj->edit_transporte();
    exit;
}
if(isset($_POST["d_transportes"]) and $_POST["d_transportes"]=="si"){
    $obj->crear_transporte();
    exit;
}

/*FORMULARIO DE EQUIPOS*/
if(isset($_POST["f_equipos"]) and $_POST["f_equipos"]=="si"){
    $obj->guardar_equipos();
    exit;
}
if(isset($_POST["f_equipos"]) and $_POST["f_equipos"]=="si2"){
    $obj->edit_equipos();
    exit;
}

if(isset($_POST["d_equipos"]) and $_POST["d_equipos"]=="si"){
    $obj->crear_equipos();
    exit;
}


if(isset($_GET["e"])){
    switch ($_GET["tipo"]) {
        case 'Materiales':
            $obj->elimina_registro($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Mantenimientos':
            $obj->elimina_registro($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Transportes':
            $obj->elimina_registro($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Equipos':
            $obj->elimina_registro($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        default:
            # code...
            break;
    }
    
}

if(isset($_GET["Cop"]) and !empty($_GET["Cop"]) and isset($_GET["id"])){
    $obj->copiar_registro();
}


if(isset($_GET["n"])){
    $obj->nuevo_registro();    
}

if(isset($_GET["b"])){
    $d=$obj->get_requi($_GET["b"]);
        
}


require_once"view/Ope_apu.phtml";
?>