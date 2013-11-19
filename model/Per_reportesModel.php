<?php
class C_menor extends Conexion{

    private $requi;
    private $detalle;
    private $cajas;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->cajas=array();
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

            $insertar="INSERT INTO Ctb_cmdet(Consecutivo,Id_cm,Fr,Ur,Fecha,Cod,Beneficiario,ValorRecibo,Retefuente,
                ReteIca,VCRetequi,ValorPagado,Reembolsable,FechaCM,DescConcPago,ValorReciboAI,
                ValorIVA,ReteIva,PorcentajeReteIcaRS,Retequi,Centrocosto)
            VALUES(".$nuevo.",".$row["Id_cm"].",".$row["Fr"].",".$_SESSION["Id_centro"].",'".$row["Fecha"]."',
                    ".$row["Cod"].",".$row["Beneficiario"].",".$row["ValorRecibo"].",".$row["Retefuente"].",
                    ".$row["ReteIca"].",".$row["VCRetequi"].",".$row["ValorPagado"].",".$row["Reembolsable"].",
                    '".$row["FechaCM"]."',".$row["DescConcPago"].",".$row["ValorReciboAI"].",
                    ".$row["ValorIVA"].",".$row["ReteIva"].",".$row["PorcentajeReteIcaRS"].",".$row["Retequi"].",
                    ".$_SESSION["Id_centro"].");

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

            $query="INSERT INTO Ctb_cm(Consecutivo,Fr,Ur,Estadodoc,Festadodoc,Sucursal,Centrocosto)
            values($nuevo,now(),'".$_SESSION["Cedula"]."','".$estado."',now(),".$_SESSION["Id_sucursal"].",
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
        $FechaPago=Conexion::ConvertirFecha($_POST["FechaPago"]);

        if(stripos($_POST["Responsable"],",")===false){
                $Responsable=$_POST["Responsable"];
                
        }else{
                $d=explode(",",$_POST["Responsable"]);    
                $Responsable=$d[0];
        }
        
        $sql="UPDATE Ctb_cm set Fecha='".$fecha."',Responsable='".$Responsable."',
                            DuenoCuenta='".$_POST["DuenoCuenta"]."',TotalLegalizado='".$_POST["TotalLegalizado"]."'
                            ,TotalAnticipos='".$_POST["TotalAnticipos"]."',TotalReembolso='".$_POST["TotalReembolso"]."',
                            EstadoAct='".$_POST["EstadoAct"]."',NuevoEstado='".$_POST["NuevoEstado"]."',
                            Firmado='".$_POST["Firmado"]."',DescripcionPago='".$_POST["DescripcionPago"]."',
                            Cancelado='".$_POST["Cancelado"]."',Cuatroxmil='".$_POST["Cuatroxmil"]."',
                            FechaPago='".$FechaPago."',Selec='".$_POST["Selec"]."',ususelec='".$_POST["ususelec"]."',
                            Clasegasto='".$_POST["Clasegasto"]."', Observaciones='".$_POST["Observaciones"]."'
                    WHERE Consecutivo=".$_POST["Id_c"]." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Cont_caja?b=".$_POST["Id_c"]);
    }
        

    public function guardar_detalle(){
        Conexion::conectar();

        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        $fechacm=Conexion::ConvertirFecha($_POST["FechaCM"]);

        $sql="INSERT INTO Ctb_cmdet(Id_cm,Fr,Ur,Fecha,Cod,Beneficiario,ValorRecibo,Retefuente,
            Reteica,VCRetequi,ValorPagado,Reembolsable,FechaCM,DescConcPago,ValorReciboAI,
            ValorIVA,Reteiva,PorcentajeReteicaRS,Retequi,Consecutivo,Centrocosto)";
        $sql.="values(".$_POST["Id_c"].",now(),'".$_SESSION["Cedula"]."','".$fecha."',".$_POST["Cod"].",
            ".$_POST["Beneficiario"].",".$_POST["ValorRecibo"].",".$_POST["ReteFuente"].",
            ".$_POST["Reteica"].",".$_POST["VCRetequi"].",".$_POST["Valorpagado"].",
            '".$_POST["Reembolsable"]."','".$fechacm."','".$_POST["DescConcPago"]."',
            ".$_POST["ValorReciboAl"].",".$_POST["ValorIVA"].",".$_POST["Reteiva"].",
            ".$_POST["PorcentajeReteIcaRS"].",".$_POST["Retequi"].",".$_SESSION["Id_centro"].",
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
