<?php
class Subcontratista extends Conexion{
    
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
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 AS nuevo 
                FROM Ope_Contratistascc 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $estado=1;

            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];

                $query="INSERT INTO Ope_Contratistascc(Consecutivo,Fr,Ur,Centrocosto)
                values(".$nuevo.",now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_centro"].");";
                mysql_query($query);
                
                echo "<script>location.href='".Conexion::ruta()."Ope_SubCont?b=".$nuevo."'</script>";
        }
    }
    

    public function get_contratistas($con){
        Conexion::conectar();

        $sql="SELECT Con.Consecutivo,Centro.Nombre Centro,Con.Idcontratista,Con.Observaciones
                FROM Ope_Contratistascc Con
                JOIN G_centros_costo Centro ON Con.Centrocosto=Centro.Id
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
        
        $sql="UPDATE Ope_Contratistascc set Idcontratista=".$_POST["Idcontratista"].",
                Observaciones='".$_POST["Observaciones"]."'
                WHERE Consecutivo=".$_POST["Id"]." 
                AND Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_SubCont?b=".$_POST["Id"]."'</script>";
    }
    
    public function guardar_detalle(){
        Conexion::conectar();
        
        $neto=(FLOAT)$_POST["Vu"]*(FLOAT)$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Imp"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Producto"]."',".$_POST["Cantidad"].",
        ".$_POST["Vu"].",".$_POST["Imp"].",".$neto.",".$total.",".$_SESSION["Id_centro"].")";
        
        
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
                Vn,Vt,Centrocosto) VALUES(".$row['Id_Requisicion'].",now(),".$_SESSION["Cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["Id_centro"].")";
            
            mysql_query($query);
            header("Location:".Conexion::ruta()."Requisicion?b=".$row["Id_Requisicion"]);
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
