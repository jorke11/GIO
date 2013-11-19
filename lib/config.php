<?php
session_start();
class Conexion {


public function conectar(){
    $arcConexion="GIO.ini";
    
    $datos = parse_ini_file($arcConexion,true);
    $conexion=mysql_connect($datos["database_host"],$datos["database_user"],$datos["database_pass"]) or die('error al conectar');
    mysql_select_db($datos["database_name"]) or die('error al conectar con la base de datos');

    return $conexion;
}


public function c_mysqli(){
	$arcConexion="GIO.ini";
    
    $datos = parse_ini_file($arcConexion,true);
    $mysqli=new mysqli($datos["database_host"],$datos["database_user"],$datos["database_pass"]);
    $mysqli->select_db($datos["database_name"]);
    return $mysqli;
}

public function ruta(){
    $arcRuta="admin.ini";
    
    $ruta = parse_ini_file($arcRuta,true);
    return $ruta["ruta"];
}

public function ConvertirFecha($fecha){

        if(stristr($fecha, '/') === FALSE) {
            $fechaconver=$fecha;
        }else{
            $var=explode("/",$fecha);    
            $fechaconver=$var[2]."/".$var[1]."/".$var[0];    
        }
        
        return $fechaconver;
}

public function ConvertirFechaHora($fecha){
        if(stristr($fecha, '/') === FALSE) {
            $fechaconver=$fecha . "00:00";
        }else{
            $var=explode("/",$fecha);    
            $fechaconver=$var[2]."/".$var[1]."/".$var[0]." 00:00";    
        }
        
        return $fechaconver;
}

public function LetraCentros($num){
        Conexion::conectar();
        $nombre="";
        $sql="SELECT Id,Nombre
                FROM G_centros_costo";
        $res=mysql_query($sql);
        
        while($valor=mysql_fetch_array($res)){
            if($valor["Id"]==$num){
                $nombre=$valor["Nombre"];
            }    
        }
        
        return $nombre;
    }

public function LetraRoles($num){
        Conexion::conectar();
        $rol="";
        $sql="SELECT Id,Rol
                FROM G_roles";
        $res=mysql_query($sql);
        
        while($valor=mysql_fetch_array($res)){
            if($valor["Id"]==$num){
                $rol=$valor["Rol"];
            }    
        }
        
        return $rol;
    }

}

?>