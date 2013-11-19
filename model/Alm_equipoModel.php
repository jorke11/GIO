<?php
class Alm_equipo extends Conexion{
    
    private $requi;
    private $detalle;
    private $proyecto;
    private $marca;
    private $modelo;
    private $TipoVehiculo;
    private $propiedad;
    private $localizacion;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->proyecto=array();
        $this->marca=array();
        $this->modelo=array();
        $this->TipoVehiculo=array();
        $this->propiedad=array();
        $this->localizacion=array();
    }

    public function get_marca(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles AS c
        JOIN G_listas AS l ON c.No=l.Id_control
        WHERE Id_control=6
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->marca[]=$row;
        }
        return $this->marca;
    }

    public function get_propiedad(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles AS c
        JOIN G_listas AS l ON c.No=l.Id_control
        WHERE Id_control=11
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->propiedad[]=$row;
        }
        return $this->propiedad;
    }

    public function get_localizacion(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles AS c
        JOIN G_listas AS l ON c.No=l.Id_control
        WHERE Id_control=12
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->localizacion[]=$row;
        }
        return $this->localizacion;
    }

    public function get_modelo(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles AS c
        JOIN G_listas AS l ON c.No=l.Id_control
        WHERE Id_control=7
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->modelo[]=$row;
        }
        return $this->modelo;
    }

    public function get_tipoVehiculo(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles AS c
        JOIN G_listas AS l ON c.No=l.Id_control
        WHERE Id_control=8
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->TipoVehiculo[]=$row;
        }
        return $this->TipoVehiculo;
    }

    public function get_proyectos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT No,Nombre from G_proyectos";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
            $this->proyecto[]='"'.$row["No"].'-'.$row["Nombre"].'"';
        }

        return $this->proyecto;
        $mysqli->close();
    }


    public function nuevo_registro(){
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo  from Alm_equi where Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
                
            $query="INSERT INTO Alm_equi(Consecutivo,Fr,Ur,Sucursal,Centrocosto)
                values($nuevo,now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",".$_SESSION["Id_centro"].");";
                mysql_query($query) or die(mysql_error());
                
                header("Location:".Conexion::ruta()."Alm_Equipos?b=".$nuevo);
        }

    }
    
    public function get_requi($con){
        Conexion::conectar();
       $sql="SELECT Centro.Nombre Centro_costo,Sucursal.Nombre Sucursal,Equi.Consecutivo,Equi.Proyecto,
                    Equi.Serial,Equi.Codigo,Equi.Nombre,Equi.Tipo,Equi.Capacidad,Equi.Valor,Equi.Marca,Equi.Modelo,
					Equi.Propiedad,Equi.Localizacion,Equi.Fabricacion,Equi.Fr,Equi.Declaracion,Equi.Nomotor,
					Equi.Pesocert,Equi.Pesomaq,Equi.Alto,Equi.Ancho,Equi.Largo,Equi.Capacidadg
                    FROM Alm_equi Equi
                    JOIN G_centros_costo Centro ON Equi.Centrocosto=Centro.Id
                    JOIN G_sucursales Sucursal ON Equi.Sucursal=Sucursal.Id
                    WHERE Equi.Codigo=$con
                    AND Centrocosto=".$_SESSION["Id_centro"];

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_requi2($con){
        Conexion::conectar();

        $sql="SELECT Centro.Nombre Centro_costo,Sucursal.Nombre Sucursal,Equi.Consecutivo,Equi.Proyecto,
                    Equi.Serial,Equi.Codigo,Equi.Nombre,Equi.Tipo,Equi.Capacidad,Equi.Valor,Equi.Marca,Equi.Modelo,
					Equi.Propiedad,Equi.Localizacion,Equi.Fabricacion,Equi.Fr,Equi.Declaracion,Equi.Nomotor,
					Equi.Pesocert,Equi.Pesomaq,Equi.Alto,Equi.Ancho,Equi.Largo,Equi.Capacidadg
                    FROM Alm_equi Equi
                    JOIN G_centros_costo Centro ON Equi.Centrocosto=Centro.Id
                    JOIN G_sucursales Sucursal ON Equi.Sucursal=Sucursal.Id

                    WHERE Consecutivo=$con
                    AND Centrocosto=".$_SESSION["Id_centro"];

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        
        $tamano = $_FILES["foto"]['size'];
        $tipo = $_FILES["foto"]['type'];
        $foto = $_FILES["foto"]['name'];

        if ($foto != "") {
            $destino =  "images/fotos/".$foto;

            if (copy($_FILES['foto']['tmp_name'],$destino)) {
                $status = "Archivo subido: <b>".$foto."</b>";
            } else {
                $status = "Error al subir el archivo";
            }
            echo $status;
        }
        
        $Buscaequipo="SELECT Serial,Consecutivo 
                        FROM Alm_equi
                        WHERE Serial='{$_POST["Serial"]}'     
        ";
        $resBusqueda=mysql_query($Buscaequipo);
        $var=mysql_fetch_array($resBusqueda);
        $ser=$var["Serial"];
        $Con=$var["Consecutivo"];

        $signo=0;

        if($signo.mysql_num_rows($resBusqueda) > 0){
            if($_POST["Id"]==$Con and $_POST["Serial"]==$ser){
                $sql="UPDATE Alm_equi SET Serial='".$_POST["Serial"]."',Codigo='".$_POST["Codigo"]."', Marca='".$_POST["Marca"]."', 
                    Modelo='".$_POST["Modelo"]."', Propiedad='".$_POST["Propiedad"]."',
					Localizacion='".$_POST["Localizacion"]."',Proyecto='".$_POST["Proyecto"]."',Nombre='".$_POST["Nombre"]."',
                    Tipo='".$_POST["Tipo"]."',Capacidad='".$_POST["Capacidad"]."',Fabricacion='".$_POST["Fabricacion"]."',
					Declaracion='".$_POST["Declaracion"]."',Nomotor='".$_POST["Nomotor"]."',Pesocert='".$_POST["Pesocert"]."',
					Pesomaq='".$_POST["Pesomaq"]."',Alto='".$_POST["Alto"]."',Ancho='".$_POST["Ancho"]."',Largo='".$_POST["Largo"]."',
                    Capacidadg='".$_POST["Capacidadg"]."',Valor=".$_POST["Valor"].",Foto='".$destino."' 
                    WHERE Consecutivo=".$_POST["Id"]." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
                mysql_query($sql);
                header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id"]);    
            }else{
                header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id"]."&error=1");
            }

            
        }else{
            $sql="UPDATE Alm_equi SET Serial='".$_POST["Serial"]."',Codigo='".$_POST["Codigo"]."', Marca='".$_POST["Marca"]."', 
                    Modelo='".$_POST["Modelo"]."', Propiedad='".$_POST["Propiedad"]."',
                    Localizacion='".$_POST["Localizacion"]."',Proyecto='".$_POST["Proyecto"]."',Nombre='".$_POST["Nombre"]."',
                    Tipo='".$_POST["Tipo"]."',Capacidad='".$_POST["Capacidad"]."',Fabricacion='".$_POST["Fabricacion"]."',
					Declaracion='".$_POST["Declaracion"]."',Nomotor='".$_POST["Nomotor"]."',Pesocert='".$_POST["Pesocert"]."',
					Pesomaq='".$_POST["Pesomaq"]."',Alto='".$_POST["Alto"]."',Ancho='".$_POST["Ancho"]."',Largo='".$_POST["Largo"]."',
                    Valor=".$_POST["Valor"].",Foto='".$destino."' 
                    WHERE Consecutivo=".$_POST["Id"]." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id"]);
            
        }

        
        
    }
  
    
    public function guardar_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;

        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="INSERT INTO Alm_entdet(Id_ent,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt)";
        $sql.="values(".$_POST["Id_ent"].",now(),'".$_SESSION["usuario"]."','".$_POST["producto"]."',".$_POST["cantidad"].",
        ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.")";
        echo $sql;
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id_ent"]);
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_entdet where Id_ent=$id";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }

    public function edit_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="UPDATE Alm_entdet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
        Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"];
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id_ent"]); 

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_entdet where Id=".$id;
        mysql_query($sql);
    }
    

}

?>
