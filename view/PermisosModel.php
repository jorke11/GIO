<?php
class Permisos extends Conexion{
    
    private $requi;
    private $detalle;
    private $centro;
    private $centro2;

    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->centro=array();
        $this->centro2=array();
    }
   
    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT *
        from 
        Usuarios
        WHERE
        Cedula='$con'";

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_centro(){
        Conexion::conectar();

        $sql="SELECT Id,Nombre
        from 
        G_centros_costo";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->centro[]=$row;
        }

        return $this->centro;    
    }

    public function get_centro_user($cedula){
        Conexion::conectar();

        $sql="SELECT id_centro,Centro_costo
        from Usuarios_centro
        WHERE Id_usuario=".$cedula;

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->centro2[]=$row;
        }

        return $this->centro2;    
    }

    
    public function guardar_registro(){
        Conexion::conectar();
        if(isset($_POST["principal"])){
            print_r($_POST["principal"]);
        }
        
        if(isset($_POST["submenu"])){
            print_r($_POST["Submenu"]);
        }

        if(isset($_POST["Centro"])){
            print_r($_POST["Centro"]);
        }
    }
    
    public function guardar_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;
        $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["cedula"]."','".$_POST["producto"]."',".$_POST["cantidad"].",
        ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.",".$_SESSION["id_centro"].")";
        
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

