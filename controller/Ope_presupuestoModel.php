<?php
class Presupuesto extends Conexion{
    
    private $requi;
    private $detalle;
    private $persona;
    private $producto;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->persona=array();
        $this->producto=array();
    }

    
    public function nuevo_registro(){
        $nuevo;
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 AS nuevo 
                FROM Ope_presupuesto 
                WHERE Centrocosto=".$_SESSION["id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $nuevo=$row["nuevo"];
            $estado=1;
            if($nuevo==NULL){
                $nuevo=1;
                $query="INSERT INTO Ope_presupuesto(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                values($nuevo,now(),'".$_SESSION["cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",".$_SESSION["id_centro"].");";
                mysql_query($query);
                return (INT)$nuevo;
            }else{
                $nuevo=$row["nuevo"];
                $query="INSERT INTO Ope_presupuesto(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                values($nuevo,now(),'".$_SESSION["cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",".$_SESSION["id_centro"].");";
                mysql_query($query);
                return (INT)$nuevo;
            }
        }
    }
    
    public function get_persona_sol(){
        Conexion::conectar();
        $sql="SELECT nombre,apellido from G_personaSolicitud";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->persona[]='"'.$row["nombre"].'-'.$row["apellido"].'"';
        }    
        return $this->persona;    
    }

    public function get_productos(){
        Conexion::conectar();
        $sql="SELECT Descripcion from G_producto";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->producto[]='"'.$row["Descripcion"].'"';
        }    
        return $this->producto;    
    }

    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT a.Id,a.Consecutivo,a.Fr,g.Lista,a.Estadodoc,Fentrega,s.Nombre,Centrocosto,Observaciones,Lentrega,Centro_costo,
        Ps,Bloqueado 
        FROM 
        Alm_requi as a 
        JOIN Usuarios_centro as u ON a.Ur=u.id_usuario   
        JOIN G_listas as g ON a.Estadodoc=g.Id_lista
        JOIN G_sucursales as s ON a.Sucursal=s.Id
        WHERE 
        Consecutivo=$con 
        AND 
        Centrocosto=".$_SESSION["id_centro"];

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        $Fentrega=date("Y-m-d-",strtotime($_POST["Fentrega"]));
        $sql="UPDATE Alm_requi set 
        Fentrega='".$Fentrega."',Ps='".$_POST["Ps"]."',Observaciones='".$_POST["Observaciones"]."',Lentrega='".$_POST["Lentrega"]."' 
        where Consecutivo=".$_POST["Id"]." and Centrocosto=".$_SESSION["id_centro"];
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id"]);
    }
    
    public function guardar_detalle(){
        Conexion::conectar();
        
        $neto=(FLOAT)$_POST["Vu"]*(FLOAT)$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Imp"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["cedula"]."','".$_POST["Producto"]."',".$_POST["Cantidad"].",
        ".$_POST["Vu"].",".$_POST["Imp"].",".$neto.",".$total.",".$_SESSION["id_centro"].")";
        
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);
    }

     public function copiar($id){
        Conexion::conectar();
        $sql="SELECT Id_Requisicion,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_requidet 
                    WHERE 
                    Id=".$id;
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $query="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,
                Vn,Vt,Centrocosto) VALUES(".$row['Id_Requisicion'].",now(),".$_SESSION["cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["id_centro"].")";
            
            mysql_query($query);
            header("Location:".Conexion::ruta()."Requisicion?b=".$row["Id_Requisicion"]);
        }
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet where Id_Requisicion=$id and Centrocosto=".$_SESSION["id_centro"];
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
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="UPDATE Alm_requidet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
        Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"];
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);   

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
        inner join
        G_listas as l
        on
        c.No=l.Id_control
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
