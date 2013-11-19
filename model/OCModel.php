<?php
ob_start();
class Orden extends Conexion{
    
    private $requi;
    private $detalle;
    private $tipo;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->tipo=array();
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
    
    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo 
                FROM Alm_oc 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
        
            $query="INSERT INTO Alm_oc(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                values($nuevo,now(),'".$_SESSION["cedula"]."','".$estado."',now(),
                    ".$_SESSION["Id_sucursal"].",".$_SESSION["Id_centro"].");";
                mysql_query($query);
                
                header("Location:".Conexion::ruta()."Alm_OC?b=".$nuevo);
        }
    }
    
    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT Centros.Nombre AS Centro,OC.Consecutivo,OC.Id,OC.Fr,OC.Observaciones,OC.Festadodoc,
                                                (SELECT Lista 
                                                    FROM G_listas
                                                    WHERE Id_control=1
                                                    AND Numeracion=OC.Estadodoc) as Estadodoc,
        Sucursal.Nombre as Sucursal,OC.Estadodoc as Id_estado,OC.Norequi,OC.Tiempo_entrega,OC.Proveedor,
        OC.Tipo_pago,OC.Estado_proceso,OC.Transportado,OC.Lugar_entrega
            FROM Alm_oc  AS OC
            JOIN G_centros_costo AS Centros ON OC.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON OC.Sucursal=Sucursal.Id
            WHERE Consecutivo=$con AND Centrocosto={$_SESSION["Id_centro"]}
        ";

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function copiar($id,$id_oc){
        Conexion::conectar();

        $sql="SELECT Id_oc,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_ocdet 
                    WHERE Id=".$id." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){

            if($_GET["norequi"]==0){
                $query="INSERT INTO Alm_ocdet(Id_oc,Fr,Ur,Producto,Cantidad,Vu,Imp,
                Vn,Vt,Centrocosto) VALUES(".$row['Id_oc'].",now(),".$_SESSION["Cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["Id_centro"].")";
                
                mysql_query($query);
            
                header("Location:".Conexion::ruta()."Alm_OC?b=".$row["Id_oc"]); 
            }else{

             $sqlValida="SELECT SUM(Cantidad) cantidad
                        FROM Alm_requidet
                        WHERE Id_requisicion=".$_GET["norequi"]."
                        AND Producto='".$row["Producto"]."'
                        AND Centrocosto=".$_SESSION["Id_centro"];

                $resValida=mysql_query($sqlValida);
                $resCantidad=mysql_fetch_array($resValida);

            $sqlActual="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_ocdet
                            WHERE Id_oc=".$row["Id_oc"]."
                            AND Centrocosto=".$_SESSION["Id_centro"];
                $resActual=mysql_query($sqlActual);
                $total=mysql_fetch_array($resActual);


            
            if($resCantidad["cantidad"] > $total["Cantidad"]){
                $query="INSERT INTO Alm_ocdet(Id_oc,Fr,Ur,Producto,Cantidad,Vu,Imp,
                Vn,Vt,Centrocosto) VALUES(".$row['Id_oc'].",now(),".$_SESSION["Cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["Id_centro"].")";
                
                mysql_query($query);
            
                header("Location:".Conexion::ruta()."Alm_OC?b=".$row["Id_oc"]);    
            }else{
                header("Location:".Conexion::ruta()."Alm_OC?b=".$row["Id_oc"]."&error=2");    
            }

        }//fin norequi
            
        }
    }


    public function guardar_registro(){
        Conexion::conectar();

        if(stripos($_POST["Proveedor"], ",")===false){
            $proveedor=$_POST["Proveedor"];
            
        }else{
            $d=explode(",",$_POST["Proveedor"]);    
            $proveedor=$d[1];
        }

        if(isset($_POST["norequi"]) and count($_POST["norequi"]) > 0){
            $norequi="Norequi=".$_POST["norequi"].",";
            $requi=$_POST["norequi"];
        }else{
            $norequi="";
        }

        $sql="UPDATE Alm_oc SET Observaciones='".$_POST["Observaciones"]."',Tiempo_entrega='".$_POST["Tentrega"]."',
            $norequi Lugar_entrega='".$_POST["Lentrega"]."',Tipo_pago=".$_POST["Tpago"].",
            Proveedor='".$proveedor."',Transportado='".$_POST["Transportado"]."',
            Estado_proceso='".$_POST["Estadoproceso"]."',Estadodoc=4 
            WHERE Consecutivo=".$_POST["Id"]." 
            AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id"]);
    }
  
    
    public function guardar_detalle(){
        Conexion::conectar();

        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        if(stripos($_POST["producto"], ",")===false){
            $producto=$_POST["producto"];
            
        }else{
            $d=explode(",",$_POST["producto"]);    
            $producto=$d[0];
        }

        if($_POST["Norequi"]==0){

             $sql="INSERT INTO Alm_ocdet(Id_oc,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["Cedula"]."','".$producto."',
                        ".$_POST["cantidad"].",".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",
                        ".$total.",".$_SESSION["Id_centro"].")";
                
                mysql_query($sql);

                header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]);    

        }else{

        $sqlRequi="SELECT Consecutivo
                    FROM Alm_requi
                    WHERE Consecutivo=".$_POST["Norequi"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $resRequi=mysql_query($sqlRequi);

        if(mysql_num_rows($resRequi) > 0){

        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto}'
        ";
        $resBusqueda=mysql_query($sqlBusqueda);

        if(mysql_num_rows($resBusqueda)){
            if(isset($_POST["Norequi"]) and $_POST["Norequi"] > 0){
                $sqlValida="SELECT SUM(Cantidad) cantidad
                        FROM
                        Alm_requidet
                        WHERE Id_requisicion=".$_POST["Norequi"]."
                        AND Producto='".$producto."'
                        AND Centrocosto=".$_SESSION["Id_centro"];

                $resValida=mysql_query($sqlValida);
                $resCantidad=mysql_fetch_array($resValida);

                $sqlActual="SELECT SUM(Cantidad) Cantidad
                                FROM Alm_ocdet
                                WHERE Id_oc=".$_POST["Id_requisicion"]."
                                AND Producto='".$producto."'
                                AND Centrocosto=".$_SESSION["Id_centro"];
                            
                    $resActual=mysql_query($sqlActual);
                    $totalActual=mysql_fetch_array($resActual);

                    
            if($resCantidad["cantidad"] >= $totalActual["Cantidad"]){
                    $total=$_POST["cantidad"] + $totalActual["Cantidad"];
                    if($resCantidad["cantidad"] >= $total){
                        $sql="INSERT INTO Alm_ocdet(Id_oc,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["Cedula"]."','".$producto."',
                        ".$_POST["cantidad"].",".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",
                        ".$total.",".$_SESSION["Id_centro"].")";
                
                        mysql_query($sql);

                        header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]);    
                    }else{
                        header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]."&error=2");        
                    }
                }else{
                    header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]."&error=2");    
                }
            }else{
                $sql="INSERT INTO Alm_ocdet(Id_oc,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                    $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["Cedula"]."','".$producto."',
                    ".$_POST["cantidad"].",".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",
                    ".$total.",".$_SESSION["Id_centro"].")";
                
                    mysql_query($sql);

                    header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]);    
            }
        }else{
            header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]."&error=1");
        }

    }else{
        header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]."&error=3");
    }

    }//no norequi
        
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT OC.Id,OC.Id_oc,Descripcion,Cantidad,Vu,Imp,Vn,Vt,Producto
                    FROM Alm_ocdet AS OC
                    JOIN G_producto AS Pro ON OC.Producto=Pro.Codigo
                    WHERE Id_oc=$id 
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

            if($_POST["Norequi"]==0){
                $sql="UPDATE Alm_ocdet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",
                        Vu=".$_POST["vunitario"].",Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." 
                    WHERE Id=".$_POST["fid"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
                    mysql_query($sql);
                    header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]);  

            }else{

            $sqlValida="SELECT SUM(Cantidad) cantidad
                        FROM Alm_requidet
                        WHERE Id_requisicion=".$_POST["Norequi"]."
                        AND Producto='".$producto."'
                        AND Centrocosto=".$_SESSION["Id_centro"];

                $resValida=mysql_query($sqlValida);
                $resCantidad=mysql_fetch_array($resValida);

             if($resCantidad["cantidad"] > $_POST["cantidad"]){   

                $sql="UPDATE Alm_ocdet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",
                        Vu=".$_POST["vunitario"].",Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." 
                    WHERE Id=".$_POST["fid"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
                    mysql_query($sql);
                    header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]);   
             }else{
                header("Location:".Conexion::ruta()."Alm_OC?b=".$_POST["Id_requisicion"]."&error=2");       
             }
            }//fin norequi
            
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

