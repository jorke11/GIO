<?php
class Alm_vehi extends Conexion{
    
    private $requi;
    private $detalle;
    private $personal;

    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->personal=array();
    }


    public function get_personal(){
        Conexion::conectar();
        $sql="SELECT Identificacion,Nombres
                FROM Rh_personal";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->personal[]='"'.$row["Identificacion"].','.$row["Nombres"].'"';
        }

        return $this->personal;
        
    }

     public function get_proyectos(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT No,Nombre from G_proyectos";
        $res=$msqli->query($sql);

        while($row=$res->fetch_array()){
            $this->proyecto[]='"'.$row["No"].'-'.$row["Nombre"].'"';
        }

        return $this->proyecto;
        $mysqli->close();
    }
    
    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 as nuevo  
                FROM Log_vehi 
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        if($row=mysql_fetch_array($res)){
    
            
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
        
                $query="INSERT INTO Log_vehi(Consecutivo,Fr,Ur,Sucursal,Centrocosto)
                values(".$nuevo.",now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",
                    ".$_SESSION["Id_centro"].");";
                mysql_query($query) or die(mysql_error());
                
                header("Location:".Conexion::ruta()."Alm_vehi?b2=".$nuevo);
            
        }

    }
    
    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT Vehi.Consecutivo,Sucursal.Nombre Sucursal,Centro.Nombre Centrocosto,Vehi.Proyecto,
                        Vehi.Placa,Vehi.Valor,Vehi.Marca,Vehi.Tipo,Vehi.Conductor,Vehi.M3,
                        Vehi.M3transporte,(SELECT Numeracion
                                            FROM G_listas
                                            WHERE Id_control=7
                                            AND Numeracion = Vehi.Modelo) Modelo
                        FROM 
                        Log_vehi Vehi
                        JOIN G_sucursales Sucursal ON Vehi.Sucursal=Sucursal.Id
                        JOIN G_centros_costo Centro ON Vehi.Centrocosto=Centro.Id
                        WHERE Vehi.Consecutivo=$con
                        AND Vehi.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_requi2($con){
        Conexion::conectar();
        $sql="SELECT Vehi.Consecutivo,Sucursal.Nombre Sucursal,Centro.Nombre Centrocosto,Vehi.Proyecto,
                        Vehi.Placa,Vehi.Valor,Vehi.Marca,Vehi.Tipo,Vehi.Conductor,Vehi.M3,
                        Vehi.M3transporte,(SELECT Numeracion
                                            FROM G_listas
                                            WHERE Id_control=7
                                            AND Numeracion = Vehi.Modelo) Modelo
                        FROM 
                        Log_vehi Vehi
                        JOIN G_sucursales Sucursal ON Vehi.Sucursal=Sucursal.Id
                        JOIN G_centros_costo Centro ON Vehi.Centrocosto=Centro.Id
                        WHERE Vehi.Placa=$con
                        AND Vehi.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    
    public function guardar_registro(){
        Conexion::conectar();
        $destino="";
        
        $tamano = $_FILES["foto"]['size'];
        $tipo = $_FILES["foto"]['type'];
        $foto = $_FILES["foto"]['name'];

        if ($foto != "") {
            $destino =  "images/fotos2/".$foto;

            if (copy($_FILES['foto']['tmp_name'],$destino)) {
                $estado = "Archivo subido: <b>".$foto."</b>";
            } else {
                $estado = "Error al subir el archivo";
            }
        }
            $BuscaPlaca="SELECT Placa,Consecutivo 
                            FROM Log_vehi
                            WHERE Placa='{$_POST["Placa"]}'

            ";
            $resBuscar=mysql_query($BuscaPlaca);
            $row=mysql_fetch_array($resBuscar);
            $placa=$row["Placa"];
            $Con=$row["Consecutivo"];

         if(stripos($_POST["Conductor"],",")===false){
                $Conductor=$_POST["Conductor"];
                
            }else{
                $d=explode(",",$_POST["Conductor"]);    
                $Conductor=$d[0];
            }   
        

        if(mysql_num_rows($resBuscar)>0){
                if($_POST["Placa"]==$placa and $_POST["Id"]==$Con){
                    $sql="UPDATE Log_vehi SET Marca='".$_POST["Marca"]."', Modelo='".$_POST["Modelo"]."',
                    Placa='".$_POST["Placa"]."',Tipo='".$_POST["Tipo"]."',Proyecto='".$_POST["proyecto"]."',
                    Valor=".$_POST["Valor"].",Conductor='".$Conductor."',M3='".$_POST["M3"]."',
                    M3transporte='".$_POST["M3transporte"]."',Foto='".$destino."'
                    WHERE Consecutivo=".$_POST["Id"]." 
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
                    mysql_query($sql);
                    header("Location:".Conexion::ruta()."Alm_vehi?b2=".$_POST["Id"]);    
                }else{
                header("Location:".Conexion::ruta()."Alm_vehi?b2=".$_POST["Id"]."&error=1");    
                }

        }else{

            $sql="UPDATE Log_vehi SET Marca='".$_POST["Marca"]."', Modelo='".$_POST["Modelo"]."',
            Placa='".$_POST["Placa"]."',Tipo='".$_POST["Tipo"]."',Proyecto='".$_POST["proyecto"]."',
            Valor=".$_POST["Valor"].",Conductor='".$_POST["Conductor"]."',M3='".$_POST["M3"]."',
            M3transporte='".$_POST["M3transporte"]."',Foto='".$destino."'
            WHERE Consecutivo=".$_POST["Id"]." 
            AND Centrocosto=".$_SESSION["Id_centro"];
        
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Alm_vehi?b2=".$_POST["Id"]);    
        }

        
    }
  
    
    public function guardar_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;

        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="INSERT INTO Alm_entdet(Id_ent,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt)";
        $sql.="values(".$_POST["Id_ent"].",now(),'".$_SESSION["usuario"]."','".$_POST["producto"]."',".$_POST["cantidad"].",
        ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.")";
        echo $sql;
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id_ent"]);
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_entdet where Id_ent=$id";
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

        $sql="UPDATE Alm_entdet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
        Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"];
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Alm_Equipos?b=".$_POST["Id_ent"]); 

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_entdet where Id=".$id;
        mysql_query($sql);
    }
    

}

?>
