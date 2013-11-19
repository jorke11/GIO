<?php
ob_start();
class PresObra extends Conexion{
    
    private $requi;
    private $detalle;
    private $tipo;
    private $liquidacion;
    private $apu;
    
    public function __construct(){
        $this->liquidacion=array();
        $this->apu=array();
    }

    public function getApu(){
        Conexion::conectar();
        $sql="SELECT Apu,Acuerdo,Descripcion 
                FROM Ope_pre_apu";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->apu[]='"'.$row["Apu"].','.$row["Acuerdo"].','.$row["Descripcion"].'"';
        }    
        return $this->apu;    
    }


    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 nuevo  
                FROM Ope_presobra 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
        
            $query="INSERT INTO Ope_presobra(Consecutivo,Fr,Ur,Sucursal,Centrocosto)
                values(".$nuevo.",now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",".$_SESSION["Id_centro"].");";
            mysql_query($query);

            echo "<script>location.href='".Conexion::ruta()."Ope_presobra'</script>";
               // header("Location:".Conexion::ruta()."Ope_presobra?b=".$nuevo);
        }
    }
    
    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT *
            FROM Ope_liquiobra  AS liqui
            JOIN G_centros_costo AS Centros ON liqui.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON liqui.Sucursal=Sucursal.Id
            WHERE liqui.Consecutivo=$con AND liqui.Centrocosto={$_SESSION["Id_centro"]}
        ";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_presobra(){
        Conexion::conectar();

        $sql="SELECT *
            FROM Ope_presobra
            WHERE Centrocosto={$_SESSION["Id_centro"]}
        ";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->liquidacion[]=$row;
        }    
        return $this->liquidacion;       
    }
    
    public function guardar_fila($con){
        Conexion::conectar();

        $fechaini=Conexion::ConvertirFecha($_GET["fechaini"]);
        $fechafin=Conexion::ConvertirFecha($_GET["fechafin"]);
        
        $sql="UPDATE Ope_presobra SET Acuerdo=".$_GET["Acuerdo"].",Fechaini='".$fechaini."',
                    Fechafin='".$fechafin."',Observaciones='".$_GET["observaciones"]."'
                    WHERE Consecutivo=$con
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        $res=mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_presobra'</script>";
        //header("Location:".Conexion::ruta()."Ope_presobra");
        
    }

    public function copiar($id){
        Conexion::conectar();
                
        $sql="SELECT Id_presobra,Idavo,Apu,Cantidad,Valor,Observaciones,Consecutivo 
                    FROM Ope_presobradet 
                    WHERE Id=".$id;
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $con=$row["Consecutivo"]+ 1;
            $query="INSERT INTO Ope_presobradet(Id_presobra,Centrocosto,Consecutivo,Fr,Ur,Idavo,Apu,
                Cantidad,Valor,Observaciones) 
                VALUES(".$row['Id_presobra'].",".$_SESSION["Id_centro"].",".$con.",now(),
                ".$_SESSION["Cedula"].",".$row["Idavo"].",".$row["Apu"].",".$row["Cantidad"].",
                ".$row["Valor"].",'".$row["Observaciones"]."')";
            
            mysql_query($query);
            echo "<script>location.href='".Conexion::ruta()."Ope_presobra?b=".$row["Id_presobra"]."'</script>";
        }


    }
  
    
    public function guardar_detalle(){
        Conexion::conectar();
        
        if(stripos($_POST["Apu"],",")===false){
                $apu=$_POST["Apu"];
                
        }else{
                $d=explode(",",$_POST["Apu"]);    
                $apu=$d[0];
        }

         $sqlMax="SELECT MAX(Consecutivo)+ 1 nuevo  
                FROM Ope_presobradet 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $resMax=mysql_query($sqlMax);

        $Consecutivo=mysql_fetch_array($resMax);
        $con=($Consecutivo["nuevo"]==NULL)?1:$Consecutivo["nuevo"];

        $sql="INSERT INTO Ope_presobradet(Consecutivo,Id_presobra,Fr,Ur,Idavo,Apu,Cantidad,Valor,Observaciones,Centrocosto)
                VALUES(".$con.",".$_POST["Consecutivo"].",now(),".$_SESSION["Cedula"].",".$_POST["Idavo"].",
                    '".$apu."',".$_POST["Cantidad"].",".$_POST["Valor"].",'".$_POST["Observaciones"]."',
                    ".$_SESSION["Id_centro"].");
        ";
        
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_presobra?b=".$_POST["Consecutivo"]."'</script>";  
    }
    
    public function get_detalle($id){
        Conexion::conectar();
        $sql="SELECT *
                    FROM Ope_presobradet 
                    WHERE Id_presobra=$id 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }

    public function edit_detalle(){
        Conexion::conectar();

        

        if(stripos($_POST["Apu"], ",")===false){
            $Apu=$_POST["Apu"];

        }else{
            $d=explode(",",$_POST["Apu"]);    
            $Apu=$d[1];
            
        }
        $sqlBusqueda="SELECT * 
                        FROM Ope_pre_apu
                        WHERE Apu=$Apu
        ";        
        $resBusqueda=mysql_query($sqlBusqueda);

        if(mysql_num_rows($resBusqueda) > 0){
            $sql="UPDATE Ope_presobradet set Idavo='".$_POST["Idavo"]."',Apu=".$_POST["Apu"].",
                        Cantidad=".$_POST["Cantidad"].",Valor=".$_POST["Valor"].",Observaciones='".$_POST["Observaciones"]."' 
                    WHERE Id=".$_POST["Id_presobra"];
            
            
            mysql_query($sql);
            echo "<script>location.href='".Conexion::ruta()."Ope_presobra?b=".$_POST["Consecutivo"]."'</script>";  
            
        }else{
            echo "<script>location.href='".Conexion::ruta()."Ope_presobra?b=".$_POST["Consecutivo"]."'</script>";  
        }

        

    }
    
    public function eliminar_detalle($id,$id_oc){
        Conexion::conectar();
        $sql="DELETE FROM Ope_presobradet where Id=".$id;
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_presobra?b=".$id_oc."'</script>";  
    }

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_oc set Estadodoc=2 where Consecutivo=$id";
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
        where Id_control=2
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tipo[]=$row;
        }
        return $this->tipo;
    }

    public function get_tipo2(){
        Conexion::conectar();
        $sql="SELECT * 
        from G_controles as c
        inner join
        G_listas as l
        on
        c.No=l.Id_control
        where Id_control=3
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tipo2[]=$row;
        }
        return $this->tipo2;
    }
    
}

?>

