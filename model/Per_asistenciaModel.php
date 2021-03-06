<?php
class Requisicion extends Conexion{
    
    private $requi;
    private $detalle;
    private $persona;
    private $producto;
    private $estadodoc;
	private $Hora;
    
    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->persona=array();
        $this->producto=array();
        $this->estadodoc=array();
		$this->Hora=array();
    }

    public function get_EstadoDoc(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No,l.Id_control 
                FROM G_listas l
                JOIN G_controles c ON c.No=l.Id_control
                WHERE Id_control=3";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->estadodoc[]=$row;
        }

        return $this->estadodoc;
    }
	
	
	public function get_Hora(){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT c.Descripcion,l.Lista,No,l.Id_control 
                FROM G_listas l
                JOIN G_controles c ON c.No=l.Id_control
                WHERE Id_control=24";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->Hora[]=$row;
        }

        return $this->Hora;
    }

    
    public function nuevo_registro(){
        $nuevo;
        Conexion::conectar();
		$Factual=date('Y-m-d');  

		$sql4="SELECT Consecutivo from Rh_personal WHERE Fecha='".$Factual."' AND  Centrocosto=".$_SESSION["Id_centro"];
		echo $sql4;
		$res4=mysql_query($sql4);
		if(mysql_num_rows($res4) > 0){


					$sql="SELECT MAX(Consecutivo)+ 1 as nuevo 
								FROM Rh_asistencia 
								WHERE Centrocosto=".$_SESSION["Id_centro"];

					$res=mysql_query($sql);
					
					if($row=mysql_fetch_array($res)){
						$nuevo=$row["nuevo"];
						$estado=1;
						
						($nuevo==NULL)?$nuevo=1:$nuevo=$row["nuevo"];
						
						$query="INSERT INTO Rh_asistencia(Consecutivo,Fr,Ur,Sucursal,Centrocosto,Fecha)
							VALUES($nuevo,now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",
								".$_SESSION["Id_centro"].",'".$Factual."');";
							mysql_query($query);
						header("Location:".Conexion::ruta()."Per_asistencia?b=".$nuevo);
					}
        }else{
            header("Location:".Conexion::ruta()."Per_asistencia?d=".$Factual."&error=4");
        }
    }
    
    public function get_persona_sol(){
        Conexion::conectar();
        $sql="SELECT nombre,apellido,Cedula 
                FROM G_personaSolicitud";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->persona[]='"'.$row["nombre"].' '.$row["apellido"].','.$row["Cedula"].'"';
        }    
        return $this->persona;    
    }

    public function get_productos(){
        Conexion::conectar();
        $sql="SELECT Descripcion,Codigo from G_producto";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->producto[]='"'.$row["Codigo"].','.$row["Descripcion"].'"';
        }    
        return $this->producto;    
    }
//consulta cuando viene de nuevo guardar y editar
    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT Centros.Nombre AS Centro,Asis.Consecutivo,Asis.Id,Asis.Fr,Asis.Fecha,Asis.Horai,Asis.Horaf,
        Sucursal.Nombre as Sucursal
            FROM Rh_asistencia  AS Asis
            JOIN G_centros_costo AS Centros ON Asis.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON Asis.Sucursal=Sucursal.Id
            WHERE Consecutivo=$con AND Centrocosto={$_SESSION["Id_centro"]}
        ";
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

