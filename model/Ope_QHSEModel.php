<?php
class Requisicion extends Conexion{
    
    private $requi;
    private $detalle;
    private $persona;
    private $producto;
    private $estadodoc;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->persona=array();
        $this->producto=array();
        $this->estadodoc=array();
    }

    public function get_EstadoDoc(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No,l.Id_control 
                FROM G_listas l
                JOIN G_controles c ON c.No=l.Id_control
                WHERE Id_control=3";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->estadodoc[]=$row;
        }

        return $this->estadodoc;
    }

    
    public function nuevo_registro(){
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo 
                    FROM Alm_requi 
                    WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $estado=1;
            $nuevo=($nuevo==NULL)?1:$row["nuevo"];
            
            $query="INSERT INTO Alm_requi(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                VALUES($nuevo,now(),'".$_SESSION["Cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",
                    ".$_SESSION["Id_centro"].");";
                mysql_query($query);
            header("Location:".Conexion::ruta()."Requisicion?b=".$nuevo);
        }
    }
    
    public function get_persona_sol(){
        Conexion::conectar();
        $sql="SELECT nombre,apellido,Cedula 
                FROM G_personaSolicitud";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->persona[]='"'.$row["nombre"].' '.$row["apellido"].','.$row["Cedula"].'"';
        }    
        return $this->persona;    
    }

    public function get_productos(){
        Conexion::conectar();
        $sql="SELECT Descripcion,Codigo from G_producto";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->producto[]='"'.$row["Codigo"].','.$row["Descripcion"].'"';
        }    
        return $this->producto;    
    }

    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT Centros.Nombre AS Centro,Requi.Consecutivo,Requi.Id,Requi.Fr,Requi.Observaciones,Requi.Fentrega,
        Requi.Lentrega,Requi.Estadoproceso,(SELECT Lista 
                                                    FROM G_listas
                                                    WHERE Id_control=1
                                                    AND Numeracion=Requi.Estadodoc) as Estadodoc,
        Sucursal.Nombre as Sucursal,Requi.Ps,Requi.Estadodoc as Id_estado
            FROM Alm_requi  AS Requi
            JOIN G_centros_costo AS Centros ON Requi.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON Requi.Sucursal=Sucursal.Id
            WHERE Consecutivo=$con AND Centrocosto={$_SESSION["Id_centro"]}
        ";

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        
        Conexion::conectar();
        

        if(stripos($_POST["Ps"], ",")===false){
            $personal=$_POST["Ps"];
            $sqlPersonal="WHERE Cedula='{$personal}'";   
        }else{
            $d=explode(",",$_POST["Ps"]);    
            $personal=$d[1];
            $sqlPersonal="WHERE Cedula='{$personal}'";
        }

        if($_POST["Ps"]=="No existe"){
            $sqlPersonal="";
            $personal="";
        }
        
        
        $buscarPersona="SELECT * 
                            FROM G_personaSolicitud
                            $sqlPersonal
        ";
        $resBusqueda=mysql_query($buscarPersona);

        if(mysql_num_rows($resBusqueda) > 0){
            $Fentrega=(isset($_POST["Fentrega"]))?date("Y-m-d",strtotime($_POST["Fentrega"])):date("Y-m-d");

            $sql="UPDATE Alm_requi SET Fentrega='".$Fentrega."',Ps='".$personal."',
            Observaciones='".$_POST["Observaciones"]."',Estadodoc=4
            Lentrega='".$_POST["Lentrega"]."',Estadoproceso=".$_POST["Estadoproceso"]." 
            WHERE Consecutivo=".$_POST["Id"]." AND Centrocosto=".$_SESSION["Id_centro"];
            mysql_query($sql);

            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id"]);
        }else{
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id"]."&error=1");
        }

        
    }
    
    public function guardar_detalle(){
        Conexion::conectar();
        
        $neto=(FLOAT)$_POST["Vu"]*(FLOAT)$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Imp"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $producto=explode(",",$_POST["Producto"]);

        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto[0]}'
        ";
        $resBusqueda=mysql_query($sqlBusqueda);
        if(mysql_num_rows($resBusqueda) > 0){
            $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
            $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Producto"]."',
                ".$_POST["Cantidad"].",".$_POST["Vu"].",".$_POST["Imp"].",".$neto.",".$total.",".$_SESSION["Id_centro"].")";
        
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);
        }else{
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]."&error=2");
        }

        
    }

     public function copiar($id){
        Conexion::conectar();
        $sql="SELECT Id_Requisicion,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_requidet 
                    WHERE 
                    Id=".$id;
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
        $producto=explode(",", $row["Producto"]);
        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto[0]}'
        ";
        $resBusqueda=mysql_query($sqlBusqueda);
            if(mysql_num_rows($resBusqueda)){
                $query="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,
                    Vn,Vt,Centrocosto) VALUES(".$row['Id_Requisicion'].",now(),".$_SESSION["Cedula"].",'".$row["Producto"]."',
                    ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                    ".$row["Vt"].",".$_SESSION["Id_centro"].")";
                
                mysql_query($query);
                header("Location:".Conexion::ruta()."Requisicion?b=".$row["Id_Requisicion"]);   
            }else{
                header("Location:".Conexion::ruta()."Requisicion?b=".$row["Id_Requisicion"]."&error=3");   
            }

             
        }
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet where Id_Requisicion=$id and Centrocosto=".$_SESSION["Id_centro"];
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }

    public function get_detalle_por_id($id_ent,$id_requi){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet where Id_Requisicion=$id_requi and Id=$id_ent";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }


    public function edit_detalle(){
        Conexion::conectar();
        $neto=$_POST["Vu"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Imp"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $producto=explode(",", $_POST["Producto"]);
        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto[0]}'
                        
        ";
        $resBusqueda=mysql_query($sqlBusqueda);
        if(mysql_num_rows($resBusqueda)){
            $sql="UPDATE Alm_requidet set Producto='".$_POST["Producto"]."',Cantidad=".$_POST["Cantidad"].",
            Vu=".$_POST["Vu"].",Imp=".$_POST["Imp"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"];
            
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);   
        }else{
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]."&error=2");
        }

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_requidet where Id=".$id;
        mysql_query($sql);
    }

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_requi set Estadodoc=2 where Consecutivo=$id";
        mysql_query($sql);
    }
    
    public function get_tipo(){
        Conexion::conectar();
        $sql="SELECT * 
        from G_controles as c
        JOIN G_listas as l ON c.No=l.Id_control
        where Id_control=1
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tipo[]=$row;
        }
        return $this->tipo;
    }
    
}

?>
