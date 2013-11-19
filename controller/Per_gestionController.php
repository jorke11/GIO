<?php
require_once("model/Per_gestionModel.php");
require_once("model/MenuModel.php");
require_once("model/UsuariosModel.php");
ob_start();
$menu=new Menu;

$user=new usuarios;
$obj=new Per_gestion;
//$principal=$menu->get_principal_user($_SESSION["cedula"]);
$principal=$menu->get_principal();

$productos=$obj->get_productos();

$sexo=$obj->get_sexo();
$tausencia=$obj->get_tipoAusencia();
$tbeneficiario=$obj->get_tipobeneficiario();
$tdotacion=$obj->get_tipodotacion();
$testudio=$obj->get_tipoestudio();
$tpagos=$obj->get_tipopagos();
$treconocimiento=$obj->get_tiporeconocimiento();
$texamen=$obj->get_tipoexamen();

if(isset($_SESSION["Cedula"])){
    $datos["Cedula"]=$_SESSION["Cedula"];
    $datos["Id_centro"]=$_SESSION["Id_centro"];
    $centro=$user->get_centro($datos);
}


if(isset($_GET["Cop"]) and !empty($_GET["Cop"]) and isset($_GET["id"])){
    
    $obj->copiar_registro();
}


if(isset($_GET["Con"]) and !empty($_GET["Con"])){
      $d=$obj->get_requi2($_GET["Con"]);

    switch ($_GET["tipo"]) {
        case 'Ausencia':
            $aus="display:inline;";
            $ben="display:none;";
            $dot="display:none;";
            $est="display:none;";
            $his="display:none;";
            $pag="display:none;";
            $exa="display:none;";
            $rec="display:none;";
            $detalle=$obj->get_ausencia($d[0]["Consecutivo"]);            
            break;

         case 'Beneficiarios':
            $aus="display:none;";
            $ben="display:inline;";
            $dot="display:none;";
            $est="display:none;";
            $his="display:none;";
            $pag="display:none;";
            $exa="display:none;";
            $rec="display:none;";
            $detalle1=$obj->get_ben($d[0]["Consecutivo"]);

            break;

         case 'Dotacion':
            $aus="display:none;";
            $ben="display:none;";
            $dot="display:inline;";
            $est="display:none;";
            $his="display:none;";
            $pag="display:none;";
            $exa="display:none;";
            $rec="display:none;";
            $detalle2=$obj->get_dotacion($d[0]["Consecutivo"]);
            break;

         case 'Estudios':
            $aus="display:none;";
            $ben="display:none;";
            $dot="display:none;";
            $est="display:inline;";
            $his="display:none;";
            $pag="display:none;";
            $exa="display:none;";
            $rec="display:none;";
            $detalle3=$obj->get_estudios($d[0]["Consecutivo"]);
            break;

        case 'Historial':
            $aus="display:none;";
            $ben="display:none;";
            $dot="display:none;";
            $est="display:none;";
            $his="display:inline;";
            $pag="display:none;";
            $exa="display:none;";
            $rec="display:none;";
            $detalle4=$obj->get_historial($d[0]["Consecutivo"]);
            break;

        case 'Pagos':
            $aus="display:none;";
            $ben="display:none;";
            $dot="display:none;";
            $est="display:none;";
            $his="display:none;";
            $pag="display:inline;";
            $exa="display:none;";
            $rec="display:none;";
            $detalle5=$obj->get_pagos($d[0]["Consecutivo"]);
            break;
        case 'Reconocimiento':
            $aus="display:none;";
            $ben="display:none;";
            $dot="display:none;";
            $est="display:none;";
            $his="display:none;";
            $pag="display:none;";
            $rec="display:inline;";
            $exa="display:none;";
            $detalle6=$obj->get_reconocimiento($d[0]["Consecutivo"]);
            break;
        case 'Examen':
            $aus="display:none;";
            $ben="display:none;";
            $dot="display:none;";
            $est="display:none;";
            $his="display:none;";
            $pag="display:none;";
            $rec="display:none;";
            $exa="display:inline;";
            $detalle7=$obj->get_examen($d[0]["Consecutivo"]);
            break;
    }
}else{
    $aus="display:none;";
    $ben="display:none;";
    $dot="display:none;";
    $est="display:none;";
    $his="display:none;";
    $pag="display:none;";
    $rec="display:none;";
    $exa="display:none;";
}

