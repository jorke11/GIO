<?php
class Adm_contenido extends Conexion{
    private $control;
    private $productos;
    private $centros;

    public function __contruct(){
        $this->control=array();
        $this->productos=array();
        $this->centros=array();
    }
    public function get_controles(){
        Conexion::conectar();Conexion::conectar();

        $sql="SELECT * from G_controles";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->control[]=$row;
        }
        return $this->control;
    }

    public function agregar_centro(){
        Conexion::conectar();
        
        $insertar="INSERT INTO G_centros_costo(Id_sucursal,Fr,Ur,Nombre,Longitud,Latitud)
                    VALUES(1,now(),".$_SESSION["Cedula"].",'".$_GET["Centro"]."',
                        ".$_GET["long"].",".$_GET["lat"].")";        
        mysql_query($insertar);
        echo "<script>location.href='".Conexion::ruta()."Adm_contenido?Centro_b=1'</script>";
    }

    public function get_centros(){
        $this->conectar();
        $sql="SELECT *
                FROM G_centros_costo";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->centros[]=$row;
        }

        return $this->centros;
    }

     public function get_listas($id){
        $this->conectar();
        $sql="SELECT c.Descripcion,l.Lista 
                FROM G_listas l
                JOIN G_controles c ON c.No=l.Id_control
                WHERE Id_control=$id;";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->productos[]=$row;
        }

        return $this->productos;
    }

    public function agregar_control($id,$texto){
        $this->conectar();
        $sqlNuevo="SELECT MAX(Numeracion) + 1 Nuevo
                    FROM G_listas
                    WHERE Id_control=$id
        ";
        $resBusqueda=mysql_query($sqlNuevo);

        if($row=mysql_fetch_array($resBusqueda)){
            ($row["Nuevo"]==NULL)?$nuevo=1:$nuevo=$row["Nuevo"];
            $sql="INSERT INTO G_listas(Id_control,Lista,Numeracion) values($id,'$texto',$nuevo)";
            mysql_query($sql);
        }
        
    }

    public function agregar_tit($texto){
        $this->conectar();
        $sql="INSERT INTO G_controles(No,Descripcion) values(null,'$texto')";
        mysql_query($sql);   

        header("Location:".Conexion::ruta()."Adm_contenido");
    }
}
?>
