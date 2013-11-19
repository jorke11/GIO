<?php
ob_start();
class Liquidacion extends Conexion{
    
    private $requi;
    private $detalle;
    private $tipo;
    private $liquidacion;
    private $liqui;
    private $contratistas;
    
    public function __construct(){
        $this->liquidacion=array();
        $this->liqui=array();
        $this->contratistas=array();
    }
    
     public function get_proveedor(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT Identificacion,Nombre from G_proveedores";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
            $this->proveedor[]='"'.$row["Identificacion"].','.$row["Nombre"].'"';
        }

        return $this->proveedor;
        $mysqli->close();
    }

    public function get_contratistas(){
        Conexion::conectar();
        $sql="SELECT * FROM 
                Ope_Contratistas";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->contratistas[]=$row;
        }

        return $this->contratistas;
    }

    public function get_liquidaciones(){
        Conexion::conectar();
        $sql="SELECT * 
                FROM Ope_liquiobradet";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->liqui[]=$row;
        }
        return $this->liqui;
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
                
                header("Location:".Conexion::ruta()."Ope_liquidacion?b=".$nuevo);
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

    public function get_liquidacion(){
        Conexion::conectar();

        $sql="SELECT *
            FROM Ope_liquiobra  AS liqui
            JOIN G_centros_costo AS Centros ON liqui.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON liqui.Sucursal=Sucursal.Id
            WHERE liqui.Centrocosto={$_SESSION["Id_centro"]}
        ";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->liquidacion[]=$row;
        }    
        return $this->liquidacion;       
    }
    
    public function guardar_fila($con){
        Conexion::conectar();
        echo $sql="UPDATE Ope_liquiobra SET Acuerdo=".$_GET["acuerdo"].",Fecha='".$_GET["fecha"]."',
                    Acta='".$_GET["acta"]."',Observaciones='".$_GET["observaciones"]."'
                    WHERE Consecutivo=$con
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        header("Location:".Conexion::ruta()."Ope_liquidacion");
        
    }

    public function copiar($id,$id_oc){
        Conexion::conectar();
        
        
        $sql="SELECT Id_oc,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_ocdet 
                    WHERE Id=".$id." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $query="INSERT INTO Alm_ocdet(Id_oc,Fr,Ur,Producto,Cantidad,Vu,Imp,
                Vn,Vt,Centrocosto) VALUES(".$row['Id_oc'].",now(),".$_SESSION["Cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["Id_centro"].")";
            
            mysql_query($query);
            
            header("Location:".Conexion::ruta()."Alm_OC?b=".$row["Id_oc"]);
        }
    }


    public function guardar_registro(){
        Conexion::conectar();
        print_r($_POST);exit;
        $sql="UPDATE Alm_oc SET Observaciones='".$_POST["Observaciones"]."',Tiempo_entrega='".$_POST["Tentrega"]."',
                $norequi Lugar_entrega='".$_POST["Lentrega"]."',Tipo_pago=".$_POST["Tpago"].",
                Proveedor='".$proveedor."',Transportado={$Transportado},
                Estado_proceso='".$_POST["Estadoproceso"]."',Estadodoc=4 
                WHERE Consecutivo=".$_POST["Id"]." 
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Ope_liquiobra?b=".$_POST["Id"]);
    }
  
    
    public function guardar_detalle(){
        Conexion::conectar();
        
        $sql="INSERT INTO Ope_liquiobradet(Consecutivo,Fr,Ur,Idavo,Apu,Cantidad,Valor,Observaciones,Centrocosto)
                VALUES(".$_POST["Consecutivo"].",now(),".$_SESSION["Cedula"].",".$_POST["Idavo"].",
                    '".$_POST["Apu"]."',".$_POST["Cantidad"].",".$_POST["Valor"].",'".$_POST["Observaciones"]."',
                    ".$_SESSION["Id_centro"].");
        ";

        $res=mysql_query($sql);

        header("Location:".Conexion::ruta()."Ope_liquidacion?b=".$_POST["Consecutivo"]);
        
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT *
                    FROM Ope_liquiobradet 
                    WHERE Consecutivo=$id 
                    AND Centrocosto=".$_SESSION["Id_centro"];
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
        
        $producto=explode(",", $_POST["producto"]);

        if(stripos($_POST["producto"], ",")===false){
            $producto=$_POST["producto"];
            $sqlProducto="WHERE Codigo='{$producto}'";   
        }else{
            $d=explode(",",$_POST["producto"]);    
            $producto=$d[1];
            $sqlProducit="WHERE Codigo='{$producto}'";
        }


        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        $sqlProducto
        ";        
        $resBusqueda=mysql_query($sqlBusqueda);

        if(mysql_num_rows($resBusqueda) > 0){
            $sql="UPDATE Alm_ocdet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
            Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"]."
            AND Centrocosto=".$_SESSION["Id_centro"];
            
            mysql_query($sql);

            header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]);   
        }else{
            header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]."&error=1");   
        }

        

    }
    
    public function eliminar_detalle($id,$id_oc){
        Conexion::conectar();
        echo $sql="DELETE FROM Alm_ocdet where Id=".$id." and Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_OC?b=".$id_oc);   
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