/*
*FORMULARIO DE AUSENCIA
*/

if(isset($_POST["f_ausencia"]) and $_POST["f_ausencia"]=="si"){
    $obj->guardar_ausencia();    
    exit;
}
if(isset($_POST["f_ausencia"]) and $_POST["f_ausencia"]=="si2"){
    $obj->editar_ausencia();    
    exit;
}
/*
*FIN AUSENCIA
*/

/*
*FORMULACION BENEFICIARIOS
*/
if(isset($_POST["f_ben"]) and $_POST["f_ben"]=="si"){
    $obj->guardar_beneficiarios();    
    exit;
}
if(isset($_POST["f_ben"]) and $_POST["f_ben"]=="si2"){
    $obj->editar_beneficiarios();    
    exit;
}
/*
*FIN BENEFICIARIOS
*/

/*
*FORMULARIO DOTACION
*/
if(isset($_POST["f_dotacion"]) and $_POST["f_dotacion"]=="si"){
    $obj->guardar_dotacion();    
    exit;
}
if(isset($_POST["f_dotacion"]) and $_POST["f_dotacion"]=="si2"){
    $obj->editar_dotacion();    
    exit;
}
/*
*FIN DOTACION
*/

/*
*FORMULARIO ESTUDIOS
*/
if(isset($_POST["f_estudios"]) and $_POST["f_estudios"]=="si"){
    $obj->guardar_estudios();    
    exit;
}
if(isset($_POST["f_estudios"]) and $_POST["f_estudios"]=="si2"){
    $obj->editar_estudios();    
    exit;
}

/*
*FIN ESTUDIOS
*/

/*
*FORMULARIO HISTORIAL
*/

if(isset($_POST["f_historial"]) and $_POST["f_historial"]=="si"){
    $obj->guardar_historial();    
    exit;
}

if(isset($_POST["f_historial"]) and $_POST["f_historial"]=="si2"){

    $obj->editar_historial();    
    exit;
}
/*
*FIN HISTORIAL
*/


/*
*FORMULARIO PAGOS
*/

if(isset($_POST["f_pagos"]) and $_POST["f_pagos"]=="si"){
    $obj->guardar_pagos();    
    exit;
}

if(isset($_POST["f_pagos"]) and $_POST["f_pagos"]=="si2"){
    $obj->editar_pagos();    
    exit;
}

/*
*FIN PAGOS
*/

/*
*FORMULARIO RECONOCIMIENTO
*/

if(isset($_POST["f_reconocimiento"]) and $_POST["f_reconocimiento"]=="si"){
    $obj->guardar_reconocimiento();    
    exit;
}

if(isset($_POST["f_reconocimiento"]) and $_POST["f_reconocimiento"]=="si2"){
    $obj->editar_reconocimiento();    
    exit;
}
/*
*FIN RECONOCIMIENTO
*/

/*
*FORMULARIO EXAMEN
*/

if(isset($_POST["f_examen"]) and $_POST["f_examen"]=="si"){
    $obj->guardar_examen();    
    exit;
}

if(isset($_POST["f_examen"]) and $_POST["f_examen"]=="si2"){
    $obj->editar_examen();    
    exit;
}
/*
*FIN EXAMEN
*/





if(isset($_GET["e"])){
    switch ($_GET["tipo"]) {
        case 'Ausencia':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Beneficiarios':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Dotacion':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Estudios':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Historial':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Pagos':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Examen':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;
        case 'Reconocimiento':
            $obj->elimina_detalle($_GET["e"],$_GET["c"],$_GET["tipo"],$_GET["id_au"]);
            break;    
        default:
            # code...
            break;
    }
    
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

require_once"view/Per_gestion.phtml";
?>