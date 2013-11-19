<?php
class Usuarios extends Conexion{
    private $centro;
    private $centrocosto;
    private $sucursal;
    public function __construct(){
        $this->centro=array();
        $this->centrocosto=array();
        $this->sucursal=array();
    }

    public function validar_login(){
        $mysqli=Conexion::c_mysqli();
        if(!empty($_POST["Usuario"]) and !empty($_POST["Clave"]) and !empty($_POST["Centro"])){
        $sql="SELECT * 
                FROM Usuarios 
                WHERE Cedula='".$_POST["Usuario"]."' 
                AND Clave='".$_POST["Clave"]."'";

        $res=$mysqli->query($sql);
        

        if($res->num_rows){
            if($row=$res->fetch_array()){
                $_SESSION["No"]=$row["No"];
                $_SESSION["Cedula"]=$row["Cedula"];
                $_SESSION["Nombre"]=$row["Nombre"];
                $_SESSION["Id_centro"]=$_POST["Centro"];
                $_SESSION["Trama_prin"]=$row["Trama_prin"];
                $_SESSION["Trama_sub"]=$row["Trama_sub"];
                $_SESSION["Trama_centros"]=$row["Centrocosto"];
                $_SESSION["Roles"]=$row["Rol"];
                $_SESSION["Centros"]=$row["Centrocosto"];
                $_SESSION["Id_sucursal"]=1;

                $query="UPDATE Usuarios set ultIngreso=now() where Cedula='".$row["Cedula"]."'";
                $mysqli->query($query);
                header("Location:".Conexion::ruta()."home");    
            }
        }else{
            header("Location:".Conexion::ruta()."login?m=1");
        }
        }

    }
    
    public function get_Centrocosto(){
        Conexion::ruta();
        $sql="SELECT Id,Nombre
                FROM G_centros_costo 
        ";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->centrocosto[]=$row;
        }
        return $this->centrocosto;

    }
     public function get_Sucursales(){
        Conexion::ruta();
        $sql="SELECT Id,Nombre
                FROM G_sucursales 
        ";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->sucursal[]=$row;
        }
        return $this->sucursal;
    }

    public function get_centro($datos){
        Conexion::conectar();
        $sql="SELECT Centrocosto
                    FROM Usuarios User            
                    WHERE User.Cedula='".$datos['Cedula']."';";
            $res=mysql_query($sql);
            $dato=mysql_fetch_array($res);

        $centro=Conexion::LetraCentros($datos["Id_centro"]);
        return $centro;
    }

    
    public function registrar_usuario(){
        Conexion::conectar();
        $query="SELECT * 
                FROM Usuarios 
                WHERE Cedula='".$_POST["cedula"]."' 
                AND Correo='".$_POST["correo"]."' 
                AND usuario='".$_POST["usuario"]."'";

        $res=mysql_query($query);
        if(mysql_num_rows($res)==0){
            if($_POST["clave"]==$_POST["clave_c"]){
            
            //$md5=md5($_POST["clave"]);
            $sql="INSERT INTO Usuarios(No,Cedula,Nombre,Apellido,Correo,usuario,Clave) values(null,'".$_POST["cedula"]."','".$_POST["nombres"]."','".$_POST["apellidos"]."','".$_POST["correo"]."','".$_POST["usuario"]."','".$_POST["clave"]."')";
            mysql_query($sql);
            header("Location:".Conexion::ruta()."registrar&m=1");    
        }else{
            header("Location:".Conexion::ruta()."registrar&m=2");    
        }
        
        }
    }
    
    public function cerrar_sesion(){
        session_destroy();
        header("Location:".Conexion::ruta()."login");
    }
}

?>
