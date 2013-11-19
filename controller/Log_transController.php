<?php
ob_start();
require_once("model/OCModel.php");
require_once("model/Log_equiModel.php");
require_once("model/Log_transModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
require_once("model/EntradasModel.php");

$menu=new Menu;
$obj=new Log_vehi;
$obj2=new Log_equi;
$obj3=new Orden;
$user=new usuarios;
$entradas=new Entradas;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();

$prod=$obj2->get_productos();
$productos=implode(",", $prod);

$proveedor=$obj3->get_proveedor();
$proveedores=implode(",", $proveedor);

$proy=$entradas->get_proyectos();
$proyectos=implode(",", $proy);



$tipoVehiculo=$obj->get_tipoVehiculo();
$tipoVehiculo=implode(",", $tipoVehiculo);

$origen=$obj->get_origen();
$origenes=implode(",", $origen);

$boni=$obj->get_bonificaciones();
$bonificaciones=implode(",", $boni);

$tipoV=$obj->get_tipoViaje();
$tipoViaje=implode(",", $tipoV);

$Centrocosto=$obj->get_Centrocosto();


if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}



if(isset($_GET["Con"])){
    $d=$obj->get_requi2($_GET["Con"]);
    switch ($_GET["tipo"]) {
        
        case 'Consumo':
            $con="display:inline;";
            $via="display:none;";
            $tan="display:none;";
            $par="display:none;";
            
            $detalle=$obj->get_consumo($_GET["Con"]);
            break;
            
         case 'Viajes':
            $con="display:none;";
            $via="display:inline;";
            $tan="display:none;";
            $par="display:none;";
            
            $detalle1=$obj->get_viajes($_GET["Con"]);

            break;
            
         case 'Tanqueadas':
            $con="display:none;";
            $via="display:none;";
            $tan="display:inline;";
            $par="display:none;";
            
            $detalle2=$obj->get_tanqueadas($_GET["Con"]);

            break;
            
         case 'Parqueos':
            $con="display:none;";
            $via="display:none;";
            $tan="display:none;";
            $par="display:inline;";
            
            $detalle3=$obj->get_parqueos($_GET["Con"]);
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
//AGREGAR ORIGENES Y DESTINOS
if(isset($_POST["f_origen"]) and $_POST["f_origen"]=="si"){
    $obj->guardar_origen();    
    exit;
}

//AGREGA BONIFICACIONES
if(isset($_POST["f_bonificacion"]) and $_POST["f_bonificacion"]=="si"){
    $obj->guardar_boni();    
    exit;
}

//AGREGA TIPO VIAJE
if(isset($_POST["f_tipo"]) and $_POST["f_tipo"]=="si"){
    $obj->guardar_tipo();    
    exit;
}

//AGREGA TIPO DE VEHICULOS
if(isset($_POST["f_vehiculo"]) and $_POST["f_vehiculo"]=="si"){
    $obj->guardar_vehiculo();    
    exit;
}





//MANEJO DE CONSUMO
if(isset($_POST["grabar_c"]) and $_POST["grabar_c"]=="si"){
	$obj->guardar_consumo();    
    exit;
}

if(isset($_POST["grabar_c"]) and $_POST["grabar_c"]=="si2"){
    $obj->edit_consumo();
    exit;
}

//FIN DE CONSUMO


//MANEJO DE VIAJES
if(isset($_POST["f_viajes"]) and $_POST["f_viajes"]=="si"){
    $obj->guardar_viajes();
    exit;
}
if(isset($_POST["f_viajes"]) and $_POST["f_viajes"]=="si2"){
    $obj->edit_viajes();
    exit;
}

//FIN VIAJES

//MANERJO TANQUEADAS

if(isset($_POST["f_tanqueadas"]) and $_POST["f_tanqueadas"]=="si"){
    $obj->guardar_tanqueadas();
    exit;
}
if(isset($_POST["f_tanqueadas"]) and $_POST["f_tanqueadas"]=="si2"){
    $obj->edit_tanqueadas();
    exit;
}


//MANEJO PARQUEOS
if(isset($_POST["f_parqueos"]) and $_POST["f_parqueos"]=="si"){
    $obj->guardar_parqueos();
    exit;
}
if(isset($_POST["f_parqueos"]) and $_POST["f_parqueos"]=="si2"){
    $obj->edit_parqueos();

    exit;
}


if(isset($_GET["e"])){
    switch ($_GET["tipo"]) {
        case 'Consumo':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Viajes':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Tanqueadas':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Parqueos':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
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
            $res=$obj->nuevo_registro();    
            $d=$obj->get_requi($res);  
}

if(isset($_GET["b"])){
        $d=$obj->get_requi($_GET["b"]);
        
}


require_once"view/Log_trans.phtml";
?>