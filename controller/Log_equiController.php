<?php
require_once("model/Log_equiModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
require_once("model/OCModel.php");
ob_start();
$menu=new Menu;
$user=new usuarios;
$obj=new Log_equi;
$obj2=new Orden;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();
$d=array();

$prod=$obj->get_productos();
$productos=implode(",", $prod);

$proveedor=$obj2->get_proveedor();
$proveedores=implode(",", $proveedor);

$actividad=$obj->get_actividad();
$actividades=implode(",", $actividad);




if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


if(isset($_GET["Con"]) and !empty($_GET["Con"])){
      $d=$obj->get_requi2($_GET["Con"]);
    switch ($_GET["tipo"]) {
        case 'Consumo':
            $con="display:inline;";
            $tra="display:none;";
            $man="display:none;";
            $eq="display:none;";
          
            $detalle=$obj->get_consumo($d[0]["Consecutivo"]);       

            break;

         case 'Trabajo':
            $con="display:none;";
            $tra="display:inline;";
            $man="display:none;";
            $eq="display:none;";
            $detalle1=$obj->get_trabajo($d[0]["Consecutivo"]);

            break;

         case 'Mantenimiento':
            $con="display:none;";
            $tra="display:none;";
            $man="display:inline;";
            $eq="display:none;";
            $detalle2=$obj->get_mantenimiento($d[0]["Consecutivo"]);
            break;

         case 'Palas':
            $con="display:none;";
            $tra="display:none;";
            $man="display:none;";
            $eq="display:inline;";
            
            $detalle3=$obj->get_palasYfresas($d[0]["Consecutivo"]);
            break;
    }
}else{
    $con="display:none;";
    $tra="display:none;";
    $man="display:none;";
    $eq="display:none;";
}


//GUARDAR ACTIVIDAD

if(isset($_POST["f_actividad"]) and $_POST["f_actividad"]=="si"){
    $obj->guardar_actividad();    
    exit;
}

//MANEJO CONSUMO

if(isset($_POST["grabar_formu"]) and $_POST["grabar_formu"]=="si"){
	$obj->guardar_consumo();    
    exit;
}


if(isset($_POST["grabar_formu"]) and $_POST["grabar_formu"]=="si2"){
    $obj->edit_consumo();    
    exit;
}

//FIN CONSUMO


//GUARDA TRABAJO

if(isset($_POST["f_formulario3"]) and $_POST["f_formulario3"]=="si"){
    $obj->guardar_trabajo();    
    exit;
}

if(isset($_POST["f_formulario3"]) and $_POST["f_formulario3"]=="si2"){
    $obj->edit_trabajo();    
    exit;
}


//FIN TRABAJO

//MANEJO MANTENIMIENTO
if(isset($_POST["f_formulario4"]) and $_POST["f_formulario4"]=="si"){
    $obj->guardar_mantenimiento();    
    exit;
}

if(isset($_POST["f_formulario4"]) and $_POST["f_formulario4"]=="si2"){
    $obj->edit_mante();    
    exit;
}

//MANEJO TRABAJOS
if(isset($_POST["f_trabajos"]) and $_POST["f_trabajos"]=="si"){
    $obj->guardar_tra();
    exit;
}

if(isset($_POST["f_trabajos"]) and $_POST["f_trabajos"]=="si2"){
    $obj->edit_mante();    
    exit;
}



//FIN MANTENIMIENTO


if(isset($_GET["e"])){
    switch ($_GET["tipo"]) {
        case 'Consumo':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Trabajo':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Mantenimiento':
            $obj->elimina_consumo($_GET["e"],$_GET["c"],$_GET["tipo"]);
            break;
        case 'Equipo':
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
    if(!empty($_GET["b"])){
        $d=$obj->get_requi($_GET["b"]);
    }
}

/*if(sizeof($d)>0){
                $detalle=$obj->get_detalle($d[0]["Consecutivo"]);
    }*/



require_once"view/Log_equi.phtml";
?>