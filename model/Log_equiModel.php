<?php
class Log_equi extends Conexion{
    
    private $requi;
    private $requi2;
    private $detalle;
    private $producto;
    private $consumo;
    private $trabajo;
    private $mante;
    private $equipos;
    private $actividad;
    private $palas;
   
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->requi2=array();
        $this->detalle=array();
        $this->producto=array();
        $this->consumo=array();
        $this->trabajo=array();
        $this->mante=array();
        $this->equipos=array();
        $this->actividad=array();
        $this->palas=array();
    }

    public function guardar_actividad(){
        Conexion::conectar();
        $sql="INSERT INTO Log_equi_actividades(Fr,Ur,Codigo,Descripcion,Valor,Sumat)
                        VALUES(now(),'".$_SESSION["Cedula"]."','".$_POST["Codigo"]."','".$_POST["Descripcion"]."',
                            ".$_POST["Valor"].",".$_POST["Sumat"].")";
        mysql_query($sql);
    }

     public function get_actividad(){
        Conexion::conectar();
        $sql="SELECT Codigo,Descripcion,Valor 
                FROM Log_equi_actividades";

        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
           $this->actividad[]='"'.$row["Codigo"].','.$row["Descripcion"].'"';
        }

        return $this->actividad;
    }  
    


    public function copiar_registro(){
        Conexion::conectar();
        switch ($_GET["tipo"]) {
            case 'Consumo':

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_consumo
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];
                $sql="SELECT * 
                            FROM
                            Log_equi_consumo
                            WHERE Id=".$_GET["Cop"];

                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){
                    $observaciones=(empty($row["observaciones"]))?"No tiene":$row["observaciones"];

                     echo $insertar="INSERT INTO Log_equi_consumo (Consecutivo,Id_consumo,Fr,Ur,Fecha,Sucursal,Centrocosto,
                             Proyecto,Proveedor,Documento,Producto,Tipo,Clasificacion,Serialeq,Cantidad,Vu,Imp,Vn,Vt,Observaciones)
                             VALUES(".$nuevo.",".$row["Id_consumo"].",now(),".$_SESSION['Cedula'].",now(),".
                                $_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",".$row["Proyecto"].",
                                '".$row["Proveedor"]."','".$row["Documento"]."',".$row["Producto"].",'".$row["Tipo"]."','".$row["Clasificacion"]."',
								'".$row["Serialeq"]."',".$row['Cantidad'].",".$row["Vu"].",".$row["Imp"].",
                                ".$row["Vn"].",".$row["Vt"].",'".$row["Observaciones"]."')
                     ";
                     
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_equi?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                }

                break;

            case 'Trabajo':{

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_trabajos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Log_equi_trabajos
                            WHERE Id=".$_GET["Cop"];
                            

                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_equi_trabajos (Consecutivo,Id_trabajos,Fr,Ur,Fecha,Sucursal,
                        Centrocosto,Proyecto,Serial,Documento,Actividad,Operario,Orometroi,Orometrof,Horastrab,
                        Profundidad,Ancho,Largo,Pilotes,Mtscubicos,Mtslineales,Porcentaje,Producto,
                        Cantidad,Vu,Imp,Vn,Vt,Observaciones,Tipo)
                             VALUES(".$nuevo['Nuevo'].",".$row["Id_trabajos"].",now(),".$_SESSION['Cedula'].",now(),".
                                $_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",'".$row["Proyecto"]."',
                                ".$row["Serial"].",".$row["Documento"].",'".$row["Actividad"]."','".$row["Operario"]."',
                                ".$row["Orometroi"].",'".$row["Orometrof"]."','".$row["Horastrab"]."','".$row["Profundidad"]."',
                                '".$row["Ancho"]."','".$row["Largo"]."','".$row["Pilotes"]."','".$row["Mtscubicos"]."',
                                '".$row["Mtslineales"]."','".$row["Porcentaje"]."','".$row["Producto"]."',
                                ".$row['Cantidad'].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",".$row["Vt"].",
                                '".$row["Observaciones"]."','".$row["Tipo"]."')
                     ";
					 
                     
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_equi?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Mantenimiento':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_mantenimiento
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];
                $sql="SELECT * 
                            FROM
                            Log_equi_mantenimiento
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_equi_mantenimiento (Consecutivo,Id_mante,Fr,Ur,Fecha,Sucursal,
                                Centrocosto,Proyecto,Serial,Documento,Tipo,Actividad,Cantidad,Vu,Imp,Vn,Vt,
                                Observaciones)
                             VALUES(".$nuevo.",".$row["Id_mante"].",now(),".$_SESSION['Cedula'].",now(),".
                                $_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",".$row["Proyecto"].",
                                '".$row["Serial"]."','".$row["Documento"]."','".$row["Tipo"]."','".$row["Actividad"]."',
                                ".$row['Cantidad'].",
                                ".$row["Vu"].",".$row["Imp"].",
                                ".$row["Vn"].",".$row["Vt"].",'".$row["Observaciones"]."')
                     ";
                     
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_equi?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }

			case 'Palas':{

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_trabajos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Log_equi_trabajos
                            WHERE Id=".$_GET["Cop"];
                            
  
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_equi_trabajos (Consecutivo,Id_trabajos,Fr,Ur,Fecha,Sucursal,
                        Centrocosto,Proyecto,Serial,Documento,Actividad,Operario,Orometroi,Orometrof,Horastrab,
                        Profundidad,Ancho,Largo,Pilotes,Mtscubicos,Mtslineales,Porcentaje,Producto,
                        Cantidad,Vu,Imp,Vn,Vt,Observaciones,Tipo)
                             VALUES(".$nuevo['Nuevo'].",".$row["Id_trabajos"].",now(),".$_SESSION['Cedula'].",now(),".
                                $_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",'".$row["Proyecto"]."',
                                ".$row["Serial"].",".$row["Documento"].",'".$row["Actividad"]."','".$row["Operario"]."',
                                ".$row["Orometroi"].",'".$row["Orometrof"]."','".$row["Horastrab"]."','".$row["Profundidad"]."',
                                '".$row["Ancho"]."','".$row["Largo"]."','".$row["Pilotes"]."','".$row["Mtscubicos"]."',
                                '".$row["Mtslineales"]."','".$row["Porcentaje"]."','".$row["Producto"]."',
                                ".$row['Cantidad'].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",".$row["Vt"].",
                                '".$row["Observaciones"]."','".$row["Tipo"]."')
                     ";
					 
                     
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_equi?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
        }
        
        
    }

    /**
    *INICIO MANEJO DE LOG EQUI CONSUMO
    */

    public function get_consumo($id){
        Conexion::conectar();
        $sql="SELECT * 
                FROM Log_equi_consumo 
                WHERE Id_consumo=$id
                AND Centrocosto={$_SESSION["Id_centro"]}
                ORDER BY Fr DESC";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->consumo[]=$row;

        }   
        return $this->consumo;
    }

    public function guardar_consumo(){
        Conexion::conectar();
        if(empty($_POST["Fecha"])){
           $fecha=date("Y-m-d");
        }else{
             $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        }

        if(stripos($_POST["Producto"],",")===false){
                $producto=$_POST["Producto"];
                
        }else{
                $d=explode(",",$_POST["Producto"]);    
                $producto=$d[0];
        }

        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor=$d[0];
        }

        $sqlproducto="SELECT * 
                        FROM 
                        G_producto
                        WHERE Codigo={$producto}
                    ";
        $resultadoBusqueda=mysql_query($sqlproducto);

        if(mysql_num_rows($resultadoBusqueda)){

            $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_consumo
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $nuevo=mysql_fetch_array($resultadoMayor);
            $nuevo=($nuevo["Nuevo"]==NULL)?1:$nuevo["Nuevo"];

            $sqlProveedor="SELECT *     
                            FROM
                            G_proveedores
                            WHERE Identificacion={$proveedor}
                            ";
            $resultadoBuscar=mysql_query($sqlProveedor);
            $proveedor=(mysql_num_rows($resultadoBuscar) > 0)?$proveedor:"";

            $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
            $iva=(FLOAT)$_POST["Impuesto"]/100.0;

            $valor_iva=$iva*$neto;
            $total=(FLOAT)$neto+(FLOAT)$valor_iva;
            
            

            $sql="INSERT INTO Log_equi_consumo(Consecutivo,Id_consumo,Fr,Ur,Fecha,Documento,Tipo,Producto,Clasificacion,Serialeq,Cantidad,Vu,Imp,Vn,
                                                Vt,Proveedor,Centrocosto,Observaciones)";
            $sql.="values(".$nuevo.",".$_POST["Consecutivo"].",now(),'".$_SESSION["Cedula"]."','".$fecha."','".$_POST["Documento"]."',
				'".$_POST["Tipo"]."','".$producto."','".$_POST["Clasificacion"]."','".$_POST["Serialeq"]."',".$_POST["Cantidad"].",
                ".$_POST["Vunitario"].",".$_POST["Impuesto"].",".$neto.",".$total.",{$proveedor},
                {$_SESSION["Id_centro"]},'{$_POST["Observaciones"]}')";
            
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"]);
        }else{
            
            header("Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"]);
        }
    }

     public function edit_consumo(){
        Conexion::conectar();
        $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        if(empty($_POST["Fecha"])){
           $fecha=date("Y-m-d");
        }else{
             $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        }

        if(stripos($_POST["Producto"],",")===false){
                $producto=$_POST["Producto"];
                
        }else{
                $d=explode(",",$_POST["Producto"]);    
                $producto=$d[0];
        }

        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor=$d[0];
        }

        $sqlproducto="SELECT * 
                        FROM 
                        G_producto
                        WHERE Codigo={$producto}
                    ";
        $resultadoBusqueda=mysql_query($sqlproducto);

        if(mysql_num_rows($resultadoBusqueda)>0){
           echo $sql="UPDATE Log_equi_consumo set Documento='".$_POST["Documento"]."', Producto='".$producto."',Tipo='".$_POST["Tipo"]."',
				Clasificacion='".$_POST["Clasificacion"]."',Cantidad=".$_POST["Cantidad"].",Vu=".$_POST["Vunitario"].",Imp=".$_POST["Impuesto"].",
				Vn=".$neto.",Fecha='".$fecha."',Vt=".$total.",Observaciones='".$_POST["Observaciones"]."' 
                WHERE Consecutivo=".$_POST["Consecutivodet"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        
        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"];
        header($dir); 
        }else{
            $dir="Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"];
        header($dir); 
        }

        
    }

    /**
    *FIN MANEJOR DE LOG EQUI CONSUMO
    */

    public function get_trabajo($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Log_equi_trabajos 
                WHERE Id_trabajos=$id AND Tipo='Palas'
                AND Centrocosto={$_SESSION["Id_centro"]}
                ORDER BY Fr";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->trabajo[]=$row;
        }   
        return $this->trabajo;
    }

    public function get_mantenimiento($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Log_equi_mantenimiento 
                WHERE Id_mante=$id
                AND Centrocosto={$_SESSION["Id_centro"]}
                ORDER BY Fr";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->mante[]=$row;

        }   
        return $this->mante;
    }

    public function guardar_mantenimiento(){
        Conexion::conectar();

        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        
        $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;

        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_mantenimiento 
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $nuevo=mysql_fetch_array($resultadoMayor);
        $nuevo=($nuevo["Nuevo"]==NULL)?1:$nuevo["Nuevo"];
        $observaciones=(empty($_POST["Observaciones"]))?"No tiene":$_POST["Observaciones"];

        $sql="INSERT INTO Log_equi_mantenimiento(Consecutivo,Id_mante,Fr,Ur,Fecha,Sucursal,
                        Centrocosto,Proyecto,Serial,Documento,Tipo,Actividad,Cantidad,Vu,Imp,Vn,
                            Vt,Observaciones)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$fecha."',
                ".$_SESSION["Id_sucursal"].",{$_SESSION["Id_centro"]},'".$_POST["Proyecto2"]."','".$_POST["Serialeq"]."',
                '".$_POST["Documento"]."','".$_POST["Tipo"]."','".$_POST["Actividad"]."',
                ".$_POST["Cantidad"].",".$_POST["Vunitario"].",".$_POST["Impuesto"].",".$neto.",".$total.",
                '".$observaciones."'
                )";

        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
    }

     public function edit_mante(){

        Conexion::conectar();
        $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        if(empty($_POST["Fecha"])){
           $fecha=date("Y-m-d");
        }else{
             $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        }
        
        $sql="UPDATE Log_equi_mantenimiento set Documento='".$_POST["Documento"]."',Actividad='".$_POST["Actividad"]."',
                        Cantidad=".$_POST["Cantidad"].",Vu=".$_POST["Vunitario"].",Fecha='".$fecha."',
                        Imp=".$_POST["Impuesto"].",Vn=".$neto.",Vt=".$total.",
                        Observaciones='".$_POST["Observaciones"]."' 
                    WHERE Consecutivo=".$_POST["Consecutivo_m"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];

        
        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
        header($dir);
    }
    
    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT Equi.Consecutivo,Sucursal.Nombre Sucursal,Centro.Nombre Centrocosto,Equi.Proyecto,
                    Equi.Serial,Equi.Valor,Equi.Marca,Equi.Modelo,Equi.Propiedad,Equi.Localizacion,Equi.Foto,Equi.Codigo
                    FROM Alm_equi Equi
                    JOIN G_sucursales Sucursal ON Equi.Sucursal=Sucursal.Id
                    JOIN G_centros_costo Centro ON Equi.Centrocosto=Centro.Id
                    WHERE Equi.Serial=$con
                    AND Equi.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_requi2($con){
        Conexion::conectar();
        $sql="SELECT Equi.Consecutivo,Sucursal.Nombre Sucursal,Centro.Nombre Centrocosto,Equi.Proyecto,
                    Equi.Serial,Equi.Valor,Equi.Marca,Equi.Modelo,Equi.Propiedad,Equi.Localizacion,Equi.Foto,Equi.Codigo
                    FROM Alm_equi Equi
                    JOIN G_sucursales Sucursal ON Equi.Sucursal=Sucursal.Id
                    JOIN G_centros_costo Centro ON Equi.Centrocosto=Centro.Id
                    WHERE Equi.Consecutivo=$con
                    AND Equi.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
  
    


    public function guardar_trabajo(){
        Conexion::conectar();            

         if(empty($_POST["Fecha"])){
           $fecha=date("Y-m-d");
        }else{
             $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        }

        if(stripos($_POST["Producto2"],",")===false){
                $producto=$_POST["Producto2"];
                
        }else{
                $d=explode(",",$_POST["Producto2"]);    
                $producto=$d[0];
        }

        $sqlproducto="SELECT * 
                        FROM 
                        G_producto
                        WHERE Codigo={$producto}
                    ";

        $resultadoBusqueda=mysql_query($sqlproducto);

        if(mysql_num_rows($resultadoBusqueda)){
            $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_trabajos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
            $resultadoMayor=mysql_query($mayor);
            $nuevo=mysql_fetch_array($resultadoMayor);
            $nuevo=($nuevo["Nuevo"]==NULL)?1:$nuevo["Nuevo"];

			$total=$_POST["Orometrof"]-$_POST["Orometroi"];

			if($total>0)
			{
				$horas=$_POST["Orometrof"]-$_POST["Orometroi"];
				

			}else
			{
				$horas=$_POST["Horas"];
			}
			//valida las formulas

			$Buscaequipo="SELECT Pof
			FROM Log_equi_actividades
			WHERE Codigo='{$_POST["Actividad"]}'     
			";
			$resBusqueda=mysql_query($Buscaequipo);
			$var=mysql_fetch_array($resBusqueda);
			$Pof=$var["Pof"];
			//pilotes
			$Mtscubicos=0;
			$Mtslineales=0;
			if($Pof==1){

				$Mtscubicos=$_POST["Pilotes"]-$_POST["Profundidad"];

			}//fresado
			elseif($Pof==2)
			{
				$Mtslineales=$_POST["Largo"]*$_POST["Ancho"]*$_POST["Profundidad"];
			}

            if(isset($_POST["Porcentaje"]) and $_POST["Porcentaje"]>0){
                $total=$horas*$_POST["Vunitario"]*($_POST["Porcentaje"]/100.0);    
            }else{
                $total=$horas*$_POST["Vunitario"];
            }
            

            $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
            $iva=(FLOAT)$_POST["Impuesto"]/100.0;

            $valor_iva=$iva*$neto;


            $observaciones=(empty($_POST["Observaciones"]))?"No tiene":$_POST["Observaciones"];

            $sql="INSERT INTO Log_equi_trabajos(Consecutivo,Id_trabajos,Fr,Ur,Fecha,Producto,Serial,Actividad,
                Horastrab,Documento,Cantidad,Vu,Imp,Vn,Vt,Centrocosto,Sucursal,Observaciones,Orometroi,Orometrof,
				Profundidad,Ancho,Largo,Pilotes,Mtscubicos,Mtslineales,Porcentaje,Tipo)";
            $sql.="values($nuevo,".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$fecha."','".$producto."',
            '".$_POST["Serialeq"]."','".$_POST["Actividad"]."',".$horas.",'".$_POST["Documento"]."',
            ".$_POST["Cantidad"].",
            ".$_POST["Vunitario"].",
            ".$_POST["Impuesto"].",".$neto.",".$total.",{$_SESSION["Id_centro"]},
            ".$_SESSION["Id_sucursal"].",'".$observaciones."',".$_POST["Orometroi"].",".$_POST["Orometrof"].",".$_POST["Profundidad"].",
			".$_POST["Ancho"].",".$_POST["Largo"].",".$_POST["Pilotes"].",".$Mtscubicos.",".$Mtslineales.",".$_POST["Porcentaje"].",'Palas')";
            
            
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
        }
    }

    public function edit_trabajo(){

        Conexion::conectar();
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

		$total=$_POST["Orometrof"]-$_POST["Orometroi"];

		if($total>0)
		{
				$horas=$_POST["Orometrof"]-$_POST["Orometroi"];


		}else
		{
				$horas=$_POST["Horas"];
		}

        if(stripos($_POST["Actividad"],",")===false){
                $actividad=$_POST["Actividad"];
                
        }else{
                $d=explode(",",$_POST["Actividad"]);    
                $actividad=$d[0];
        }

        $sql="UPDATE Log_equi_trabajos set Fecha='".$fecha."',Documento='".$_POST["Documento"]."',Actividad='".$actividad."',
                        Producto='".$_POST["Producto2"]."',Cantidad=".$_POST["Cantidad"].",Vu=".$_POST["Vunitario"].",
                        Imp=".$_POST["Impuesto"].",Vn=".$neto.",Vt=".$total.",Observaciones='".$_POST["Observaciones"]."',
                        Orometroi=".$_POST["Orometroi"].",Orometrof=".$_POST["Orometrof"].",Profundidad=".$_POST["Profundidad"]."
						,Ancho=".$_POST["Ancho"].",Largo=".$_POST["Largo"].",Pilotes=".$_POST["Pilotes"]."
						,Mtscubicos='".$_POST["Mtsc"]."',Mtslineales='".$_POST["Mts"]."',Porcentaje=".$_POST["Porcentaje"].",Horastrab=".$horas."
                    WHERE Consecutivo=".$_POST["Consecutivodet"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        echo 'sql'.$sql;

        
        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
        header($dir);
    }

    public function get_palasYfresas($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Log_equi_trabajos 
                WHERE Id_trabajos=$id AND Tipo='Trabajos'
                AND Centrocosto={$_SESSION["Id_centro"]}
                ORDER BY Fr";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->trabajo[]=$row;
        }   
        return $this->trabajo;
    }

    
    public function guardar_tra(){
        Conexion::conectar();            
        

        /*
            Verifica si la fecha llega en blanco para colocar la fecha actual
        */
         if(empty($_POST["Fecha"])){
           $fecha=date("Y-m-d");
        }else{
             $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        }


            $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_equi_trabajos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
            $resultadoMayor=mysql_query($mayor);
            $nuevo=mysql_fetch_array($resultadoMayor);

            /*Estos son como se tiene que hacer los calculos*/

            $nuevo=($nuevo["Nuevo"]==NULL)?1:$nuevo["Nuevo"];
            
			$total=$_POST["Orometrof"]-$_POST["Orometroi"];

			if($total>0)
			{
				$horas=$_POST["Orometrof"]-$_POST["Orometroi"];
				

			}else
			{
				$horas=$_POST["Horas"];
			}
	
			
            if(isset($_POST["Porcentaje"]) and $_POST["Porcentaje"]>0){
                $total=$horas*$_POST["Vu"]*($_POST["Porcentaje"]/100.0);    
            }else{
                $total=$horas*$_POST["Vu"];
            }
            

            $neto=$_POST["Vu"]*$_POST["Cantidad"];
            $iva=(FLOAT)$_POST["Impuesto"]/100.0;

            $valor_iva=$iva*$neto;

            /*
            Lo hago de esta manera para evitar errores en la insercion
            */

            $observaciones=(empty($_POST["Observaciones"]))?"No tiene":$_POST["Observaciones"];

            /*
                En esta parte es agregarle dejarle solo los campos necesarios que se van a utilizar

            */
            $sql="INSERT INTO Log_equi_trabajos(Consecutivo,Id_trabajos,Fr,Ur,Fecha,Producto,Serial,Actividad,
                Horastrab,Documento,Cantidad,Vu,Imp,Vn,Vt,Centrocosto,Sucursal,Observaciones,Orometroi,Orometrof,Tipo)";
            $sql.="values($nuevo,".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$fecha."','".$producto."',
            '".$_POST["Serialeq"]."','".$_POST["Actividad"]."',".$horas.",'".$_POST["Documento"]."',
            '".$_POST["Cantidad"]."',
            '".$_POST["Vu"]."',
            '".$_POST["Impuesto"]."',".$neto.",".$total.",{$_SESSION["Id_centro"]},
            ".$_SESSION["Id_sucursal"].",'".$observaciones."',".$_POST["Orometroi"].",".$_POST["Orometrof"].",'Trabajos')";
            

            
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Log_equi?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
        
    }

    public function elimina_consumo($id,$con,$tipo){
        Conexion::conectar();
        switch ($tipo) {
            case 'Consumo':
                $sql="DELETE from Log_equi_consumo where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_equi?Con=".$con."&tipo=".$tipo;
                header($dir);         
                break;
            case 'Trabajo':
                $sql="DELETE from Log_equi_trabajos where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_equi?Con=".$con."&tipo=".$tipo;
                header($dir);         
                break;
            case 'Mantenimiento':
                $sql="DELETE from Log_equi_mantenimiento where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_equi?Con=".$con."&tipo=".$tipo;
                header($dir); 
            case 'Equipo':
                $sql="DELETE from Log_equi_actividades where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_equi?Con=".$con."&tipo=".$tipo;
                header($dir);
            default:
                # code...
                break;
        }
        
    }

    
    public function get_productos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT Codigo,Descripcion from G_producto";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
           $this->producto[]='"'.$row["Codigo"].','.$row["Descripcion"].'"';
        }

        return $this->producto;
    }    

}

?>
