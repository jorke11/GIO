<?php
class Salidas extends Conexion {
    
    private $requi;
    private $detalle;
    private $tipo;
    private $tipo2;
    private $proyecto;
    private $personal;
    private $destino;

    public function __construct(){
        
        $this->requi=array();
        $this->detalle=array();
        $this->tipo=array();
        $this->tipos2=array();
        $this->proyecto=array();
        $this->personal=array();
        $this->destino=array();
    }

    public function get_personal(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT Identificacion,Nombres,Apellidos 
                FROM Rh_personal";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
            $this->personal[]='"'.$row["Identificacion"].','.$row["Nombres"].'_'.$row["Apellidos"].'"';
        }

        return $this->personal;
        $mysqli->close();
    }   

    
    public function nuevo_registro(){
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 AS nuevo 
                FROM Alm_sal 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
                
            $query="INSERT INTO Alm_sal(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
                VALUES(".$nuevo.",now(),'".$_SESSION["Cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",
                    ".$_SESSION["Id_centro"].");";
            mysql_query($query);
            header("Location:".Conexion::ruta()."Salidas?b=".$nuevo);
        }
    }
    
    public function get_requi($con){
        Conexion::conectar();
       $sql="SELECT Sal.Consecutivo,Sal.Festadodoc Fr,(SELECT Lista
                                                            FROM G_listas
                                                            WHERE Numeracion=Sal.Estadodoc
                                                            AND Id_control=1) as Lista,
                Centro.Nombre Centro_costo,Sucursal.Nombre Sucursal,Sal.Tipo,Sal.Proyecto,
                Sal.Destino,Sal.Observaciones,Sal.EntregadoA,Sal.Requisicion,
                Sal.Estadodoc

                FROM Alm_sal AS Sal
                JOIN G_centros_costo as Centro ON Sal.Centrocosto=Centro.Id
                JOIN G_sucursales as Sucursal ON Sal.Sucursal=Sucursal.Id
                
                WHERE Consecutivo=$con AND Centrocosto=1
                ";

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        
        if(isset($_POST["Requisicion"])){
            $requi=$_POST["Requisicion"];
        }else if(empty($_POST["Requisicion"])){
            $requi=0;
        }

        if(stripos($_POST["Proyecto"], ",")!==false){
            $d=explode(",",$_POST["Proyecto"]);
            $proyecto=$d[0];
            $sqlp="WHERE No=".$proyecto;
        }else if(empty($_POST["Proyecto"])){
            $sqlp="";
        }else{
            $proyecto=$_POST["Proyecto"];
            $sqlp="WHERE No=".$proyecto;
        }

        $sqlProyecto="SELECT * 
                        FROM G_proyectos
                        $sqlp

        ";
        $resProyecto=mysql_query($sqlProyecto);

        if(mysql_num_rows($resProyecto) > 0 ){
            $Proyecto=$proyecto;
        }

        if(stripos($_POST["EntregadoA"], ",")!==false){
            $d=explode(",",$_POST["EntregadoA"]);
            $entregadoA=$d[0];
            $sqlE="WHERE Identificacion=".$entregadoA;
        }else if(empty($_POST["EntregadoA"])){
            $entregadoA='';
        }else{
            $entregadoA=$_POST["EntregadoA"];
            $sqlE="WHERE Identificacion=".$entregadoA;
        }

        $sqlEntregado="SELECT * 
                        FROM 
                        Rh_personal
                        WHERE Identificacion=$entregadoA
        ";
        $resEntregado=mysql_query($sqlEntregado) ;
        if(mysql_num_rows($resEntregado) > 0 ){
            $EntregadoA=$entregadoA;
        }

        $sql="UPDATE Alm_sal SET Observaciones='".$_POST["Observaciones"]."',Estadodoc=4,
                            Proyecto='".$Proyecto."',Destino='".$_POST["Destino"]."',
                            EntregadoA='".$EntregadoA."',Tipo='".$_POST["Tipo"]."',
                            Requisicion=".$requi."
                            WHERE Consecutivo=".$_POST["Id"]." 
                            AND Centrocosto=".$_SESSION["Id_centro"];
            mysql_query($sql);

        if($EntregadoA=="" || $Proyecto==""){
            $error="&error=2";
        }

        
        header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id"].$error);
        
    }

    public function guardar_sin_order(){
        Conexion::conectar();
        echo "INGRESO sin order";exit;
        if(empty($_POST["Requisicion"])){
            $requi=0;
        }else{
            $requi=$_POST["Requisicion"];
        }

        if(stripos($_POST["Proyecto"],",")===false){
                $proyecto=$_POST["Proyecto"];
                
        }else{
                $d=explode(",",$_POST["Proyecto"]);    
                $proyecto=$d[0];
        }

         $sql="UPDATE Alm_sal 
        SET
            Observaciones='".$_POST["Observaciones"]."',Proyecto='".$proyecto."',
            Destino='".$_POST["Destino"]."',EntregadoA=".$_POST["EntregadoA"].",
            Tipo='".$_POST["Tipo"]."',Requisicion=".$requi."
        WHERE Consecutivo=".$_POST["Id"]." 
        AND Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id"]);
    }
  
    public function copiar($id){
        Conexion::conectar();
        $sql="SELECT Id_sal,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_saldet 
                    WHERE 
                    Id=".$id;
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $query="INSERT INTO Alm_saldet(Id_sal,Fr,Ur,Producto,Cantidad,Vu,Imp,
                Vn,Vt,Centrocosto) VALUES(".$row['Id_sal'].",now(),".$_SESSION["Cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["Id_centro"].")";
            
            mysql_query($query);
            header("Location:".Conexion::ruta()."Salidas?b=".$row["Id_sal"]);
        }
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


        if($_POST["Requi"]==0){
            $sqlSaldo="SELECT SUM(ent.Cantidad) Entradas,SUM(sal.Cantidad) Salidas,
                                SUM(ent.Cantidad)-SUM(sal.Cantidad) Saldo
                        FROM Alm_entdet ent
                        JOIN Alm_saldet sal
                        WHERE ent.Centrocosto=".$_SESSION["Id_centro"]."
                        AND sal.Producto='".$producto."'";
            $resSaldo=mysql_query($sqlSaldo);
            $totalSaldo=mysql_fetch_array($resSaldo);


            $sqlActual="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_saldet
                            WHERE Centrocosto=".$_SESSION["Id_centro"]."
                            AND Producto='".$producto."'";
            $resActual=mysql_query($sqlActual);
            $totalActual=mysql_fetch_array($resActual);

            if($totalSaldo["Saldo"] >= $totalActual["Cantidad"]){
                 $sql="INSERT INTO Alm_saldet(Id_sal,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                $sql.="values(".$_POST["Id_sal"].",now(),'".$_SESSION["Cedula"]."','".$producto."',
                ".$_POST["cantidad"].",".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",
                ".$total.",".$_SESSION["Id_centro"].")";   

                mysql_query($sql);
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]);
            }



        }else{
        $verificaRequi="SELECT Consecutivo
                        FROM Alm_requi
                        WHERE Consecutivo=".$_POST["Requi"]."
                        AND Centrocosto=".$_SESSION["Id_centro"];
        $resVerfica=mysql_query($verificaRequi);
        if(mysql_num_rows($resVerfica) > 0){

        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto}'
        ";
        $resBuscar=mysql_query($sqlBusqueda);
        if(mysql_num_rows($resBuscar) > 0){

            $sqlSaldo="SELECT SUM(ent.Cantidad) Entradas,SUM(sal.Cantidad) Salidas,
                                SUM(ent.Cantidad)-SUM(sal.Cantidad) Saldo
                        FROM Alm_entdet ent
                        JOIN Alm_saldet sal
                        WHERE ent.Id_ent=".$_POST["Id_sal"]."
                        AND sal.Id_sal=".$_POST["Id_sal"]."
                        AND ent.Centrocosto=".$_SESSION["Id_centro"];
            $resSaldo=mysql_query($sqlSaldo);
            $totalSaldo=mysql_fetch_array($resSaldo);

            $sqlActual="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_saldet
                            WHERE Id_sal=".$_POST["Requi"]."
                            AND Centrocosto=".$_SESSION["Id_centro"];
            $resActual=mysql_query($sqlActual);
            $totalActual=mysql_fetch_array($resActual);
            
            if($totalSaldo["Saldo"] >= $totalActual["Cantidad"]){
                $sql="INSERT INTO Alm_saldet(Id_sal,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
                $sql.="values(".$_POST["Id_sal"].",now(),'".$_SESSION["Cedula"]."','".$producto."',
                ".$_POST["cantidad"].",".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",
                ".$total.",".$_SESSION["Id_centro"].")";   

                mysql_query($sql);
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]);
            }else{
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]."&error=3");    
            }

            
        }else{
            header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]."&error=1");
        }
    }else{
        header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]."&error=4");
    }
     
    }//requi=0

    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * 
                    FROM Alm_saldet 
                    WHERE Id_sal=$id 
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

        if(stripos($_POST["producto"], ",")===false){
            $producto=$_POST["producto"];
            
        }else{
            $d=explode(",",$_POST["producto"]);    
            $producto=$d[0];
        }

        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto}'
        ";
        $resBuscar=mysql_query($sqlBusqueda);

        if(mysql_num_rows($resBuscar) > 0){
            
            if($_POST["Requi"]==0){

 $sqlSaldo="SELECT SUM(ent.Cantidad) Entradas,SUM(sal.Cantidad) Salidas,
                                SUM(ent.Cantidad)-SUM(sal.Cantidad) Saldo
                        FROM Alm_entdet ent
                        JOIN Alm_saldet sal
                        WHERE ent.Centrocosto=".$_SESSION["Id_centro"]."
                        AND sal.Producto='".$producto."'";
            $resSaldo=mysql_query($sqlSaldo);
            $totalSaldo=mysql_fetch_array($resSaldo);


            $sqlActual="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_saldet
                            WHERE Centrocosto=".$_SESSION["Id_centro"]."
                            AND Producto='".$producto."'";
            $resActual=mysql_query($sqlActual);
            $totalActual=mysql_fetch_array($resActual);

            if($totalSaldo["Saldo"] >= $totalActual["Cantidad"]){
                $sql="UPDATE Alm_saldet SET 
                    Producto='".$producto."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
                    Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." 
                    WHERE Id_sal=".$_POST["Id_sal"]."
                    AND Centrocosto=".$_SESSION["Id_centro"]; 

                mysql_query($sql);
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]);
            }else{
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]."&error=3");    
            }


            }else{

            $sqlSaldo="SELECT SUM(ent.Cantidad) Entradas,SUM(sal.Cantidad) Salidas,
                                SUM(ent.Cantidad)-SUM(sal.Cantidad) Saldo
                        FROM Alm_entdet ent
                        JOIN Alm_saldet sal
                        WHERE ent.Id_ent=".$_POST["Requi"]."
                        AND sal.Id_sal=".$_POST["Requi"]."
                        AND ent.Centrocosto=".$_SESSION["Id_centro"];
            $resSaldo=mysql_query($sqlSaldo);
            $totalSaldo=mysql_fetch_array($resSaldo);

            $sqlActual="SELECT SUM(Cantidad) Cantidad
                            FROM Alm_saldet
                            WHERE Id_sal=".$_POST["Id_sal"]."
                            AND Centrocosto=".$_SESSION["Id_centro"];
            $resActual=mysql_query($sqlActual);
            $totalActual=mysql_fetch_array($resActual);


            if($totalSaldo["Saldo"] >= $totalActual["Cantidad"]){
                $sql="UPDATE Alm_saldet SET 
                    Producto='".$producto."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
                    Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." 
                    WHERE Id_sal=".$_POST["Id_sal"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
            
                mysql_query($sql);
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]);   
            }else{
                header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]."&error=3");    
            }

        }//fin si

        }else{
            header("Location:".Conexion::ruta()."Salidas?b=".$_POST["Id_sal"]."&error=1");
        }

    }
    
    public function get_proyectos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT No,Nombre 
                FROM G_proyectos";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
            $this->proyecto[]='"'.$row["No"].','.$row["Nombre"].'"';
        }

        return $this->proyecto;
        $mysqli->close();
    }

    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_saldet where Id=".$id." and Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($sql);
    }

     public function imprime_documento($id){
        Conexion::conectar();

        $sqlDetalle="SELECT Producto 
                    FROM Alm_saldet 
                    WHERE Id_sal=$id 
                    AND Centrocosto=".$_SESSION["Id_centro"].";";

        $resDetalle=mysql_query($sqlDetalle);

        if(mysql_num_rows($resDetalle) > 0){
            while($res=mysql_fetch_array($resDetalle)){
                $sqlSaldo="SELECT Sal.Producto,SUM(Sal.Cantidad) Tsalida,SUM(Ent.Cantidad) Tentrada,
                                SUM(Ent.Cantidad) - SUM(Sal.Cantidad) Saldo
                                FROM Alm_saldet Sal
                                JOIN Alm_entdet Ent ON Sal.Id_sal=Ent.Id_ent 
                                WHERE Sal.Producto=".$res['Producto']."
                                AND Sal.Centrocosto={$_SESSION['Id_centro']}
                            ";
                $resSaldo=mysql_query($sqlSaldo);

                if($row=mysql_fetch_array($resSaldo)){
                    if($row["Saldo"] < 0){
                        header("Location:".Conexion::ruta()."Salidas?b=".$id."&error=3");   
                    }
                }
            }
        $sql="UPDATE Alm_sal set Estadodoc=2 where Consecutivo=$id";
        mysql_query($sql);    
        }
    }
    
    public function get_tipo(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles as c
        JOIN G_listas as l ON c.No=l.Id_control
        where Id_control=5
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tipo[]=$row;
        }
        return $this->tipo;
    }

    public function get_destino(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles as c
        JOIN G_listas as l ON c.No=l.Id_control
        where Id_control=10
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->destino[]=$row;
        }
        return $this->destino;
    }

     public function get_tipo2(){
        $mysqli=Conexion::c_mysqli();
        $sql="SELECT l.Id_lista,l.Lista
        FROM G_controles AS c
        JOIN
        G_listas AS l ON c.No=l.Id_control
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