//consulta cuando viene de buscar
	    public function get_requib($con){
        Conexion::conectar();

        $sql="SELECT Centros.Nombre AS Centro,Asis.Consecutivo,Asis.Id,Asis.Fr,Asis.Fecha,Asis.Horai,Asis.Horaf,
        Sucursal.Nombre as Sucursal
            FROM Rh_asistencia  AS Asis
            JOIN G_centros_costo AS Centros ON Asis.Centrocosto=Centros.Id
            JOIN G_sucursales AS Sucursal ON Asis.Sucursal=Sucursal.Id
            WHERE Fecha='$con' AND Centrocosto={$_SESSION["Id_centro"]}
        ";
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function guardar_registro(){
        
        Conexion::conectar();
        

        if(stripos($_POST["Ps"], ",")===false){
            $personal=$_POST["Ps"];
            $sqlPersonal="WHERE Cedula='{$personal}'";   
        }else{
            $d=explode(",",$_POST["Ps"]);    
            $personal=$d[1];
            $sqlPersonal="WHERE Cedula='{$personal}'";
        }

        if($_POST["Ps"]=="No existe"){
            $sqlPersonal="";
            $personal="";
        }
        
   

        $sql2="SELECT Identificacion from Rh_personal WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res2=mysql_query($sql2);

        while($row2=mysql_fetch_array($res2))
		{
			
			$Identificacion=trim($row2["Identificacion"]);

			$sql="INSERT INTO Rh_asistenciadet(id_asi,Fr,Ur,Persona,Fecha,Horai,Horaf,Centrocosto)";
            $sql.="values(".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$Identificacion."',
                '".$_POST["Fecha"]."','".$_POST["Horai"]."','".$_POST["Horaf"]."',".$_SESSION["Id_centro"].")";
            mysql_query($sql);

		}
            $sql1="UPDATE Rh_asistencia SET 
            Horai='".$_POST["Horai"]."',Horaf='".$_POST["Horaf"]."' 
            WHERE Consecutivo=".$_POST["Id"]." AND Centrocosto=".$_SESSION["Id_centro"];
            mysql_query($sql1);

        header("Location:".Conexion::ruta()."Per_asistencia?b=".$_POST["Id"]);


    }
    
    public function guardar_detalle(){
        Conexion::conectar();
        
        $neto=(FLOAT)$_POST["Vu"]*(FLOAT)$_POST["Cantidad"];
        $iva=(FLOAT)$_POST["Imp"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        if(stripos($_POST["Producto"], ",")===false){
            $producto=$_POST["Producto"];
            
        }else{
            $d=explode(",",$_POST["Producto"]);    
            $producto=$d[0];
        }

        $sqlBusqueda="SELECT * 
                        FROM G_producto
                        WHERE Codigo='{$producto}'
        ";
        $resBusqueda=mysql_query($sqlBusqueda);


        if(mysql_num_rows($resBusqueda) > 0){
            $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
            $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["Cedula"]."','".$producto."',
                ".$_POST["Cantidad"].",".$_POST["Vu"].",".$_POST["Imp"].",".$neto.",".$total.",".$_SESSION["Id_centro"].")";
        
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);
        }else{
            header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]."&error=2");
        }

        
    }

     public function copiar($id){
        Conexion::conectar();
        $sql="SELECT Id_asi, Persona,Fecha,Horai,Horaf 
                    FROM Rh_asistenciadet 
                    WHERE 
                    Id=".$id;
        
        $res=mysql_query($sql);

		echo $sql;
       if($row=mysql_fetch_assoc($res)){
        $Persona=trim($row["Persona"]);
		echo $Persona;
        $sqlBusqueda="SELECT * 
                        FROM Rh_asistenciadet
                        WHERE Persona='{$Persona}'
        ";
		echo $sqlBusqueda;
        $resBusqueda=mysql_query($sqlBusqueda);
            if(mysql_num_rows($resBusqueda)){
                $query="INSERT INTO Rh_asistenciadet(Id_asi,Fr,Ur,Persona,Fecha,Horai,Horaf,Centrocosto) VALUES(".$row['Id_asi'].",now(),".$_SESSION["Cedula"].",'".$row["Persona"]."',
                    '".$row["Fecha"]."','".$row["Horai"]."','".$row["Horaf"]."',".$_SESSION["Id_centro"].")";
                
                mysql_query($query);
                header("Location:".Conexion::ruta()."Per_asistencia?b=".$row["Id_asi"]);   
            }else{
                header("Location:".Conexion::ruta()."Per_asistencia?b=".$row["Id_asis"]."&error=2");   
            }

             
        }
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Rh_asistenciadet where id_asi=$id and Centrocosto=".$_SESSION["Id_centro"];
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }

    public function get_detalle_por_id($id_ent,$id_requi){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet where Id_Requisicion=$id_requi and Id=$id_ent";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }


    public function edit_detalle(){
        Conexion::conectar();
        
		$Persona=$_POST["Persona"];
            
        $sqlBusqueda="SELECT * 
                        FROM Rh_asistenciadet
                        WHERE Persona='{$Persona}'
                        
        ";

        $resBusqueda=mysql_query($sqlBusqueda);
        if(mysql_num_rows($resBusqueda)){
            $sql="UPDATE Rh_asistenciadet set Persona='".$Persona."',Fecha='".$_POST["Fecha"]."',
                            Horai='".$_POST["Horai"]."',Horaf='".$_POST["Horaf"]."'
                    WHERE Id=".$_POST["fid"];
            
            mysql_query($sql);
            header("Location:".Conexion::ruta()."Per_asistencia?b=".$_POST["Id_asis"]);   
        }else{
            header("Location:".Conexion::ruta()."Per_asistencia?b=".$_POST["Id_asis"]."&error=2");
        }

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Rh_asistenciadet where Id=".$id;
        mysql_query($sql);
    }

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_requi set Estadodoc=2 where Consecutivo=$id";
        mysql_query($sql);
    }
    
    public function get_tipo(){
        Conexion::conectar();
        $sql="SELECT * 
        from G_controles as c
        JOIN G_listas as l ON c.No=l.Id_control
        where Id_control=1
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tipo[]=$row;
        }
        return $this->tipo;
    }
    
}

?>
