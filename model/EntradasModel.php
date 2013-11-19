<?php
ob_start();
class Entradas extends Conexion{
    
    private $requi;
    private $detalle;
    private $tipo;
    private $tipo2;
    private $proveedor;
    private $proyecto;
    private $producto;
    private $personal;
    private $estadodoc;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->tipo=array();
        $this->tipo2=array();
        $this->proveedor=array();
        $this->proyecto=array();
        $this->producto=array();
        $this->personal=array();
        $this->estadodoc=array();
    }

    public function get_EstadoDoc(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No 
                FROM G_listas l
                JOIN G_controles c ON c.No=l.Id_control
                WHERE Id_control=4";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->estadodoc[]=$row;
        }

        return $this->estadodoc;
    }



    public function get_productos(){
        Conexion::conectar();
        $sql="SELECT Codigo,Descripcion from G_producto";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->producto[]='"'.$row["Codigo"].','.$row["Descripcion"].'"';
        }    
        return $this->producto;    
    }
    
    public function nuevo_registro(){

        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo  
                FROM Alm_ent 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
                
            $query="INSERT INTO Alm_ent(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
            values($nuevo,now(),'".$_SESSION["Cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",".$_SESSION["Id_centro"].");";
            mysql_query($query);
            echo "<script>location.href='".Conexion::ruta()."Entradas?b=".$nuevo."'</script>";
            
        }
    }
    
    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT Ent.Consecutivo,Ent.Festadodoc,Ent.Proyecto,Ent.Tipo,Centros.Nombre as Centro,
        Sucursal.Nombre Sucursal,Ent.Id_oc,Ent.Transportado,Ent.Proveedor,Ent.Observaciones,Ent.Estadodoc,
        (SELECT Lista FROM G_listas WHERE Numeracion=Ent.Estadodoc AND Id_control=1) Lista
        FROM Alm_ent as Ent
        JOIN G_centros_costo AS Centros ON Ent.Centrocosto=Centros.Id
        JOIN G_proveedores AS Pro ON Ent.Proveedor=Pro.Identificacion
        JOIN G_sucursales AS Sucursal ON Ent.Sucursal=Sucursal.Id
        WHERE Ent.Consecutivo=$con 
        AND Ent.Centrocosto=".$_SESSION["Id_centro"];

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        $p1=0;
        $p2=0;
        echo "<pre>";
        
        if(empty($_POST["orden"])){
            $orden=0;
        }else{
            $orden=$_POST["orden"];
        }

        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor1=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor1=$d[0];
        }

        if(stripos($_POST["Proyecto"],",")===false){
                $proyecto1=$_POST["Proyecto"];
                
        }else{
                $d=explode(",",$_POST["Proyecto"]);    
                $proyecto1=$d[0];
        }
        $sqlProveedor="SELECT * 
                            FROM G_proveedores
                            WHERE Identificacion='$proveedor1'";
        $sqlProyecto="SELECT * 
                        FROM G_proyectos
                        WHERE No='$proyecto1'
        "; 
        $resProveedor=mysql_query($sqlProveedor);
        $resProyecto=mysql_query($sqlProyecto);

        if(mysql_num_rows($resProveedor) > 0){
            $proveedor=$proveedor1;
        }else{
            $proveedor="";

        }

        if(mysql_num_rows($resProyecto) > 0){
            $proyecto=$proyecto1;
        }else{
            $proyecto="";
        }

        $sql="UPDATE Alm_ent SET Proyecto='".$proyecto."',Tipo=".$_POST["Tipo"].",
                    Observaciones='".$_POST["Observaciones"]."',Transportado='".$_POST["Transportado"]."',
                    Proveedor='".$proveedor."',Id_oc=".$orden.",Estadodoc=4
                WHERE Consecutivo=".$_POST["Id"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id"]);
    }

    public function copiar($id){
        Conexion::conectar();
        
        $sql="SELECT Id_ent,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_entdet 
                    WHERE Id=".$id." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $sqlVerifica="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_ocdet
                            WHERE Id_oc=".$_GET["orden"]."
                            AND Producto='".$row["Producto"]."'
                            AND Centrocosto=".$_SESSION["Id_centro"];

            $resVerifica=mysql_query($sqlVerifica);
            $cantidadActual=mysql_fetch_array($resVerifica);

            $sqlActual="SELECT SUM(Cantidad) Cantidad
                        FROM Alm_entdet
                        WHERE Id_ent=".$row["Id_ent"]."
                        AND Producto='".$row["Producto"]."'
                        AND Centrocosto=".$_SESSION["Id_centro"];
            $resActual=mysql_query($sqlActual);
            $totalAct=mysql_fetch_array($resActual);

            if($_GET["order"]==0){
                $query="INSERT INTO Alm_entdet(Id_ent,Fr,Ur,Producto,Cantidad,Vu,Imp,
                    Vn,Vt,Centrocosto) VALUES(".$row['Id_ent'].",now(),".$_SESSION["Cedula"].",
                    '".$row["Producto"]."',".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",
                    ".$row["Vn"].",
                    ".$row["Vt"].",".$_SESSION["Id_centro"].")";
            
                    mysql_query($query);
                    header("Location:".Conexion::ruta()."Entradas?b=".$row["Id_ent"]);
                }else{
            if($cantidadActual["Cantidad"] >= $totalAct["Cantidad"]){
                $suma=$totalAct["Cantidad"] + $row["Cantidad"];
                    if($cantidadActual["Cantidad"]>=$suma){
                        $query="INSERT INTO Alm_entdet(Id_ent,Fr,Ur,Producto,Cantidad,Vu,Imp,
                        Vn,Vt,Centrocosto) VALUES(".$row['Id_ent'].",now(),".$_SESSION["Cedula"].",
                        '".$row["Producto"]."',".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",
                        ".$row["Vn"].",
                        ".$row["Vt"].",".$_SESSION["Id_centro"].")";
                
                        mysql_query($query);
                        header("Location:".Conexion::ruta()."Entradas?b=".$row["Id_ent"]);
                    }else{
                        header("Location:".Conexion::ruta()."Entradas?b=".$row["Id_ent"]."&error=3");    
                    }
                }else{
                    header("Location:".Conexion::ruta()."Entradas?b=".$row["Id_ent"]."&error=3");
                }
            }
        }
    }
  
    public function guarda_proveedor(){
        Conexion::conectar();
        $sql="INSERT INTO G_proveedores(Fr,Ur,Identificacion,Nombre,Direccion,Fax,SitioWeb,Mail) 
        value(now(),".$_SESSION["cedula"].",'".$_POST["Identificacion"]."','".$_POST["Nombre"]."','".$_POST["Direccion"]."',
            '".$_POST["Fax"]."','".$_POST["SitioWeb"]."','".$_POST["Mail"]."')";

        mysql_query($sql);
        header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_entrada"]);   
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

    public function get_proyectos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT No,Nombre from G_proyectos";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
            $this->proyecto[]='"'.$row["No"].','.$row["Nombre"].'"';
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


        if(stripos($_POST["producto"], ",")===false){
                $producto=$_POST["producto"];
        }else{
            $d=explode(",", $_POST["producto"]);
            $producto=$d[0];
        }

        $sqlBusqueda="SELECT * 
                        FROM 
                        G_producto
                        WHERE Codigo='$producto'
        ";
        $resBusqueda=mysql_query($sqlBusqueda);
        if(mysql_num_rows($resBusqueda) > 0){
            
            $salValida="SELECT SUM(Cantidad) Cantidad
                        FROM Alm_ocdet
                        WHERE Id_oc=".$_POST["Orden"]."
                        AND Producto='".$producto."'
                        AND Centrocosto=".$_SESSION["Id_centro"];
            $resValida=mysql_query($salValida);
            $totalValida=mysql_fetch_array($resValida);

            $sqlValida="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_entdet
                            WHERE Id_ent=".$_POST["Id_ent"]."
                            AND Producto='".$producto."'
                            AND Centrocosto=".$_SESSION["Id_centro"];
                            
            $resValida=mysql_query($sqlValida);
            $totalres=mysql_fetch_array($resValida);

            if($_POST["Orden"]==0){
                $sql="INSERT INTO Alm_entdet(Id_ent,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                    $sql.="values(".$_POST["Id_ent"].",now(),'".$_SESSION["Cedula"]."','".$producto."',".$_POST["cantidad"].",
                    ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.",".$_SESSION["Id_centro"].")";
                mysql_query($sql);
                header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]);
            }else{

            if($totalValida["Cantidad"] >= $totalres["Cantidad"]){
                $suma=$totalres["Cantidad"] + $_POST["cantidad"];
                    if($totalValida["Cantidad"] >= $suma){

                    $sql="INSERT INTO Alm_entdet(Id_ent,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                    $sql.="values(".$_POST["Id_ent"].",now(),'".$_SESSION["Cedula"]."','".$producto."',".$_POST["cantidad"].",
                    ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.",".$_SESSION["Id_centro"].")";
                    
                    mysql_query($sql);
                    
                    header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]);
                    }else{
                    header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]."&error=4");       
                    }

                }else{
                     header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]."&error=4");
                }

            }
        }else{
            header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]."&error=2");
        }

    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_entdet where Id_ent=$id and Centrocosto=".$_SESSION["Id_centro"];
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

        if(stripos($_POST["producto"], ",")===false){
                $producto=$_POST["producto"];
        }else{
            $d=explode(",", $_POST["producto"]);
            $producto=$d[0];
        }


        $sqlBusqueda="SELECT * 
                        FROM 
                        G_producto
                        WHERE Codigo='$producto'
        ";
        $resBusqueda=mysql_query($sqlBusqueda);
        if(mysql_num_rows($resBusqueda) > 0){

            $salValida="SELECT SUM(Cantidad) Cantidad
                        FROM Alm_ocdet
                        WHERE Id_oc=".$_POST["Orden"]."
                        AND Producto='".$producto."'
                        AND Centrocosto=".$_SESSION["Id_centro"];
            $resValida=mysql_query($salValida);
            $totalValida=mysql_fetch_array($resValida);

            $sqlValida="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_entdet
                            WHERE Id_ent=".$_POST["Id_ent"]."
                            AND Producto='".$producto."'
                            AND Centrocosto=".$_SESSION["Id_centro"];
                            
            $resValida=mysql_query($sqlValida);
            $totalres=mysql_fetch_array($resValida);    
            if($_POST["Orden"]==0){
                $sql="UPDATE Alm_entdet set Producto='".$producto."',Cantidad=".$_POST["cantidad"].",
                        Vu=".$_POST["vunitario"].",Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." 
                    WHERE Id=".$_POST["fid"];
                mysql_query($sql);
                header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]);                         
            }else{
            if($totalValida["Cantidad"] >= $totalres["Cantidad"]){
                $suma=$totalres["Cantidad"] + $_POST["cantidad"];
                if($totalValida["Cantidad"] >= $suma){
                    $sql="UPDATE Alm_entdet set Producto='".$producto."',Cantidad=".$_POST["cantidad"].",
                        Vu=".$_POST["vunitario"].",Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." 
                    WHERE Id=".$_POST["fid"];
            
                    mysql_query($sql);
                    header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]);             
                    }else{
                        header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]."&error=3");
                    }    
                }else{
                    header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]."&error=3");    
                }
            }
        }else{
            header("Location:".Conexion::ruta()."Entradas?b=".$_POST["Id_ent"]."&error=2");     
        }


        

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_entdet where Id=".$id." and Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($sql);

    }

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_ent set Estadodoc=2 where Consecutivo=$id";
        mysql_query($sql);
    }

    public function no_tiene_order($id){
        Conexion::conectar();
        $sqlOrden="SELECT SUM(Cantidad) Total
                    FROM Alm_ocdet
                    WHERE Id_oc=$id

        ";
        $resBusqueda2=mysql_query($sqlOrden);

        if(mysql_num_rows($resBusqueda2) > 0){
            if($row2=mysql_fetch_array($resBusqueda2)){
                $total_oc=$row2["Total"];
            }
        }


        $sqlBuscar="SELECT SUM(Cantidad) Total
                        FROM Alm_entdet
                        WHERE Id_ent=$id
                        AND Centrocosto='{$_SESSION["Id_centro"]}'
        ";
        $resBusqueda=mysql_query($sqlBuscar);

        if(mysql_num_rows($resBusqueda)){
            if($row=mysql_fetch_array($resBusqueda)){
                $total_ent=$row["Total"];
            }
        }

        if($total_ent==$total_oc and $total_ent!='' and $total_oc!=''){
            $sql="UPDATE Alm_ent set Estadodoc=2 where Consecutivo=$id";
            msql_query($sql);
            header("Location:".Conexion::ruta()."Entradas?b=".$id); 
        }else{
            header("Location:".Conexion::ruta()."Entradas?b=".$id."&error=4"); 
        }
    }


    public function get_tipo(){
        $mysqli=Conexion::c_mysqli();
        $sql="SELECT * 
        FROM G_controles AS c
        JOIN G_listas AS l ON c.No=l.Id_control
        WHERE l.Id_control=3
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
        FROM G_controles as c
        JOIN G_listas as l ON c.No=l.Id_control
        WHERE l.Id_control=1
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


