<?php
ob_start();
class LiquiObra extends Conexion{
    
    private $requi;
    private $detalle;
    private $tipo;
    private $liquidacion;
    private $Contratista;
    
    public function __construct(){
        $this->liquidacion=array();
        $this->Contratista=array();
    }

    public function get_Contratistas(){
        Conexion::conectar();
        $sql="SELECT Cednit,Nombre
                FROM Ope_Contratistas";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->Contratista[]='"'.$row["Cednit"].','.$row["Nombre"].'"';
        }    
        return $this->Contratista; 
    }

    public function nuevo_registro(){
        $nuevo;
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 nuevo  
                FROM Ope_liquiobra 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
        
            $query="INSERT INTO Ope_liquiobra(Consecutivo,Fr,Ur,Sucursal,Centrocosto)
                values(".$nuevo.",now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",".$_SESSION["Id_centro"].");";
            mysql_query($query);

            // echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra?b='".$nuevo."</script>";
               header("Location:".Conexion::ruta()."Ope_liquiobra?b=".$nuevo);
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
            FROM Ope_liquiobra
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
        $fecha=Conexion::convertirFecha($_GET["Fecha"]);

        $sql="UPDATE Ope_liquiobra SET Acuerdo=".$_GET["Acuerdo"].",Fecha='".$fecha."',
                    Acta='".$_GET["Acta"]."',Observaciones='".$_GET["observaciones"]."'
                    WHERE Consecutivo=$con
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra'</script>";
        //header("Location:".Conexion::ruta()."Ope_presobra");
        
    }

    public function copiar($id,$id_oc){
        Conexion::conectar();
        
        
        $sql="SELECT Id_liquiobra,Consecutivo,Idavo,Apu,Cantidad,Valor,Contratista,Observaciones
                    FROM Ope_liquiobradet 
                    WHERE Id=".$id;
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $con=$row["Consecutivo"]+ 1;
            $query="INSERT INTO Ope_liquiobradet(Id_liquiobra,Consecutivo,Fr,Ur,Idavo,Apu,
                Cantidad,Valor,Contratista,Observaciones,Centrocosto) 
                VALUES(".$row['Id_liquiobra'].",".$con.",now(),".$_SESSION["Cedula"].",
                ".$row["Idavo"].",".$row["Apu"].",".$row["Cantidad"].",
                ".$row["Valor"].",".$row["Contratista"].",'".$row["Observaciones"]."',".$_SESSION["Id_centro"].")";
            
            mysql_query($query);
            
            echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra?b=".$row["Id_liquiobra"]."'</script>";
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

        if(stripos($_POST["Contratista"],",")===false){
                $con=$_POST["Contratista"];
                
        }else{
                $d=explode(",",$_POST["Contratista"]);    
                $con=$d[0];
        }
        $sqlMax="SELECT MAX(Consecutivo)+ 1 nuevo  
                FROM Ope_liquiobradet";
        $resMax=mysql_query($sqlMax);

        $Consecutivo=mysql_fetch_array($resMax);
        $con=($Consecutivo["nuevo"]==NULL)?1:$Consecutivo["nuevo"];

        $sql="INSERT INTO Ope_liquiobradet(Id_liquiobra,Consecutivo,Fr,Ur,Idavo,Apu,Cantidad,Valor,Contratista,Observaciones,Centrocosto)
                VALUES(".$_POST["Consecutivo"].",".$con.",now(),".$_SESSION["Cedula"].",".$_POST["Idavo"].",
                    '".$apu."',".$_POST["Cantidad"].",".$_POST["Valor"].",
                    '".$con."','".$_POST["Observaciones"]."',
                    ".$_SESSION["Id_centro"].");
        ";
        
        
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra?b=".$_POST["Consecutivo"]."'</script>";  
    }
    
    public function get_detalle($id){
        Conexion::conectar();
        $sql="SELECT *
                    FROM Ope_liquiobradet
                    WHERE Id_liquiobra=".$id." 
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

        if(stripos($_POST["Contratista"], ",")===false){
            $Contratista=$_POST["Contratista"];

        }else{
            $d=explode(",",$_POST["Contratista"]);    
            $Contratista=$d[1];
        }        

        $sqlBusqueda="SELECT * 
                        FROM Ope_pre_apu
                        WHERE Apu=$Apu
        ";        
        $resBusqueda=mysql_query($sqlBusqueda);

        if(mysql_num_rows($resBusqueda) > 0){
            $sql="UPDATE Ope_liquiobradet set Idavo='".$_POST["Idavo"]."',Apu=".$_POST["Apu"].",
                        Cantidad=".$_POST["Cantidad"].",Valor=".$_POST["Valor"].",Contratista='".$Contratista."',
                        Observaciones='".$_POST["Observaciones"]."' 
                    WHERE Id=".$_POST["Id_liquiobra"];
            
            
            mysql_query($sql);
            echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra?b=".$_POST["Consecutivo"]."'</script>";  
            
        }else{
            echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra?b=".$_POST["Consecutivo"]."'</script>";  
        }

        

    }
    
    public function eliminar_detalle($id,$id_oc){
        Conexion::conectar();
        $sql="DELETE FROM Ope_liquiobradet where Id=".$id;
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_liquiobra?b=".$id_oc."'</script>";  
    }
    
}

?>

