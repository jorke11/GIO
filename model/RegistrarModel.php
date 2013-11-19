<?php
class Registro extends Conexion{
    
    private $requi;
    private $detalle;
    private $centros;
    private $ucentros;
    private $centros_p;
    private $rol;
    private $con;

    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->centros=array();
        $this->ucentros=array();
        $this->rol=array();
        $this->con=array();
    }
    
    public function getRoles(){
        Conexion::conectar();
        $sql="SELECT * from G_roles";
        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $this->rol[]=$row;
        }

        return $this->rol;
    }    

    public function get_centro(){
        Conexion::conectar();
        $sql="SELECT * from G_centros_costo";
        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $this->centros_p[]=$row;
        }

        return $this->centros_p;
    }

    public function centros_usuarios($cedula){
        Conexion::conectar();
        $sql="SELECT Id_centro from Usuarios_centro where Id_usuario='".$cedula."'";
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->ucentros[]=$row;
        }

        return $this->ucentros;
    }

    public function nuevo_registro(){
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo  from Usuarios";
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
                
            $query="INSERT INTO Usuarios(Consecutivo)
            values($nuevo);";
            mysql_query($query);

            echo "<script>location.href='".Conexion::ruta()."Registrar?Con=".$nuevo."'</script>";
        }
    }
    
    public function Buscar($con){
        Conexion::conectar();
        $sql="SELECT *
                FROM Usuarios 
                WHERE Cedula='$con'";
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;  
    }

    public function BuscarCon($con){
        Conexion::conectar();
        $sql="SELECT *
                FROM Usuarios 
                WHERE Consecutivo='$con'";
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->con[]=$row;
        }    
        return $this->con;  
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        $centro=array();
        $centros="";
        $rol="";
        $sqlBorrar="UPDATE Usuarios SET Centrocosto='/ ',Rol='/ '
                    WHERE Cedula='".$_POST["cedula"]."'";
        mysql_query($sqlBorrar);

        $centro=$_POST["Centrocosto"];

        for ($i=0; $i <count($centro) ; $i++) { 
            $centros .= Conexion::LetraCentros($centro[$i])." /";
        }

        $roles=$_POST["Roles"];

        for ($i=0; $i <count($roles) ; $i++) { 
            $rol .= Conexion::LetraRoles($roles[$i])." /";
        }

        $sqlUser="SELECT Centrocosto,Rol
                    FROM Usuarios
                    WHERE Cedula='".$_POST["cedula"]."'";
        $resUser=mysql_query($sqlUser);
        $dato=mysql_fetch_array($resUser);

        if(!empty($_POST["clave"])){
            $clave="Clave='".$_POST["clave"]."',";
        }else{
            $clave="";
        }

        
        $sql="UPDATE Usuarios set Cedula='".$_POST["cedula"]."',Nombre='".$_POST["nombre"]."',Apellido='".$_POST["apellido"]."',
                Correo='".$_POST["correo"]."',$clave 
                Centrocosto=CONCAT('".$dato["Centrocosto"]."','".$centros."'),
                Rol=CONCAT('".$dato["Rol"]."','".$rol."')
                WHERE Consecutivo=".$_POST["Id"];
        
        mysql_query($sql);

        echo "<script>location.href='".Conexion::ruta()."Registrar?b=".$_POST["cedula"]."'</script>";
    }

    
    
    
    public function guardar_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;
        $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt)";
        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["usuario"]."','".$_POST["producto"]."',".$_POST["cantidad"].",
        ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.")";
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Usuarios where No=$id";
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

    
    
    
}

?>

