<?php
ob_start();
class equimov extends Conexion{
    
    private $requi;
    private $detalle;
    private $tipo;
    private $tipo2;
    private $proveedor;
    private $proyecto;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->tipo=array();
        $this->tipo2=array();
        $this->proveedor=array();
        $this->proyecto=array();
    }


    
    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo  
                FROM Alm_equimov 
                WHERE Centrocosto=".$_SESSION["id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];

                $query="INSERT INTO Alm_equimov(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                values($nuevo,now(),'".$_SESSION["cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",".$_SESSION["id_centro"].");";
                mysql_query($query);
        }
    }
    
    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT *
        from 
        Alm_equimov as a
        inner join
        Usuarios_centro as u
        on
        a.Ur=u.id_usuario
        where 
        Consecutivo=$con 
        and 
        Centrocosto=".$_SESSION["id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        $Fentrega=date("Y/m/d",strtotime($_POST["Fecha"]));

        if(empty($_POST["orden"])){
            $orden=0;
        }else{
            $orden=$_POST["orden"];
        }

        $sql="UPDATE Alm_equimov
        set Localizacion='".$_POST["Localizacion"]."',Fecha='".$Fentrega."',Destino='".$_POST["Destino"]."'
        where 
        Consecutivo=".$_POST["Id"]."
        and Centrocosto=".$_SESSION["id_centro"];

        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_equimov?b=".$_POST["Id"]);
    }
  
    public function guarda_proveedor(){
        Conexion::conectar();
        echo $sql="INSERT INTO Proveedores(Fr,Ur,Identificacion,Nombre,Direccion,Fax,SitioWeb,Mail) 
        value(now(),".$_SESSION["cedula"].",'".$_POST["Identificacion"]."','".$_POST["Nombre"]."','".$_POST["Direccion"]."',
            '".$_POST["Fax"]."','".$_POST["SitioWeb"]."','".$_POST["Mail"]."')";
        //mysql_query($sql);
        //header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_entrada"]);   
    }
    public function get_proveedor(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT Identificacion,Nombre from Proveedores";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->proveedor[]=$row;
        }

        return $this->proveedor;
        $mysqli->close();
    }

    public function get_proyectos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT No,Nombre from Proyectos";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->proyecto[]=$row;
        }

        return $this->proyecto;
        $mysqli->close();
    }
    
    public function guardar_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="INSERT INTO Alm_movdet(No,Fr,Ur,Serial,Movimiento)";
        $sql.="values(".$_POST["Id_ent"].",now(),'".$_SESSION["cedula"]."','".$_POST["Serial"]."','".$_POST["Movimiento"]."')";
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_equimov?b=".$_POST["Id_ent"]);
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_movdet where No=$id";
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
        header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]); 

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_entdet where Id=".$id;
        mysql_query($sql);
    }

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_ent set Bloqueado=1 where Consecutivo=$id";
        mysql_query($sql);
    }

    public function get_tipo(){
        $mysqli=Conexion::c_mysqli();
        $sql="SELECT * 
        from G_controles as c
        inner join
        G_listas as l
        on
        c.No=l.Id_control
        where l.Id_control=3
        ";
        $res=$mysqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->tipo[]=$row;
        }
        return $this->tipo;
        $mysqli->close();
    }

     public function get_tipo2(){
        $mysqli=Conexion::c_mysqli();
        $sql="SELECT l.Id_lista,l.Lista
        from G_controles as c
        inner join
        G_listas as l
        on
        c.No=l.Id_control
        where l.Id_control=1
        ";
        $res=$mysqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->tipo2[]=$row;
        }
        return $this->tipo2;
        $mysqli->close();
    }

}

?>

