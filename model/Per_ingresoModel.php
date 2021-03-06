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
    private $cargo;
	private $Tipocontrato;
	private $Centrotrabajo;
	private $Periodopago;
	private $pagos;
   
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
        $this->cargo=array();
		$this->Tipocontrato=array();
		$this->Centrotrabajo=array();
		$this->Periodopago=array();
		$this->pagos=array();
    }
     
    public function get_cargo(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No 
        FROM G_listas l
        JOIN G_controles c ON c.No=l.Id_control
        WHERE Id_control=9";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->cargo[]=$row;
        }

        return $this->cargo;
    }

	public function get_Tipocontrato(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No 
        FROM G_listas l
        JOIN G_controles c ON c.No=l.Id_control
        WHERE Id_control=21";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->Tipocontrato[]=$row;
        }

        return $this->Tipocontrato;
    }

	public function get_Centrotrabajo(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No 
        FROM G_listas l
        JOIN G_controles c ON c.No=l.Id_control
        WHERE Id_control=22";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->Centrotrabajo[]=$row;
        }

        return $this->Centrotrabajo;
    }

	public function get_Periodopago(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No 
        FROM G_listas l
        JOIN G_controles c ON c.No=l.Id_control
        WHERE Id_control=23";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->Periodopago[]=$row;
        }

        return $this->Periodopago;
    }


	public function get_tipopagos(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=18
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->pagos[]=$row;
        }
        return $this->pagos;
    }

    public function guardar_pagos(){
        Conexion::conectar();

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_pagosadd
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);

        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

        $sql="INSERT INTO Rh_pagosadd(Consecutivo,Id_pag,Fr,Ur,Identificacion,Tipo,Valor,
                                Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."'
        ,".$_POST["Tipo"].",".$_POST["Valor"].",
        ".$_SESSION["Id_centro"].")";

        mysql_query($sql);
		echo "<script>location.href='".Conexion::ruta()."Per_ingreso?b2=".$_POST["Id"]."'</script>";
    }

    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 nuevo  
                FROM Rh_personal 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            
            
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
            
            $query="INSERT INTO Rh_personal(Consecutivo,Fr,Ur,Sucursal,Centrocosto)
            values(".$nuevo.",now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",".$_SESSION["Id_centro"].");";
            mysql_query($query);
        
            echo "<script>location.href='".Conexion::ruta()."Per_ingreso?b2=".$nuevo."'</script>";
            
            }

    }
    
    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT Per.Consecutivo,Per.Fr,Sucursal.Nombre Sucursal,Centro.Nombre Centro_costo,
                            Per.Identificacion,Per.Nombres,Per.Apellidos,Per.Sexo,Per.Fexpedicion,
                            Per.Fnacimiento,Per.Direccion,Per.Telefono1,Per.Telefono2,Per.Email,
                            Per.Carnet,Per.Estadocivil,Per.Centrotrabajo,Per.Tipocontrato,Per.Cargo,
                            Per.Salario,Per.Periodopago,Per.Fingreso,Per.Retirado,Per.Fretiro,Per.Motivoret,Per.Estadoact,
                            Per.EPS,Per.AFP,Per.ARL,Per.Gruporh,Per.Nocuenta,Per.Banco,Per.Personacontacto,
                            Per.Telcontacto,Per.Observaciones

                    FROM Rh_personal Per
                    JOIN G_sucursales Sucursal ON Per.Sucursal=Sucursal.Id
                    JOIN G_centros_costo Centro ON Per.Centrocosto=Centro.Id
                    WHERE Per.Consecutivo='$con' 
                    AND Per.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_requi2($con){
        Conexion::conectar();
        $sql="SELECT Per.Consecutivo,Per.Fr,Sucursal.Nombre Sucursal,Centro.Nombre Centro_costo,
                            Per.Identificacion,Per.Nombres,Per.Apellidos,Per.Sexo,Per.Fexpedicion,
                            Per.Fnacimiento,Per.Direccion,Per.Telefono1,Per.Telefono2,Per.Email,
                            Per.Carnet,Per.Estadocivil,Per.Centrotrabajo,Per.Tipocontrato,Per.Cargo,
                            Per.Salario,Per.Periodopago,Per.Fingreso,Per.Retirado,Per.Fretiro,Per.Motivoret,Per.Estadoact,
                            Per.EPS,Per.AFP,Per.ARL,Per.Gruporh,Per.Nocuenta,Per.Banco,Per.Personacontacto,
                            Per.Telcontacto,Per.Observaciones

                    FROM Rh_personal Per
                    JOIN G_sucursales Sucursal ON Per.Sucursal=Sucursal.Id
                    JOIN G_centros_costo Centro ON Per.Centrocosto=Centro.Id
                    WHERE Per.Identificacion='$con' 
                    AND Per.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
  
    
    public function guardar_personal(){
        Conexion::conectar();

        $fexpedicion=Conexion::ConvertirFecha($_POST["Fexpedicion"]);
        $fnacimiento=Conexion::ConvertirFecha($_POST["Fnacimiento"]);
        $fingreso=Conexion::ConvertirFecha($_POST["Fingreso"]);
        $fretiro=Conexion::ConvertirFecha($_POST["Fretiro"]);

        $BuscaUltimo="SELECT MAX(Consecutivo) as Ultimo 
                        FROM Rh_pagosadd
                        WHERE Tipo=3 AND Identificacion='{$_POST["Identificacion"]}'

                ";
        $resUltimo=mysql_query($BuscaUltimo);
        $Ultimo=mysql_fetch_array($resUltimo);
        $Valoru=$Ultimo["Ultimo"];

		$BuscaSalario="SELECT Valor
                        FROM Rh_pagosadd
                        WHERE Consecutivo='".$Valoru."' AND Tipo=3 AND Identificacion='{$_POST["Identificacion"]}'

                ";
        $resSalario=mysql_query($BuscaSalario);
        $Salario=mysql_fetch_array($resSalario);
        $Valors=$Salario["Valor"];


        $BuscaPersona="SELECT Identificacion 
                        FROM Rh_personal
                        WHERE Identificacion='{$_POST["Identificacion"]}'

                ";
        $resBusqueda=mysql_query($BuscaPersona);
        $var=mysql_fetch_array($resBusqueda);
        $Con=$var["Consecutivo"];
        $ide=$var["Identificacion"];
         if(mysql_num_rows($resBusqueda) > 1){
            if($_POST["Identificacion"]==$ide and $_POST["Id"]==$Con){
                $sql="UPDATE Rh_personal set Identificacion='".$_POST["Identificacion"]."'
                    ,Nombres='".$_POST["Nombres"]."',Apellidos='".$_POST["Apellidos"]."',Sexo='".$_POST["Sexo"]."',
                    Direccion='".$_POST["Direccion"]."',Telefono1='".$_POST["Telefono1"]."',
                    Fexpedicion='".$fexpedicion."',Fnacimiento='".$fnacimiento."',Fretiro='".$fretiro."',
                    Fingreso='".$fingreso."',Telefono2='".$_POST["Telefono2"]."',Email='".$_POST["Email"]."',
                    Carnet='".$_POST["Carnet"]."',Estadocivil='".$_POST["Estadocivil"]."',
                    Centrotrabajo='".$_POST["Centrotrabajo"]."',
                    Tipocontrato='".$_POST["Tipocontrato"]."',Cargo='".$_POST["Cargo"]."',
                    Salario=".$Valors.",Periodopago=".$_POST["Periodopago"].",Motivoret='".$_POST["Motivoret"]."',Estadoact='".$_POST["Estadoact"]."',
                    EPS='".$_POST["EPS"]."',AFP='".$_POST["AFP"]."',ARL='".$_POST["ARL"]."',Gruporh='".$_POST["Gruporh"]."',
                    Nocuenta='".$_POST["Nocuenta"]."',Banco='".$_POST["Banco"]."',
                    Personacontacto='".$_POST["Personacontacto"]."',Telcontacto='".$_POST["Telcontacto"]."',
                    Observaciones='".$_POST["Observaciones"]."' WHERE Consecutivo=".$_POST["Id"]." and 
                    Centrocosto=".$_SESSION["Id_centro"];

                
            
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_ingreso?b2=".$_POST["Id"]."'</script>";
            }else{
                // header("Location:".Conexion::ruta()."Per_ingreso?b=".$_POST["Id"]."&error=1");
            echo "<script>location.href='".Conexion::ruta()."Per_ingreso?b2=".$_POST["Id"]."&error=1'</script>";    
            }
            
         }   else{

                $sql="UPDATE Rh_personal set Identificacion='".$_POST["Identificacion"]."'
                    ,Nombres='".$_POST["Nombres"]."',Apellidos='".$_POST["Apellidos"]."',Sexo='".$_POST["Sexo"]."',
                    Direccion='".$_POST["Direccion"]."',Telefono1='".$_POST["Telefono1"]."',
                    Fexpedicion='".$fexpedicion."',Fnacimiento='".$fnacimiento."',Fretiro='".$fretiro."',
                    Fingreso='".$fingreso."',Telefono2='".$_POST["Telefono2"]."',Email='".$_POST["Email"]."',
                    Carnet='".$_POST["Carnet"]."',Estadocivil='".$_POST["Estadocivil"]."',
                    Centrotrabajo='".$_POST["Centrotrabajo"]."',
                    Tipocontrato='".$_POST["Tipocontrato"]."',Cargo='".$_POST["Cargo"]."',
                    Salario=".$Valors.",Periodopago=".$_POST["Periodopago"].",Motivoret='".$_POST["Motivoret"]."',Estadoact='".$_POST["Estadoact"]."',
                    EPS='".$_POST["EPS"]."',AFP='".$_POST["AFP"]."',ARL='".$_POST["ARL"]."',Gruporh='".$_POST["Gruporh"]."',
                    Nocuenta='".$_POST["Nocuenta"]."',Banco='".$_POST["Banco"]."',
                    Personacontacto='".$_POST["Personacontacto"]."',Telcontacto='".$_POST["Telcontacto"]."',
                    Observaciones='".$_POST["Observaciones"]."' WHERE Consecutivo=".$_POST["Id"]." and 
                    Centrocosto=".$_SESSION["Id_centro"];
					//echo 'sql1'.$sql;
                
  
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_ingreso?b2=".$_POST["Id"]."'</script>";
                // header("Location:".Conexion::ruta()."Per_ingreso?b=".$_POST["Id"]);    
            }
    }
    
    
    public function get_productos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT Codigo,Descripcion from G_producto";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->producto[]=$row;
        }
        return $this->producto;
    }
	



}

?>

