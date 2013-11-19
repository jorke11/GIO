<?php
class MovEquipo extends Conexion{
    
    private $requi;
    private $detalle;
    private $persona;
    private $producto;
    private $estadodoc;
    private $serial;
    private $equipos;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->persona=array();
        $this->producto=array();
        $this->estadodoc=array();
        $this->serial=array();
        $this->equipos=array();
    }


    public function get_EstadoDoc(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No,l.Id_control 
                FROM G_listas l
                JOIN G_controles c ON c.No=l.Id_control
                WHERE Id_control=3";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->serial[]=$row;
        }

        return $this->serial;
    }

    public function get_seriales(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT Serial
                FROM Alm_equi";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->estadodoc[]=$row;
        }

        return $this->estadodoc;
    }

    public function get_equipos(){
        Conexion::conectar();

        $sql="SELECT Serial
                FROM Alm_equi";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            
            $this->equipos[]='"'.$row["Serial"].'"';
        }

        return $this->equipos;
    }

    
    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo 
                    FROM Alm_equimov 
                    WHERE Centrocosto=".$_SESSION["Id_centro"];

        $res=mysql_query($sql);
        print_r($_POST);exit;
        if($row=mysql_fetch_array($res)){
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
            
            $query="INSERT INTO Alm_equimov(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                VALUES($nuevo,now(),'".$_SESSION["Cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",
                    ".$_SESSION["Id_centro"].");";
                mysql_query($query);

            // echo "<script>location.href='".Conexion::ruta()."Alm_equimov?b=".$nuevo."'</script>";    
            header("Location:".Conexion::ruta()."Alm_equimov?b=".$nuevo);
        }
    }




    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT Equi.Id,Equi.Consecutivo,Equi.Fecha,Equi.Festadodoc,Equi.Localizacion,Equi.Destino,
                        (SELECT Lista
                            FROM G_listas
                            WHERE Id_control=1
                            AND Numeracion=Equi.Estadodoc) Estadodoc
            FROM Alm_equimov  AS Equi
            JOIN G_centros_costo AS Centros ON Equi.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON Equi.Sucursal=Sucursal.Id
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

    
            $Fentrega=(isset($_POST["Fecha"]))?date("Y/m/d"):date("Y/m/d",strtotime($_POST["Fecha"]));

            $sql="UPDATE Alm_equimov SET Fecha='".$Fentrega."',Localizacion='{$_POST["Localizacion"]}',
            Estadodoc=4,Destino='{$_POST["Destino"]}'
            WHERE Consecutivo=".$_POST["Id"]." 
            AND Centrocosto=".$_SESSION["Id_centro"];
            mysql_query($sql);

            header("Location:".Conexion::ruta()."Alm_equimov?b=".$_POST["Id"]);
        
    }
    
    public function guardar_detalle(){
        Conexion::conectar();
            
            $sql="INSERT INTO Alm_movdet(Consecutivo,Fr,Ur,Serial,Movimiento,Centrocosto)";
            $sql.="values(".$_POST["Id_mov"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Serial"]."',
                '{$_POST["Movimiento"]}',".$_SESSION["Id_centro"].")";
                

            mysql_query($sql);
            header("Location:".Conexion::ruta()."Alm_equimov?b=".$_POST["Id_mov"]);
        

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
        Conexion::conectar();
        echo $sql="SELECT * 
                FROM Alm_movdet 
                WHERE Consecutivo=$id 
                AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
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
            $sql="UPDATE Alm_movdet SET Serial='".$_POST["Serial"]."',Movimiento='".$_POST["Movimiento"]."'
                        WHERE Id={$_POST["Id"]}";
            // mysql_query($sql);
            // header("Location:".Conexion::ruta()."Alm_equimov?b=".$_POST["Id_mov"]);   

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_movdet where Id=".$id;
        mysql_query($sql);
    }

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_requi set Estadodoc=2 where Consecutivo=$id";
        mysql_query($sql);
    }
    
}

?>
