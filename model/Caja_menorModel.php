<?php
class C_menor extends Conexion{

    private $requi;
    private $detalle;
    private $cajas;
	private $Proveerdores;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->cajas=array();
		$this->Proveerdores=array();
		
    }

    public function get_cajas(){
        Conexion::conectar();
        $sql="SELECT * from Ctb_cm";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->cajas[]=$row;
        }
        return $this->cajas;
    }

	public function get_proveedores(){
        Conexion::conectar();
        $sql="SELECT Nit , Pnombre  from Ctb_Proveerdores";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->Proveerdores[]='"'.$row["Nit"].','.$row["Pnombre"].'"';
        }

        return $this->Proveerdores;
        
    }
	
    public function Copiar(){
        Conexion::conectar();
        print_r($_GET);
        echo $sql="SELECT * 
                FROM Ctb_cmdet
                WHERE Id_cm=".$_GET["id"]."";
        $resultado=mysql_query($sql);
        
        if($row=mysql_fetch_array($resultado)){
            echo $sqlmayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Ctb_cmdet
                            AND Centrocosto=".$_SESSION["Id_centro"];
            $resMayor=mysql_query($sqlmayor);
            $var=mysql_fetch_array($resMayor);
            $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

            $insertar="INSERT INTO Ctb_cmdet(Consecutivo,Id_cm,Fr,Ur,FechaRembolso,Norembolso,Nit,Cuentaocod,Valordebito,
                Basederetencion,Vderetencion,Baseiva,Viva,Concepto,Cuiva,Curetencion,Centrocosto)
            VALUES(".$nuevo.",".$row["Id_cm"].",".$row["Fr"].",".$_SESSION["Id_centro"].",'".$row["FechaRembolso"]."',
                    ".$row["Norembolso"].",".$row["Nit"].",".$row["Cuentaocod"].",".$row["Valordebito"].",
                    ".$row["Basederetencion"].",".$row["Vderetencion"].",".$row["Baseiva"].",".$row["Viva"].",
                    '".$row["Concepto"]."',".$row["Cuiva"].",".$row["Curetencion"].",".$_SESSION["Id_centro"].");

            ";
            
            mysql_query($insertar);
            header("Location:".Conexion::ruta()."Cont_caja?b=".$row["Id_cm"]);        
        }        
        
    }

    public function nuevo_registro(){
        $nuevo;
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo  
                FROM Ctb_cm 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];

            $query="INSERT INTO Ctb_cm(Consecutivo,Fr,Ur,Estadodoc,Sucursal,Centrocosto)
            values($nuevo,now(),'".$_SESSION["Cedula"]."','".$estado."',".$_SESSION["Id_sucursal"].",
                ".$_SESSION["Id_centro"].");";
            mysql_query($query);
            
            header("Location:".Conexion::ruta()."Cont_caja?b=".$nuevo);        
        
        }
    }

    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT *
                    FROM Ctb_cm 
                    WHERE Consecutivo=$con 
                    AND Centrocosto=".$_SESSION["Id_centro"];

        $res=mysql_query($sql) or mysql_error();

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * 
                FROM Ctb_cmdet 
                WHERE Id_cm=$id 
                AND Centrocosto=".$_SESSION["Id_centro"];
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }

    public function guardar_registro(){
        Conexion::conectar();

        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

        $sql="UPDATE Ctb_cm set Fecha='".$fecha."', 
                            Nodocrem='".$_POST["Nodocrem"]."',
                            Codcontable='".$_POST["Codcontable"]."',Nocajasis='".$_POST["Nocajasis"]."',
                            Viva='".$_POST["Viva"]."',Observaciones='".$_POST["Observaciones"]."'
                    WHERE Consecutivo=".$_POST["Id_c"]." 
                    AND Centrocosto=".$_SESSION["Id_centro"];

        
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Cont_caja?b=".$_POST["Id_c"]);
    }
        

    public function guardar_detalle(){
        Conexion::conectar();

        $fecha=Conexion::ConvertirFecha($_POST["FechaRembolso"]);
		$Valordebito=$_POST["Valordebito"];

		if(stripos($_POST["Nit"],",")===false){
			$Nit=$_POST["Nit"];

		}else{
			$d=explode(",",$_POST["Nit"]);    
			$Nit=$d[0];
		}   

           $BuscaRegimen="SELECT Regimen from Ctb_Proveerdores
                            WHERE Nit='{$Nit}'

            ";
            $resBuscar=mysql_query($BuscaRegimen);
            $row=mysql_fetch_array($resBuscar);
            $Regimen=$row["Regimen"];
			
			/// saca los valores dependiendo tipo de regimen
           $BuscaDescuentos="SELECT * from Ctb_descuentos WHERE CodDesc='{$_POST["Cuentaocod"]}'
            ";
            $resBuscar=mysql_query($BuscaDescuentos);
            $row=mysql_fetch_array($resBuscar);
			
			$Base=0;
			$rtf=0;
			$rti=0;
			$iva=0;
			if($Regimen=='RC'){

				//regimen comun
				$Base=$row["BMRetefRC"];
				$rtf=$row["PorcentajeRetefRC"];

				if($Valordebito>$Base){
					$Vrtfrc=$Valordebito*$rtf;
					$Vrtfrcto=$Vrtfrc/100;

					$Vtotalrc=$Valordebito+$Vrtfrcto;
				}
				else
				{
					$Vrtfrcto=0;
					$Vtotalrc=0;
				}

			
			}elseif($Regimen='RS'){

			//regimen simplificado
			$Base=$row["BMRetefRS"];
			$rtf=$row["PorcentajeRetefRS"];
			$rti=$row["PorcentajeReteIcaRS"];
			$iva=$row["PorcentajeReteIvaRS"];


				if($Valordebito>$Base){
					$Vrtfrs=$Valordebito*$rtf;
					$Vrtfrsto=$Vrtfrs/100;

					$Vrtirs=$Valordebito*$rti;
					$Vrtirsto=$Vrtirs/100;

					$Vrivars=$Valordebito*$iva;
					$Vivarsto=$Vrivars/100;

					$Vtotalrs=$Valordebito+$Vrtfrsto+$Vrtirsto+$Vivarsto;
				}
				else
				{
					$Vrtfrsto=0;
					$Vrtirsto=0;
					$Vivarsto=0;
					$Vtotalrs=0;
				}
			
			
			}else
			{

			}
        

        $sql="INSERT INTO Ctb_cmdet(Id_cm,Fr,Ur,FechaRembolso,Norembolso,Nit,Cuentaocod,Valordebito,
            Basederetencion,Baseiva,Concepto,Cuiva,Curetencion,Consecutivo,Centrocosto)";
        $sql.="values(".$_POST["Id_c"].",now(),'".$_SESSION["Cedula"]."','".$fecha."',".$_POST["Norembolso"].",
            ".$Nit.",".$_POST["Cuentaocod"].",".$Valordebito.",
            ".$rtf.",".$iva.",'".$_POST["Concepto"]."',
            ".$_POST["Cuiva"].",".$_POST["Curetencion"].",".$_SESSION["Id_centro"].",
            ".$_SESSION["Id_centro"].")";

		
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Cont_caja?b=".$_POST["Id_c"]);
    }    

    public function eliminar_detalle($id,$Con){
        Conexion::conectar();
        $sql="DELETE FROM Ctb_cmdet where Id=".$id;
        mysql_query($sql);
        $dir="Location:".Conexion::ruta()."Cont_caja?b=".$Con;
        header($dir);
    }

    public function edit_detalle(){
        Conexion::conectar();
        
        
        $sql="UPDATE Ctb_cmdet set Fecha='".$_POST["Fecha"]."',Cod=".$_POST["Cod"].",Beneficiario=".$_POST["Beneficiario"].",ValorRecibo=".$_POST["ValorRecibo"].",ReteFuente=".$_POST["ReteFuente"].",
        VCRetequi=".$_POST["VCRetequi"].",ValorPagado=".$_POST["ValorPagado"].",
        Reembolsable=".$_POST["Reembolsable"].",FechaCM=".$_POST["FechaCM"].",
        DescConcPago=".$_POST["DescConcPago"].",ValorReciboAI=".$_POST["ValorReciboAl"].",
        ValorIVA=".$_POST["ValorIVA"].",Reteiva=".$_POST["Reteiva"].",
        PorcentajeReteIcaRS=".$_POST["PorcentajeReteIcaRS"].",Retequi=".$_POST["Retequi"].",
        ReteIca=".$_POST["ReteIca"].",Retequi=".$_POST["Retequi"]." where Id=".$_POST["Id"];

        mysql_query($sql);
        header("Location:".Conexion::ruta()."Cont_caja?b=".$_POST["Id_c"]);   

    }


    
}

?>
