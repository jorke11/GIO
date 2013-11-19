<?php
class Apu extends Conexion{
    
    private $requi;
    private $detalle;
    private $persona;
    private $producto;
    private $materiales;
    private $mantenimientos;
    private $equipos;
    private $matdet;
    private $mantedet;
    private $equidet;

    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->persona=array();
        $this->producto=array();
        $this->materiales=array();
        $this->mantenimientos=array();
        $this->transportes=array();
        $this->equipos=array();
        $this->matdet=array();
        $this->mantedet=array();
        $this->equidet=array();
        $this->transdet=array();
        
    }

    
    public function nuevo_registro(){
        
        Conexion::conectar();
        $sql="SELECT MAX(Consecutivo)+ 1 AS nuevo 
                FROM Ope_pre_apu
                WHERE Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);
        
        if($row=mysql_fetch_array($res)){
            $estado=1;
            $nuevo=($row["nuevo"]==NULL)?1:$row["nuevo"];
                
                $query="INSERT INTO Ope_pre_apu(Consecutivo,Fr,Ur,Sucursal,Centrocosto)
                values($nuevo,now(),'".$_SESSION["Cedula"]."',".$_SESSION["Id_sucursal"].",
                    ".$_SESSION["Id_centro"].");";

                mysql_query($query);
                echo "<script>location.href='".Conexion::ruta()."Ope_apu?b=".$nuevo."'</script>";
                // header("Location:".Conexion::ruta()."Ope_apu?b=".$nuevo);
        }
    }

    public function get_materiales($con){
        Conexion::conectar();
        $sql="SELECT * 
                FROM Ope_pre_matapu
                WHERE Id_apu=$con
                AND Centrocosto=".$_SESSION["Id_centro"]."

        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->materiales[]=$row;
        }
        return $this->materiales;
    }

    public function guardar_materiales(){
        Conexion::conectar();

        $sqlBuscar="SELECT Valor
                    FROM Ope_pre_apu apu
                    WHERE Consecutivo=".$_POST["Id"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $resBusqueda=mysql_query($sqlBuscar);
        $valor=mysql_fetch_array($resBusqueda);

        $valort=$_POST["Cantidad"]*$_POST["Vu"];
        $total=($valort) + $valor["Valor"];

        $actualizar="UPDATE Ope_pre_apu apu SET Valor = ".$total."
                        WHERE Consecutivo=".$_POST["Id"]."
                        AND Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($actualizar);

        $sql="INSERT INTO Ope_pre_matapu (Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort) 
                VALUES(".$_POST["Id"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                    '".$_POST["Apu"]."','".$_POST["Codigo"]."','".$_POST["Cantidad"]."',
                    '".$_POST["Vu"]."',".$valort.");

        ";
        
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
        // header("Location:".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
        
    }

     public function edit_materiales(){
        Conexion::conectar();
        $total=$_POST["Cantidad"]*$_POST["Vu"];
        $update="UPDATE Ope_pre_matapu SET Apu=".$_POST["Apu"].",Codigo=".$_POST["Codigo"].",
                                Cantidad=".$_POST["Cantidad"].", Vu=".$_POST["Vu"].",Valort=$total 
                                WHERE Id=".$_POST["Id_mat"];

        
        mysql_query($update);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
    }

    public function crear_materiales(){
        Conexion::ruta();
        
        $sql="INSERT INTO Ope_pre_mat(Fr,Ur,Codigo,Um,Descripcion)
                VALUES(now(),".$_SESSION["Cedula"].",'".$_POST["Codigo"]."','".$_POST["Um"]."','".$_POST["Descripcion"]."')";
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"]."'</script>";

    }

    public function get_materialesdet(){
        Conexion::conectar();
        $sql="SELECT Codigo,Um,Descripcion 
                FROM Ope_pre_mat";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->matdet[]='"'.$row["Codigo"].','.$row["Um"].','.$row["Descripcion"].'"';
        }    
        return $this->matdet;    
    }



    public function get_mantenimientos($con){
        Conexion::ruta();
        $sql="SELECT * 
                FROM Ope_pre_moapu
                WHERE Id_apu=$con
                AND Centrocosto=".$_SESSION["Id_centro"]."
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->mantenimientos[]=$row;
        }
        return $this->mantenimientos;
    }

    public function guardar_mantenimientos(){
        Conexion::conectar();
        
        $sqlBuscar="SELECT Valor
                    FROM Ope_pre_apu apu
                    WHERE Consecutivo=".$_POST["Id"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $resBusqueda=mysql_query($sqlBuscar);
        $valor=mysql_fetch_array($resBusqueda);

        $valort=$_POST["Cantidad"]*$_POST["Vu"];
        $total=($valort) + $valor["Valor"];

        $actualizar="UPDATE Ope_pre_apu apu SET Valor = ".$total."
                        WHERE Consecutivo=".$_POST["Id"]."
                        AND Centrocosto=".$_SESSION["Id_centro"];

        mysql_query($actualizar);


        $sql="INSERT INTO Ope_pre_moapu (Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort) 
                VALUES(".$_POST["Id"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                    '".$_POST["Apu"]."','".$_POST["Codigo"]."','".$_POST["Cantidad"]."',
                    '".$_POST["Vu"]."',".$valort.");

        ";
        
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
        // header("Location:".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
    }

     public function edit_mantenimientos(){
        Conexion::conectar();
        $total=$_POST["Cantidad"]*$_POST["Vu"];
        $update="UPDATE Ope_pre_moapu SET Apu=".$_POST["Apu"].",Codigo=".$_POST["Codigo"].",
                                Cantidad=".$_POST["Cantidad"].", Vu=".$_POST["Vu"].",Valort=$total 
                                WHERE Id=".$_POST["Id_mante"];

        
        mysql_query($update);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
    }

    public function crear_mante(){
        Conexion::ruta();
        
        $sql="INSERT INTO Ope_pre_mo(Fr,Ur,Codigo,Um,Descripcion)
                VALUES(now(),".$_SESSION["Cedula"].",'".$_POST["Codigo"]."','".$_POST["Um"]."','".$_POST["Descripcion"]."')";
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"]."'</script>";

    }

    public function get_mantedet(){
        Conexion::conectar();
        $sql="SELECT Codigo,Um,Descripcion 
                FROM Ope_pre_mo";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->mantedet[]='"'.$row["Codigo"].','.$row["Um"].','.$row["Descripcion"].'"';
        }    
        return $this->mantedet;    
    }


    public function get_transportes($con){
        Conexion::ruta();
        $sql="SELECT * 
                FROM Ope_pre_trasapu
                WHERE Id_apu=$con
                AND Centrocosto=".$_SESSION["Id_centro"]."

        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->transportes[]=$row;
        }
        return $this->transportes;
    }

    public function guardar_transportes(){
        Conexion::conectar();
        
        $sqlBuscar="SELECT Valor
                    FROM Ope_pre_apu apu
                    WHERE Consecutivo=".$_POST["Id"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $resBusqueda=mysql_query($sqlBuscar);
        $valor=mysql_fetch_array($resBusqueda);

        $valort=$_POST["Cantidad"]*$_POST["Vu"];
        $total=($valort) + $valor["Valor"];

        $actualizar="UPDATE Ope_pre_apu apu SET Valor = ".$total."
                        WHERE Consecutivo=".$_POST["Id"]."
                        AND Centrocosto=".$_SESSION["Id_centro"];
        mysql_query($actualizar);

        $sql="INSERT INTO Ope_pre_trasapu (Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort) 
                VALUES(".$_POST["Id"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                    '".$_POST["Apu"]."','".$_POST["Codigo"]."','".$_POST["Cantidad"]."',
                    '".$_POST["Vu"]."',".$valort.");

        ";
        
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
    }

    public function edit_transporte(){
        Conexion::conectar();
        $total=$_POST["Cantidad"]*$_POST["Vu"];
        $update="UPDATE Ope_pre_trasapu SET Apu=".$_POST["Apu"].",Codigo=".$_POST["Codigo"].",
                                Cantidad=".$_POST["Cantidad"].", Vu=".$_POST["Vu"].",Valort=$total 
                                WHERE Id=".$_POST["Id_trans"];

        
        mysql_query($update);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
    }

    public function crear_transporte(){
        Conexion::ruta();
        
        $sql="INSERT INTO Ope_pre_tras(Fr,Ur,Codigo,Um,Descripcion)
                VALUES(now(),".$_SESSION["Cedula"].",'".$_POST["Codigo"]."','".$_POST["Um"]."','".$_POST["Descripcion"]."')";
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"]."'</script>";

    }

    public function get_transdet(){
        Conexion::conectar();
        $sql="SELECT Codigo,Um,Descripcion 
                FROM Ope_pre_tras";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->transdet[]='"'.$row["Codigo"].','.$row["Um"].','.$row["Descripcion"].'"';
        }    
        return $this->transdet; 
    }

    public function get_equipos($con){
        Conexion::ruta();
        $sql="SELECT * 
                FROM Ope_pre_equiapu
                WHERE Id_apu=$con
                AND Centrocosto=".$_SESSION["Id_centro"]."

        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->equipos[]=$row;
        }
        return $this->equipos;
    }

    public function guardar_equipos(){
        Conexion::conectar();
        
        $sqlBuscar="SELECT Valor
                    FROM Ope_pre_apu apu
                    WHERE Consecutivo=".$_POST["Id"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        $resBusqueda=mysql_query($sqlBuscar);
        $valor=mysql_fetch_array($resBusqueda);

        $valort=$_POST["Cantidad"]*$_POST["Vu"];

        $total=($valort) + $valor["Valor"];

        $actualizar="UPDATE Ope_pre_apu apu SET Valor = ".$total."
                        WHERE Consecutivo=".$_POST["Id"]."
                        AND Centrocosto=".$_SESSION["Id_centro"];

        mysql_query($actualizar);

        $sql="INSERT INTO Ope_pre_equiapu (Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort) 
                VALUES(".$_POST["Id"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                    '".$_POST["Apu"]."','".$_POST["Codigo"]."','".$_POST["Cantidad"]."',
                    '".$_POST["Vu"]."',".$valort.");

        ";
        
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
        // header("Location:".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]);
    }

    public function edit_equipos(){
        Conexion::conectar();
        $total=$_POST["Cantidad"]*$_POST["Vu"];
        echo $update="UPDATE Ope_pre_equiapu SET Apu=".$_POST["Apu"].",Codigo=".$_POST["Codigo"].",
                                Cantidad=".$_POST["Cantidad"].", Vu=".$_POST["Vu"].",Valort=$total 
                                WHERE Id=".$_POST["Id_equi"];

        
        mysql_query($update);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Id"]."&tipo=".$_POST["tipo"]."'</script>";
    }

    public function crear_equipos(){
        Conexion::ruta();
        
        $sql="INSERT INTO Ope_pre_equi(Fr,Ur,Codigo,Um,Descripcion)
                VALUES(now(),".$_SESSION["Cedula"].",'".$_POST["Codigo"]."','".$_POST["Um"]."','".$_POST["Descripcion"]."')";
        mysql_query($sql);
        echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$_POST["Consecutivo"]."&tipo=".$_POST["tipo"]."'</script>";

    }

    public function get_equipodet(){
        Conexion::conectar();
        $sql="SELECT Codigo,Um,Descripcion 
                FROM Ope_pre_equi";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->equidet[]='"'.$row["Codigo"].','.$row["Um"].','.$row["Descripcion"].'"';
        }    
        return $this->equidet; 
    }

     public function elimina_registro($id,$con,$tipo){
        Conexion::conectar();
        switch ($tipo) {
            case 'Materiales':
                $sqlBuscar="SELECT Valor
                            FROM Ope_pre_apu
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resBusqueda=mysql_query($sqlBuscar);
                $valorM=mysql_fetch_array($resBusqueda);
                
                $sqlActual="SELECT Valort
                            FROM Ope_pre_matapu
                            WHERE Id=$id
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resActual=mysql_query($sqlActual);
                $valorA=mysql_fetch_array($resActual);

                $total=$valorM["Valor"]-$valorA["Valort"];

                $update="UPDATE Ope_pre_apu SET Valor=$total
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"]."
                ";

                mysql_query($update);

                $sql="DELETE from Ope_pre_matapu WHERE Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo."'</script>";
                // $dir="Location:".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo;
                // header($dir);         
                break;
            case 'Mantenimientos':
                $sqlBuscar="SELECT Valor
                            FROM Ope_pre_apu
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resBusqueda=mysql_query($sqlBuscar);
                $valorM=mysql_fetch_array($resBusqueda);

                $sqlActual="SELECT Valort
                            FROM Ope_pre_moapu
                            WHERE Id=$id
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resActual=mysql_query($sqlActual);
                $valorA=mysql_fetch_array($resActual);

                $total=$valorM["Valor"]-$valorA["Valort"];

                $update="UPDATE Ope_pre_apu SET Valor=$total
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"]."
                ";

                mysql_query($update);

                $sql="DELETE from Ope_pre_moapu WHERE Id=$id";
                mysql_query($sql);
                // $dir="Location:".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo;
                echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo."'</script>";
                // header($dir);         
                break;
            case 'Transportes':

                $sqlBuscar="SELECT Valor
                            FROM Ope_pre_apu
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resBusqueda=mysql_query($sqlBuscar);
                $valorM=mysql_fetch_array($resBusqueda);

                $sqlActual="SELECT Valort
                            FROM Ope_pre_trasapu
                            WHERE Id=$id
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resActual=mysql_query($sqlActual);
                $valorA=mysql_fetch_array($resActual);

                $total=$valorM["Valor"]-$valorA["Valort"];

                $update="UPDATE Ope_pre_apu SET Valor=$total
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"]."
                ";

                mysql_query($update);


                $sql="DELETE from Ope_pre_trasapu WHERE Id=$id";
                mysql_query($sql);
                // $dir="Location:".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo;
                echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo."'</script>";
                // header($dir); 
            case 'Equipos':
                $sqlBuscar="SELECT Valor
                            FROM Ope_pre_apu
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resBusqueda=mysql_query($sqlBuscar);
                $valorM=mysql_fetch_array($resBusqueda);

                $sqlActual="SELECT Valort
                            FROM Ope_pre_equiapu
                            WHERE Id=$id
                            AND Centrocosto=".$_SESSION["Id_centro"];

                $resActual=mysql_query($sqlActual);
                $valorA=mysql_fetch_array($resActual);

                $total=$valorM["Valor"]-$valorA["Valort"];

                $update="UPDATE Ope_pre_apu SET Valor=$total
                            WHERE Consecutivo=$con
                            AND Centrocosto=".$_SESSION["Id_centro"]."
                ";

                mysql_query($update);


                $sql="DELETE from Ope_pre_equiapu WHERE Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo."'</script>";
                // $dir="Location:".Conexion::ruta()."Ope_apu?Con=".$con."&tipo=".$tipo;
                // header($dir);
            default:
                # code...
                break;
        }
        
    }



    
    public function get_persona_sol(){
        Conexion::conectar();
        $sql="SELECT nombre,apellido from G_personaSolicitud";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->persona[]='"'.$row["nombre"].'-'.$row["apellido"].'"';
        }    
        return $this->persona;    
    }

    public function get_productos(){
        Conexion::conectar();
        $sql="SELECT Descripcion from G_producto";
        $res=mysql_query($sql);

        while($row=mysql_fetch_array($res)){
            $this->producto[]='"'.$row["Descripcion"].'"';
        }    
        return $this->producto;    
    }

    public function get_requi($con){
        Conexion::conectar();

        $sql="SELECT apu.Id,apu.Consecutivo,Sucursal.nombre Sucursal,Centro.nombre Centrocosto,apu.Acuerdo,apu.Apu,apu.Um,
                            apu.Descripcion,apu.Cantidad,apu.Valor
                        FROM Ope_pre_apu apu
                        JOIN G_sucursales Sucursal ON apu.Sucursal=Sucursal.Id
                        JOIN G_centros_costo Centro ON apu.Centrocosto=Centro.Id
                        WHERE Consecutivo=$con 
                        AND Centrocosto=".$_SESSION["Id_centro"];

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }
    
    public function guardar_registro(){
        Conexion::conectar();
        
        $sql="UPDATE Ope_pre_apu set 
                        Acuerdo='".$_POST["Acuerdo"]."',Apu='".$_POST["Apu"]."',Um='".$_POST["Um"]."',
                        Descripcion='{$_POST["Descripcion"]}',Cantidad='".$_POST["Cantidad"]."'
                        WHERE Consecutivo=".$_POST["Id"]." 
                        AND Centrocosto=".$_SESSION["Id_centro"];

        mysql_query($sql);

        echo "<script>location.href='".Conexion::ruta()."Ope_apu?b=".$_POST["Id"]."'</script>";
        // header("Location:".Conexion::ruta()."Ope_apu?b=".$_POST["Id"]);
    }

     public function copiar_registro(){
        Conexion::conectar();
        
        switch ($_GET["tipo"]) {
            case 'Materiales':
                $sql="SELECT * 
                        FROM Ope_pre_matapu
                        WHERE Id=".$_GET["Cop"]."
                ";
                $res=mysql_query($sql);
                
                if($row=mysql_fetch_array($res)){
                    $insertar="INSERT INTO Ope_pre_matapu(Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort)
                                VALUES(".$row["Id_apu"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                                    ".$row["Apu"].",'".$row["Codigo"]."',".$row["Cantidad"].",".$row["Vu"].",
                                    ".$row["Valort"].")";
                    mysql_query($insertar);

                    $sql="SELECT Valor 
                            FROM Ope_pre_apu 
                            WHERE Consecutivo=".$row["Id_apu"]." 
                            AND Centrocosto=".$_SESSION["Id_centro"];
                    $resSQL=mysql_query($sql);
                    $valor=mysql_fetch_array($resSQL);

                    $total=$valor["Valor"] + $row["Valort"];
                    $update="UPDATE Ope_pre_apu set Valor=$total 
                                WHERE Consecutivo=".$row["Id_apu"]." AND Centrocosto=".$_SESSION["Id_centro"];
                    mysql_query($update);
                    echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]."'</script>";
                    // header("Location:".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]);
                }
                break;
            case 'Mantenimientos':
                $sql="SELECT * 
                        FROM Ope_pre_moapu
                        WHERE Id=".$_GET["Cop"]."
                ";
                $res=mysql_query($sql);
                
                if($row=mysql_fetch_array($res)){
                    $insertar="INSERT INTO Ope_pre_moapu(Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort)
                                VALUES(".$row["Id_apu"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                                    ".$row["Apu"].",'".$row["Codigo"]."',".$row["Cantidad"].",".$row["Vu"].",".$row["Valort"].")";
                    mysql_query($insertar);

                    $sql="SELECT Valor 
                            FROM Ope_pre_apu 
                            WHERE Consecutivo=".$row["Id_apu"]." 
                            AND Centrocosto=".$_SESSION["Id_centro"];
                    $resSQL=mysql_query($sql);
                    $valor=mysql_fetch_array($resSQL);

                    $total=$valor["Valor"] + $row["Valort"];
                    $update="UPDATE Ope_pre_apu set Valor=$total 
                                WHERE Consecutivo=".$row["Id_apu"]." AND Centrocosto=".$_SESSION["Id_centro"];
                    mysql_query($update);

                    echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]."'</script>";
                    // header("Location:".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]);
                }
                
                break;
            case 'Transportes':
                $sql="SELECT * 
                        FROM Ope_pre_trasapu
                        WHERE Id=".$_GET["Cop"]."
                ";
                $res=mysql_query($sql);
                
                if($row=mysql_fetch_array($res)){
                    $insertar="INSERT INTO Ope_pre_trasapu(Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort)
                                VALUES(".$row["Id_apu"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                                    ".$row["Apu"].",'".$row["Codigo"]."',".$row["Cantidad"].",".$row["Vu"].",".$row["Valort"].")";
                    mysql_query($insertar);

                    $sql="SELECT Valor 
                            FROM Ope_pre_apu 
                            WHERE Consecutivo=".$row["Id_apu"]." 
                            AND Centrocosto=".$_SESSION["Id_centro"];
                    $resSQL=mysql_query($sql);
                    $valor=mysql_fetch_array($resSQL);

                    $total=$valor["Valor"] + $row["Valort"];
                    $update="UPDATE Ope_pre_apu set Valor=$total 
                                WHERE Consecutivo=".$row["Id_apu"]." AND Centrocosto=".$_SESSION["Id_centro"];
                    mysql_query($update);

                    echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]."'</script>";
                    // header("Location:".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]);
                }
                break;
            case 'Equipos':
                $sql="SELECT * 
                        FROM Ope_pre_equiapu
                        WHERE Id=".$_GET["Cop"]."
                ";
                $res=mysql_query($sql);
                
                if($row=mysql_fetch_array($res)){
                    $insertar="INSERT INTO Ope_pre_equiapu(Id_apu,Fr,Ur,Centrocosto,Apu,Codigo,Cantidad,Vu,Valort)
                                VALUES(".$row["Id_apu"].",now(),".$_SESSION["Cedula"].",".$_SESSION["Id_centro"].",
                                    ".$row["Apu"].",'".$row["Codigo"]."',".$row["Cantidad"].",".$row["Vu"].",".$row["Valort"].")";
                    mysql_query($insertar);

                    $sql="SELECT Valor 
                            FROM Ope_pre_apu 
                            WHERE Consecutivo=".$row["Id_apu"]." 
                            AND Centrocosto=".$_SESSION["Id_centro"];
                    $resSQL=mysql_query($sql);
                    $valor=mysql_fetch_array($resSQL);

                    $total=$valor["Valor"] + $row["Valort"];
                    $update="UPDATE Ope_pre_apu set Valor=$total 
                                WHERE Consecutivo=".$row["Id_apu"]." AND Centrocosto=".$_SESSION["Id_centro"];
                    mysql_query($update);

                    echo "<script>location.href='".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]."'</script>";
                    // header("Location:".Conexion::ruta()."Ope_apu?Con=".$row["Id_apu"]."&tipo=".$_GET["tipo"]);
                }
                break;    
            default:
                # code...
                break;
        }
        

    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet WHERE Id_Requisicion=$id and Centrocosto=".$_SESSION["Id_centro"];
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }

    public function get_detalle_por_id($id_ent,$id_requi){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet WHERE Id_Requisicion=$id_requi and Id=$id_ent";
        $res=$msqli->query($sql);

        while($row=$res->fetch_assoc()){
            $this->detalle[]=$row;
        }

        return $this->detalle;
    }


   
    

    public function bloquea_doc($id){
        Conexion::conectar();
        $sql="UPDATE Alm_requi set Estadodoc=2 WHERE Consecutivo=$id";
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
        WHERE Id_control=1
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->tipo[]=$row;
        }
        return $this->tipo;
    }
    
}

?>

