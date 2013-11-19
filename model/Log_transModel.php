<?php
class Log_vehi extends Conexion{
    
    private $requi;
    private $detalle;
    private $consumo;
    private $viajes;
    private $tanqueadas;
    private $parqueo;
    private $tipoVehiculo;
    private $origen;
    private $bonificacion;
    private $tipo;
	private $Centrocosto;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->consumo=array();
        $this->viajes=array();
        $this->tanqueadas=array();
        $this->parqueo=array();
        $this->tipoVehiculo=array();
        $this->origen=array();
        $this->bonificacion=array();
        $this->tipo=array();
		$this->Centrocosto=array();
    }

	public function get_Centrocosto(){
        Conexion::conectar();
        $sql="SELECT Id , Nombre  
        FROM G_centros_costo
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->Centrocosto[]=$row;
        }
        return $this->Centrocosto;
    }


    public function get_tipoVehiculo(){
        Conexion::conectar();
        $sql="SELECT Id,Tipo
                FROM G_tipoVehiculo";
        $res=mysql_query($sql);
        while($row=mysql_fetch_array($res)){
            $this->tipoVehiculo[]='"'.$row["Id"].','.$row["Tipo"].'"';
        }
        return $this->tipoVehiculo;
    }
    
    public function guardar_vehiculo(){
        Conexion::conectar();

        $sql="INSERT INTO G_tipoVehiculo(Tipo)
                    VALUES('".$_POST["Descripcion"]."')";
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Consecutivo"]."&tipo=".$_GET["tipo"]);
    }

    public function get_origen(){
        Conexion::conectar();
        $sql="SELECT Id,Descripcion,Valor_parqueo,Valor_transporte
                FROM G_OrigenesyDestinos";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->origen[]='"'.$row["Id"].','.$row["Descripcion"].','.$row["Valor_parqueo"].'"';
        }
        return $this->origen;
    }

    public function guardar_origen(){
        Conexion::conectar();
        $sql="INSERT INTO G_OrigenesyDestinos(Descripcion,Valor_parqueo,Valor_transporte)
                    VALUES('".$_POST["Descripcion"]."',".$_POST["Valor_p"].",".$_POST["Valor_t"].")";
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Consecutivo"]."&tipo=".$_GET["tipo"]);
    }

    public function get_bonificaciones(){
        Conexion::conectar();
        $sql="SELECT Id,Tvehiculo,Km_desde,Km_hasta,Vbonificacion
                FROM G_bonificaciones";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->bonificacion[]='"'.$row["Id"].','.$row["Tvehiculo"].','.$row["Km_desde"].','.$row["Km_hasta"].'"';
        }
        return $this->bonificacion;
    }


    public function guardar_boni(){
        Conexion::conectar();
        $sql="INSERT INTO G_bonificaciones(Tvehiculo,Km_desde,Km_hasta,Vbonificacion)
                    VALUES('".$_POST["Tipo_v"]."',".$_POST["Km_desde"].",".$_POST["Km_hasta"].",
                        ".$_POST["Vbonificacion"].")";
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Consecutivo"]."&tipo=".$_GET["tipo"]);
    }

    public function get_tipoViaje(){
        Conexion::conectar();
        $sql="SELECT Id,Descripcion,M3km
                FROM Log_vehi_tipoviaje";
        $res=mysql_query($sql);
        while($row=mysql_fetch_assoc($res)){
            $this->tipo[]='"'.$row["Id"].','.$row["Descripcion"].','.$row["M3km"].'"';
        }
        return $this->tipo;
    }

    public function guardar_tipo(){
        Conexion::conectar();
        $sql="INSERT INTO G_tipoViaje(Descripcion,M3km)
                    VALUES('".$_POST["Descripcion"]."',".$_POST["M3km"].")";
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Consecutivo"]."&tipo=".$_GET["tipo"]);
    }

    public function copiar_registro(){
        Conexion::conectar();
        switch ($_GET["tipo"]) {
            case 'Consumo':

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_consumo
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Log_vehi_consumo
                            WHERE Id=".$_GET["Cop"];

                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){
                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_vehi_consumo (Consecutivo,Id_consumo,Fr,Ur,Fecha,Sucursal,
                        Centrocosto,Proyecto,Documento,Proveedor,Vehiculo,Producto,Tipo,Clasificacion,Cantidad,Vu,Imp,Vn,
                        Vt,Observaciones)
                             VALUES(".$nuevo.",".$row["Id_consumo"].",now(),'".$_SESSION['Cedula']."',now(),".
                                $_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",".$row["Proyecto"].",
                                ".$row["Documento"].",".$row["Proveedor"].",".$row["Vehiculo"].",
                                ".$row["Producto"].",".$row['Cantidad'].",".$row['Tipo'].",".$row['Clasificacion'].",".$row["Vu"].",".$row["Imp"].",
                                ".$row["Vn"].",".$row["Vt"].",'".$row["Observaciones"]."')
                     ";
                     
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_trans?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                }

                break;

            case 'Viajes':{

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_viajes
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Log_vehi_viajes
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_vehi_viajes(Consecutivo,Id_viajes,Fr,Ur,Fecha,Sucursal,Centrocosto,
                        Proyecto,Documento,Tipob,Valecantera,Vehiculo,Tipoviaje,Origen,Destino,Conductor,Cantviajes,
                        Propio,Kmrecorridos,Vbonificacion,Vmateriales,Vtransporte,Porcentaje,
                        M3,M3transp,Vm3km,Volumen,Apuasoc,Observaciones)

                             VALUES(".$nuevo.",".$row["Id_viajes"].",now(),".$_SESSION['Cedula'].",now(),".
                                $_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",".$row["Proyecto"].",
                                ".$row["Documento"].",'".$row["Tipob"]."',".$row["Valecantera"].",".$row["Vehiculo"].",
                                '".$row["Tipoviaje"]."',".$row["Origen"].",'".$row["Destino"]."','".$row["Conductor"]."',
                                '".$row["Cantviajes"]."','".$row["Propio"]."',
                                '".$row["Kmrecorridos"]."','".$row["Vbonificacion"]."','".$row["Vmateriales"]."',
                                ".$row["Vtransporte"].",".$row['Porcentaje'].",".$row["M3"].",".$row["M3transp"].",
                                ".$row["Vm3km"].",".$row["Volumen"].",".$row["Apuasoc"].",'".$observaciones."')
                     ";
                    
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_trans?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Tanqueadas':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_tanqueadas
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Log_vehi_tanqueadas
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_vehi_tanqueadas(Consecutivo,Id_tanqueadas,Fr,Ur,Fecha,Sucursal,
                                Centrocosto,Documento,Proveedor,Vehiculo,Galones,Hora,Km,Vu,Imp,Vn,
                                Vt,Observaciones)

                             VALUES(".$nuevo.",".$row["Id_tanqueadas"].",now(),".$_SESSION['Cedula'].",now(),
                                ".$_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",
                                '".$row["Documento"]."','".$row["Proveedor"]."','".$row["Vehiculo"]."','".$row["Galones"]."',
                                '".$row['Hora']."',".$row["Km"].",".$row["Vu"].",".$row["Imp"].",
                                ".$row["Vn"].",".$row["Vt"].",'".$row["Observaciones"]."')
                     ";
                     
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_trans?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Parqueos':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_parqueos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Log_vehi_parqueos
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Log_vehi_parqueos(Consecutivo,Id_parqueo,Fr,Ur,Fecha,Sucursal,
                                Centrocosto,Proyecto,Documento,Vehiculo,Conductor,Direccion,Valor,Valortransp
                                ,Observaciones)

                             VALUES(".$nuevo.",".$row["Id_parqueo"].",now(),".$_SESSION['Cedula'].",now(),
                                ".$_SESSION['Id_sucursal'].",".$_SESSION["Id_centro"].",'".$row["Proyecto"]."',
                                '".$row["Documento"]."','".$row["Vehiculo"]."','".$row["Conductor"]."',
                                '".$row["Direccion"]."','".$row['Valor']."',".$row["Valortransp"].",
                                '".$row["Observaciones"]."')
                     ";
                
                     mysql_query($insertar);
                     header("Location:".Conexion::ruta()."Log_trans?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
            }
        }
        
        
    }


    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT Vehi.Consecutivo,Sucursal.Nombre Sucursal,Centro.Nombre Centro_costo,
                            Vehi.Proyecto,Vehi.Placa,Vehi.Valor,Vehi.Marca,Vehi.Modelo,Vehi.Tipo,
                            Vehi.Conductor,Vehi.M3,Vehi.M3transporte
                    FROM 
                    Log_vehi as Vehi
                    JOIN G_sucursales Sucursal ON Vehi.Sucursal=Sucursal.Id
                    JOIN G_centros_costo Centro ON Vehi.Centrocosto=Centro.Id
                    WHERE Vehi.Placa='".$con."';";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        
        return $this->requi;    
    }
    
    public function get_requi2($con){
        Conexion::conectar();

        $sql="SELECT Vehi.Consecutivo,Sucursal.Nombre Sucursal,Centro.Nombre Centro_costo,
                            Vehi.Proyecto,Vehi.Placa,Vehi.Valor,Vehi.Marca,Vehi.Modelo,Vehi.Tipo,
                            Vehi.Conductor,Vehi.M3,Vehi.M3transporte
                    FROM 
                    Log_vehi as Vehi
                    JOIN G_sucursales Sucursal ON Vehi.Sucursal=Sucursal.Id
                    JOIN G_centros_costo Centro ON Vehi.Centrocosto=Centro.Id
                    WHERE Vehi.Consecutivo='".$con."';";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        
        return $this->requi;    
    }
    
    
    
    public function guardar_consumo(){
        Conexion::conectar();
        $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;

        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor=$d[0];
        }

        if(stripos($_POST["Producto"],",")===false){
                $producto=$_POST["Producto"];
                
        }else{
                $d=explode(",",$_POST["Producto"]);    
                $producto=$d[0];
        }

        $Buscacentro="SELECT Id_sucursal 
                        FROM G_centros_costo
                        WHERE Id='{$_POST["Centro"]}'

                ";
        $resBusqueda=mysql_query($Buscacentro);
        $var=mysql_fetch_array($resBusqueda);
        $Id_sucursal=$var["Id_sucursal"];

        $buscaProducto="SELECT * 
                        FROM G_producto
                        WHERE Codigo={$producto}
        ";
        $resBusqueda=mysql_query($buscaProducto);

        if(mysql_num_rows($resBusqueda) > 0 ){

            $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_consumo
                            WHERE Centrocosto={$_POST["Centro"]}

                ";
            $resultadoMayor=mysql_query($mayor);
            $variable=mysql_fetch_array($resultadoMayor);
            $nuevo=($variable["Nuevo"]==NULL)?1:$variable["Nuevo"];

            $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

            $observaciones=(!empty($_POST["Observaciones"]))?$_POST["Observaciones"]:"No tiene";

            $sql="INSERT INTO Log_vehi_consumo(Consecutivo,Id_consumo,Fr,Ur,Fecha,Documento,Proveedor,Vehiculo,Producto,Tipo,Clasificacion
                Cantidad,Vu,Imp,Vn,Vt,Centrocosto,Sucursal,Observaciones)";
            $sql.="VALUES($nuevo,".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."',".$fecha.",'".$_POST["Documento"]."'
                ,'".$proveedor."','".$_POST["Vehiculo"]."','".$producto."',".$_POST["Tipo"].",".$_POST["Clasificacion"].",
                ".$_POST["Cantidad"].",".$_POST["Vunitario"].",".$_POST["Impuesto"].",".$neto.",".$total."
                ,'".$_POST["Centro"]."',".$Id_sucursal.",'".$observaciones."')";
            
            mysql_query($sql);
            $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
            header($dir);
        }else{
            $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
            header($dir);
        }
    }

    public function edit_consumo(){
        Conexion::conectar();
        $neto=$_POST["Vunitario"]*$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor=$d[0];
        }

        if(stripos($_POST["Producto"],",")===false){
                $producto=$_POST["Producto"];
                
        }else{
                $d=explode(",",$_POST["Producto"]);    
                $producto=$d[0];
        }

        $buscaProducto="SELECT * 
                        FROM G_producto
                        WHERE Codigo={$producto}
        ";
        $resBusqueda=mysql_query($buscaProducto);

        if(mysql_num_rows($resBusqueda) > 0 ){
            $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

            $sql="UPDATE Log_vehi_consumo SET Fecha='".$fecha."', Producto='".$producto."',Tipo=".$_POST["Tipo"].",
                        Clasificacion=".$_POST["Clasificacion"].",Cantidad=".$_POST["Cantidad"].",Vu=".$_POST["Vunitario"].",
                        Vehiculo='".$_POST["Vehiculo"]."',Proveedor='".$proveedor."',
                        Documento='".$_POST["Documento"]."',
                        Imp=".$_POST["Impuesto"].",Vn=".$neto.",Vt=".$total.",Observaciones='".$_POST["Observaciones"]."' 
                    WHERE Consecutivo=".$_POST["Consecutivo_c"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];

            mysql_query($sql);
            $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
            header($dir); 
        }

        
    }

    public function guardar_viajes(){
        Conexion::conectar();
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_viajes
                            WHERE Centrocosto={$_POST["Centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $variable=mysql_fetch_array($resultadoMayor);

        $nuevo=($variable["Nuevo"]==NULL)?1:$variable["Nuevo"];


        $observaciones=(!empty($_POST["Observaciones"]))?$_POST["Observaciones"]:"No tiene";
		//proyectos
        if(stripos($_POST["Proyecto"],",")===false){
                $proyecto=$_POST["Proyecto"];
                
        }else{
                $d=explode(",",$_POST["Proyecto"]);    
                $proyecto=$d[0];
        }
		//Tipoviaje
		if(stripos($_POST["Tipoviaje"],",")===false){
                $Tipoviaje=$_POST["Tipoviaje"];
                
        }else{
                $d=explode(",",$_POST["Tipoviaje"]);    
                $Tipoviaje=$d[0];
        }

		//Origen
		if(stripos($_POST["Origen"],",")===false){
                $Origen=$_POST["Origen"];
                
        }else{
                $d=explode(",",$_POST["Origen"]);    
                $Origen=$d[0];
        }

		//Destino
		if(stripos($_POST["Destino"],",")===false){
                $Destino=$_POST["Destino"];
                
        }else{
                $d=explode(",",$_POST["Destino"]);    
                $Destino=$d[0];
        }
		//bonificaciones
		$Kmrecorridos=$_POST["Kmrecorridos"];

		//Calculo interno
        $sql1="SELECT Interno
                FROM Log_vehi_tipoviaje WHERE Id='".$Tipoviaje."'";
        $res1=mysql_query($sql1);
		$var1=mysql_fetch_array($res1);
		if(mysql_num_rows($res1)> 0){
			$M3km=$var1["M3km"];
		}else
		{
			$M3km=$_POST["Vm3km"];
		}
		//formula de valor trasporte

		$Vtras=$_POST["Kmrecorridos"]*$M3km*$_POST["M3transp"]*$_POST["Cantviajes"];

        $Buscacentro="SELECT Id_sucursal 
                        FROM G_centros_costo
                        WHERE Id='{$_POST["Centro"]}'

                ";
        $resBusqueda=mysql_query($Buscacentro);
        $var=mysql_fetch_array($resBusqueda);
        $Id_sucursal=$var["Id_sucursal"];

		$Valecantera=$_POST["Valecantera"];
		$buscaDuplica="SELECT * 
                        FROM Log_vehi_viajes
                        WHERE Valecantera={$Valecantera}
        ";
        $resBusqueda=mysql_query($buscaDuplica);
        if(mysql_num_rows($resBusqueda) == 0 ){

        $sql="INSERT INTO Log_vehi_viajes(Consecutivo,Id_viajes,Fr,Ur,Fecha,Sucursal,Centrocosto,Proyecto,
                        Documento,Tipob,Valecantera,Vehiculo,Tipoviaje,Origen,Destino,Conductor,Cantviajes,
                        Propio,Kmrecorridos,Vbonificacion,Vmateriales,Vtransporte,Porcentaje,
                        M3,M3transp,Vm3km,Volumen,Apuasoc,Observaciones)";
        $sql.="values($nuevo,".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$fecha."',".$Id_sucursal."
            ,".$_POST["Centro"].",".$proyecto.",'".$_POST["Documento"]."','".$_POST["Tipob"]."',
            ".$_POST["Valecantera"].",".$_POST["Vehiculo"].",".$Tipoviaje.",
            '".$Origen."',".$Destino.",".$_POST["Conductor"].",".$_POST["Cantviajes"].",
            ".$_POST["Propio"].",'".$_POST["Kmrecorridos"]."'
            ,'".$_POST["Vbonificacion"]."','".$_POST["Vmateriales"]."','".$Vtras."'
            ,'".$_POST["Porcentaje"]."','".$_POST["M3"]."','".$_POST["M3transp"]."','".$_POST["Vm3km"]."'
            ,'".$_POST["Volumen"]."','".$_POST["Apuasoc"]."','".$observaciones."')";

        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);

				}else{
            $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
            header($dir);
        }
    }

     public function edit_viajes(){
        print_r($_POST);
        Conexion::conectar();
        $sql="UPDATE Log_vehi_viajes set Documento='".$_POST["Documento"]."',Tipob='".$_POST["Tipob"]."',
            ,Vehiculo=".$_POST["Vehiculo"].",
            Tipoviaje=".$_POST["Tipoviaje"].",Origen=".$_POST["Tipoviaje"].",Destino=".$_POST["Destino"].",
            Conductor=".$_POST["Conductor"].",Cantviajes=".$_POST["Cantviajes"].",Propio=".$_POST["Propio"].",Kmrecorridos=".$_POST["Kmrecorridos"].",
            Vbonificacion=".$_POST["Vbonificacion"].",Vmateriales=".$_POST["Vmateriales"].",
            Vtransporte=".$_POST["Vtransporte"].",Porcentaje=".$_POST["Porcentaje"].",M3=".$_POST["M3"].",
            M3transp=".$_POST["M3transp"].",Vm3km=".$_POST["Vm3km"].",Volumen=".$_POST["Volumen"].",
            Apuasoc=".$_POST["Apuasoc"].",Observaciones='".$_POST["Observaciones"]."'
        WHERE Consecutivo=".$_POST["consecutivo_v"];
        
        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
        header($dir); 
    }


     public function guardar_tanqueadas(){
        Conexion::conectar();
        $neto=$_POST["Vu"]*$_POST["Galones"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;
        
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
		$Documento=$_POST["Documento"];


        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor=$d[0];
        }


        $Buscacentro="SELECT Id_sucursal 
                        FROM G_centros_costo
                        WHERE Id='{$_POST["Centro"]}'

                ";
        $resBusqueda=mysql_query($Buscacentro);
        $var=mysql_fetch_array($resBusqueda);
        $Id_sucursal=$var["Id_sucursal"];

        
		$buscaDuplica="SELECT * 
                        FROM Log_vehi_tanqueadas
                        WHERE Documento={$Documento}
        ";
        $resBusqueda=mysql_query($buscaDuplica);

        if(mysql_num_rows($resBusqueda) < 0 ){



        echo $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Log_vehi_viajes
                            WHERE Centrocosto={$_POST["Centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $variable=mysql_fetch_array($resultadoMayor);
        $nuevo=($variable["Nuevo"]==NULL)?1:$variable["Nuevo"];
        $observaciones=(!empty($_POST["Observaciones"]))?$_POST["Observaciones"]:"No tiene";

        $sql="INSERT INTO Log_vehi_tanqueadas(Consecutivo,Id_tanqueadas,Fr,Ur,Fecha,Sucursal,Centrocosto,
                            Documento,Proveedor,Vehiculo,Galones,Hora,Km,Vu,Imp,Vn,Vt,Observaciones)";

        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$fecha."',
            '".$Id_sucursal."','".$_POST["Centro"]."',
            '".$_POST["Documento"]."','".$proveedor."','".$_POST["Vehiculo"]."',
            '".$_POST["Galones"]."','".$_POST["Hora"]."','".$_POST["Km"]."',".$_POST["Vu"].",
            ".$_POST["Impuesto"].",".$neto.",".$total.",'".$observaciones."')";
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
		}else{
            $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
            header($dir);
        }
    }

    public function edit_tanqueadas(){
        print_r($_POST);
        Conexion::conectar();
        $neto=$_POST["Vu"]*$_POST["Galones"];
        $iva=(FLOAT)$_POST["Impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);


        if(stripos($_POST["Proveedor"],",")===false){
                $proveedor=$_POST["Proveedor"];
                
        }else{
                $d=explode(",",$_POST["Proveedor"]);    
                $proveedor=$d[0];
        }
		$buscaDuplica="SELECT * 
                        FROM Log_vehi_tanqueadas
                        WHERE Documento={$Documento}
        ";
        $resBusqueda=mysql_query($buscaDuplica);

        if(mysql_num_rows($resBusqueda) > 0 ){


        $sql="UPDATE Log_vehi_tanqueadas SET Fecha='".$fecha."',
                Documento='".$_POST["Documento"]."',Vehiculo='".$_POST["Vehiculo"]."',
                Proveedor='".$_POST["Proveedor"]."',Galones='".$_POST["Galones"]."',
                Km='".$_POST["Km"]."',Vu=".$_POST["Vu"].",
                Imp=".$_POST["Impuesto"].",Vn=".$neto.",Vt=".$total.",Observaciones='".$_POST["Observaciones"]."' 
                WHERE Consecutivo=".$_POST["consecutivo_t"]." AND Centrocosto=".$_SESSION["Id_centro"];
        
        

        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
        header($dir);
				}else{
            $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
            header($dir);
        }
    }




    public function guardar_parqueos(){
        Conexion::conectar();
		
		$fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
		
		$Verificaviajes="SELECT * 
                        FROM Log_vehi_viajes
                        WHERE Fecha='".$fecha."' and Vehiculo={$_POST["Vehiculo"]}
        ";
        $buscaverifica=mysql_query($Verificaviajes);
        if(mysql_num_rows($buscaverifica) < 1 ){

       

        if(stripos($_POST["Proyecto"],",")===false){
                $proyecto=$_POST["Proyecto"];
                
        }else{
                $d=explode(",",$_POST["Proyecto"]);    
                $proyecto=$d[0];
        }

		        $Buscacentro="SELECT Id_sucursal 
                        FROM G_centros_costo
                        WHERE Id='{$_POST["Centro"]}'

                ";
        $resBusqueda=mysql_query($Buscacentro);
        $var=mysql_fetch_array($resBusqueda);
        $Id_sucursal=$var["Id_sucursal"];

        $sql="INSERT INTO Log_vehi_parqueos(Id_parqueo,Fr,Ur,Centrocosto,Sucursal,Fecha,Vehiculo,Conductor,
            Direccion,Valor,Valortransp,Observaciones)";

        $sql.="values(".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Centro"]."','".$Id_sucursal."','".$fecha."','".$_POST["Vehiculo"]."','".$_POST["Conductor"]."',
            '".$_POST["Direccion"]."','".$_POST["Valor"]."','".$_POST["Valortransp"]."',
            '".$_POST["Observaciones"]."')";
        

        mysql_query($sql);
        header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
		}else
		{
			header("Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
		}
    }

    public function edit_parqueos(){
        Conexion::conectar();
        print_r($_POST);

        echo $sql="UPDATE Log_vehi_parqueos set 
                    Vehiculo=".$_POST["Vehiculo"].",Conductor=".$_POST["Conductor"].",
                    Direccion=".$_POST["Direccion"].", Valor=".$_POST["Valor"].",Valortransp=".$_POST["Valortransp"].",
                    Observaciones='".$_POST["Observaciones"]."'
                WHERE Consecutivo=".$_POST["consecutivo_p"]." AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Log_trans?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"];
        header($dir);

    }


    
    public function get_consumo($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Log_vehi_consumo where Id_consumo=$id";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->consumo[]=$row;
        }

        return $this->consumo;
    }

    public function get_viajes($id){
        Conexion::conectar();
        $sql="SELECT * from Log_vehi_viajes 
                WHERE Id_viajes=$id";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->viajes[]=$row;
        }

        return $this->viajes;
    }


    public function get_tanqueadas($id){
        Conexion::conectar();
        $sql="SELECT * from Log_vehi_tanqueadas where Id_tanqueadas=$id";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tanqueadas[]=$row;
        }

        return $this->tanqueadas;
    }

    public function get_parqueos($id){
        Conexion::conectar();
        $sql="SELECT * 
                FROM Log_vehi_parqueos 
                WHERE Id_parqueo=$id";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->parqueo[]=$row;
        }

        return $this->parqueo;
    }


    public function edit_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="UPDATE Alm_entdet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
        Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"];
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id_ent"]); 

    }
    
    public function elimina_consumo($id,$con,$tipo){
        Conexion::conectar();
        switch ($tipo) {
            case 'Consumo':
                $sql="DELETE from Log_vehi_consumo where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_trans?Con=".$con."&tipo=".$tipo;
                header($dir);         
                break;
            case 'Viajes':
                $sql="DELETE from Log_vehi_viajes where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_trans?Con=".$con."&tipo=".$tipo;
                header($dir);         
                break;
            case 'Tanqueadas':
                $sql="DELETE from Log_vehi_tanqueadas where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_trans?Con=".$con."&tipo=".$tipo;
                header($dir); 
            case 'Parqueos':
                $sql="DELETE from Log_vehi_parqueos where Id=$id";
                mysql_query($sql);
                $dir="Location:".Conexion::ruta()."Log_trans?Con=".$con."&tipo=".$tipo;
                header($dir);
            default:
                # code...
                break;
        }
        
    }
    


}

?>

