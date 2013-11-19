<?php
class Per_gestion extends Conexion{
    
    private $requi;
    private $requi2;
    private $detalle;
    private $producto;
    private $consumo;
    private $trabajo;
    private $mante;
    private $equipos;
    private $examen;
    private $estimulo;
    private $Sexo;
    private $ausencia;
    private $bene;
    private $dotacion;
    private $estudio;
    private $pagos;
    private $reco;
    private $exa;
   
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
        $this->examen=array();
        $this->estimulos=array();
        $this->Sexo=array();
        $this->ausencia=array();
        $this->bene=array();
        $this->dotacion=array();
        $this->estudio=array();
        $this->pagos=array();
        $this->reco=array();
        $this->exa=array();
    }
    public function get_tipoexamen(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=20
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->exa[]=$row;
        }
        return $this->exa;
    }

    public function get_tiporeconocimiento(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=19
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->reco[]=$row;
        }
        return $this->reco;
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

    public function get_tipoestudio(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=17
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->estudio[]=$row;
        }
        return $this->estudio;
    }

    public function get_tipodotacion(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=16
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->dotacion[]=$row;
        }
        return $this->dotacion;
    }

    public function get_tipobeneficiario(){
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=15
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->bene[]=$row;
        }
        return $this->bene;
    }    

    public function get_tipoAusencia(){
        
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=14
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->ausencia[]=$row;
        }
        return $this->ausencia;
    }

    public function get_sexo(){
        
        Conexion::conectar();
        $sql="SELECT * 
        FROM G_controles control
        JOIN G_listas lista ON control.No=lista.Id_control
        WHERE Id_control=13
        ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->Sexo[]=$row;
        }
        return $this->Sexo;
    }

    public function copiar_registro(){
        Conexion::conectar();

        switch ($_GET["tipo"]) {
            case 'Ausencia':

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_ausentismo
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];
                $sql="SELECT * 
                            FROM
                            Rh_ausentismo
                            WHERE Id=".$_GET["Cop"];

                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){
                    $observaciones=(empty($row["observaciones"]))?"No tiene":$row["observaciones"];

                     $insertar="INSERT INTO Rh_ausentismo(Id_ausentismo,Consecutivo,Fr,Ur,Identificacion
                                        ,Fecha,Tipo,Tiempoausente,Fausencia,Centrocosto,
                                        Observaciones)
                             VALUES(".$row["Id_ausentismo"].",".$nuevo.",'".$row["Fecha"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",'".$row["Fecha"]."',
                                ".$row["Tipo"].",".$row['Tiempoausente'].",'".$row["Fausencia"]."',
                                ".$_SESSION["Id_centro"].",'".$row["Observaciones"]."')
                     ";
                     
                 mysql_query($insertar);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_ausentismo"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$row["Id_ausentismo"]."&tipo=".$_GET["tipo"]);
                }

                break;

            case 'Beneficiarios':{

                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_beneficiarios
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM
                            Rh_beneficiarios
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_beneficiarios (Consecutivo,Id_ben,Fr,Ur,Identificacion,
                        Tipo,Nomapell,Fnacimiento,Bccf,Beps,Sexo,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_ben"].",now(),".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",
                                ".$row["Tipo"].",'".$row["Nomapell"]."',
                                '".$row["Fnacimiento"]."',".$row["Bccf"].",'".$row["Beps"]."',
                                '".$row["Sexo"]."','".$_SESSION["Id_centro"]."'
                                )
                     ";
                     
                     mysql_query($insertar);
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$row["Id_ben"]."&tipo=".$_GET["tipo"]);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_ben"]."&tipo=".$_GET["tipo"]."'</script>";
                 }
                break;
            }
            case 'Dotacion':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_infdotacion
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM Rh_infdotacion
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_infdotacion(Consecutivo,Id_dot,Fr,Ur,Identificacion,
                        Tipo,Talla,Observaciones,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_dot"].",'".$row["Fr"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",
                                ".$row["Tipo"].",'".$row["Talla"]."','".$row["Observaciones"]."','".$_SESSION["Id_centro"]."')
                     ";
                     
                     mysql_query($insertar);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_dot"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Estudios':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_estudios
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM Rh_estudios
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_estudios(Consecutivo,Id_est,Fr,Ur,Identificacion,
                                    Tipo,Institucion,Titulo,Observaciones,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_est"].",'".$row["Fr"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",
                                ".$row["Tipo"].",'".$row["Institucion"]."','".$row["Titulo"]."',
                                '".$row["Observaciones"]."','".$_SESSION["Id_centro"]."')
                     ";
                     
                     mysql_query($insertar);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_est"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$row["Id_est"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Historial':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_hislaboral
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM Rh_hislaboral
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_hislaboral(Consecutivo,Id_his,Fr,Ur,Identificacion,
                                            Empresa,Telefono,Fingreso,Fretiro,Motivoret,
                                            Cargo,Jefeinm,Observaciones,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_his"].",'".$row["Fr"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",".$row["Retirado"].",'".$row["Fretirado"]."',
                                '".$row["Empresa"]."','".$row["Telefono"]."',
                                ,'".$row["Motivoret"]."','".$row["Cargo"]."',
                                '".$row["Jefeinm"]."','".$row["Observaciones"]."','".$_SESSION["Id_centro"]."')
                     ";
                     
                     mysql_query($insertar);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_his"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$row["Id_his"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Pagos':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_pagosadd
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM Rh_pagosadd
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_pagosadd(Consecutivo,Id_pag,Fr,Ur,Identificacion
                        ,Tipo,Valor,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_pag"].",'".$row["Fr"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",
                                ".$row["Tipo"].",'".$row["Valor"]."','".$_SESSION["Id_centro"]."')
                     ";
                     
                     mysql_query($insertar);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_pag"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$row["Id_pag"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Reconocimiento':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_estimulos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM Rh_estimulos
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_estimulos(Consecutivo,Id_est,Fr,Ur,Identificacion
                                            ,Fecha,Tipo,Observaciones,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_est"].",'".$row["Fr"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",'".$row["Fecha"]."',
                                ".$row["Tipo"].",'".$row["Observaciones"]."',".$_SESSION["Id_centro"].")
                     ";
                     
                     mysql_query($insertar);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_est"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
            case 'Examen':{
                $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_infdotacion
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
                $resultadoMayor=mysql_query($mayor);
                $var=mysql_fetch_array($resultadoMayor);
                $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

                $sql="SELECT * 
                            FROM Rh_examenes
                            WHERE Id=".$_GET["Cop"];
                            
                            
                $resultado=mysql_query($sql);

                if($row=mysql_fetch_array($resultado)){

                    $observaciones=(empty($row["Observaciones"]))?"No tiene":$row["Observaciones"];

                     $insertar="INSERT INTO Rh_examenes(Consecutivo,Id_exa,Fr,Ur,Identificacion
                        ,Tipo,Fecha,Observaciones,Centrocosto)
                             VALUES(".$nuevo.",".$row["Id_exa"].",'".$row["Fr"]."',".$_SESSION['Cedula'].",
                                ".$row["Identificacion"].",
                                ".$row["Tipo"].",'".$row["Fecha"]."','".$row["Observaciones"]."','".$_SESSION["Id_centro"]."')
                     ";
                     
                     mysql_query($insertar);
                     echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$row["Id_exa"]."&tipo=".$_GET["tipo"]."'</script>";
                     // header("Location:".Conexion::ruta()."Per_gestion?Con=".$_GET["id"]."&tipo=".$_GET["tipo"]);
                 }
                break;
            }
        }
        
        
    }

     
    public function get_ausencia($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_ausentismo 
                WHERE Id_ausentismo=$id
                AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->consumo[]=$row;

        }   
        return $this->consumo;
    }


    public function guardar_ausencia(){
        Conexion::conectar();
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        $Fausencia=Conexion::ConvertirFecha($_POST["Fausencia"]);

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_ausentismo
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

        $sql="INSERT INTO Rh_ausentismo(Consecutivo,Id_ausentismo,Fr,Ur,Identificacion,Fecha,
            Tipo,Tiempoausente,Fausencia,Observaciones,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id_ausencia"].",now(),'".$_SESSION["Cedula"]."',
            '".$_POST["Identificacion"]."'
            ,'".$fecha."','".$_POST["Tipo"]."',".$_POST["Tiempoausente"].",
            '".$Fausencia."','".$_POST["Observaciones"]."',".$_SESSION["Id_centro"].")";

        
        mysql_query($sql);
        $con=$_POST["Id_ausencia"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }
    public function editar_ausencia(){
        Conexion::conectar();

        $sql="UPDATE Rh_ausentismo set Retirado='".$_POST["Retirado"]."',
                    Fretirado='".$_POST["Fretirado"]."',Fecha='".$_POST["Fecha"]."',
                    Tipo=".$_POST["Tipo"].",Tiempoausente=".$_POST["Tiempoausente"].",
                    Fausencia='".$_POST["Fausencia"]."',Observaciones='".$_POST["Observaciones"]."' 
                    WHERE Consecutivo=".$_POST["Consecutivo_a"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id_ausencia"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
    }

    /*
    *MANEJO DE BENEFICIARIOS
    */
    public function get_ben($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_beneficiarios 
                WHERE Id_ben=$id
                AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->trabajo[]=$row;
        }   
        return $this->trabajo;
    }

    public function guardar_beneficiarios(){
        Conexion::conectar();

        $Fnacimiento=Conexion::ConvertirFecha($_POST["Fnacimiento"]);
        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_beneficiarios
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

		// $bccf=(isset($_POST["Bccf"]))?$_POST["Bccf"]:"0";
        //$beps=(isset($_POST["Beps"]))?$_POST["Beps"]:"0";
		$bccf=$_POST["Bccf"];
		$beps=$_POST["Beps"];
        $sql="INSERT INTO Rh_beneficiarios(Consecutivo,Id_ben,Fr,Ur,Identificacion
                            ,Tipo,Nomapell,Fnacimiento,Bccf,Beps,Sexo,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id_ben"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."'
        ,".$_POST["Tipo"].",'".$_POST["Nomapell"]."',
        '".$Fnacimiento."','".$bccf."','".$beps."',".$_POST["Sexo"].",
        ".$_SESSION["Id_centro"].")";
        echo $sql;

        
        mysql_query($sql);
        $con=$_POST["Id_ben"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";

    }

    public function editar_beneficiarios(){
        Conexion::conectar();

        $Fretirado=Conexion::ConvertirFecha($_POST["Fretirado"]);
        $Fnacimiento=Conexion::ConvertirFecha($_POST["Fnacimiento"]);

        $sql="UPDATE Rh_beneficiarios set Retirado='".$_POST["Retirado"]."',
                    Fretirado='".$Fretirado."',Tipo=".$_POST["Tipo"].",
                    Nomapell='".$_POST["Nomapell"]."',Fnacimiento='".$Fnacimiento."',
                    Bccf='".$_POST["Bccf"]."', Beps='".$_POST["Beps"]."',Sexo='".$_POST["Sexo"]."'
                WHERE Consecutivo=".$_POST["Consecutivo_b"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id_ben"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";

    }

    /*
    *FIN BENEFICIARIOS
    */

    /*
    *INICIO DOTACION
    */

    public function get_dotacion($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_infdotacion 
                WHERE Id_dot=$id
                ";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->mante[]=$row;

        }   
        return $this->mante;
    }

    public function guardar_dotacion(){
        Conexion::conectar();

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_infdotacion
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

        $sql="INSERT INTO Rh_infdotacion(Consecutivo,Id_dot,Fr,Ur,Identificacion,Tipo,Talla,
                        Observaciones,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."',
        ".$_POST["Tipo"].",'".$_POST["Talla"]."',
        '".$_POST["Observaciones"]."',".$_SESSION["Id_centro"].")";

        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
    }

    public function editar_dotacion(){
        Conexion::conectar();

        $sql="UPDATE Rh_infdotacion set Retirado='".$_POST["Retirado"]."',
                    Fretirado='".$_POST["Fretirado"]."',Tipo=".$_POST["Tipo"].",
                    Talla=".$_POST["Talla"].",Observaciones='".$_POST["Observaciones"]."'
                WHERE Consecutivo=".$_POST["Consecutivo_d"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";

    }

    /*
    *FIN DOTACION
    */


    /*
    *INICIO ESTUDIOS
    */

    public function get_estudios($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_estudios 
                WHERE Id_est=$id";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->equipos[]=$row;

        }   
        return $this->equipos;
    }

     public function guardar_estudios(){
        Conexion::conectar();

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_estudios
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];


        $sql="INSERT INTO Rh_estudios(Consecutivo,Id_est,Fr,Ur,Identificacion,
                                    Tipo,Institucion,Titulo,Observaciones,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."'
        ,".$_POST["Tipo"].",'".$_POST["Institucion"]."',
        '".$_POST["Titulo"]."','".$_POST["Observaciones"]."',".$_SESSION["Id_centro"].")";

        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

    public function editar_estudios(){
        Conexion::conectar();

        $sql="UPDATE Rh_estudios set Retirado='".$_POST["Retirado"]."',
                Fretirado='".$_POST["Fretirado"]."',Tipo=".$_POST["Tipo"].",
                Institucion='".$_POST["Institucion"]."',Titulo='".$_POST["Titulo"]."',
                Observaciones='".$_POST["Observaciones"]."'
                WHERE Consecutivo=".$_POST["Consecutivo_e"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

    /*
    *FIN ESTUDIOS
    */

    /*
    *INICIO HISTORIAL
    */
    public function get_historial($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_hislaboral 
                WHERE Id_his=$id
                AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->equipos[]=$row;

        }   
        return $this->equipos;
    }

     public function guardar_historial(){
        Conexion::conectar();

        $Fretirado=Conexion::ConvertirFecha($_POST["Fretirado"]);
        $Fretiro=Conexion::ConvertirFecha($_POST["Fretiro"]);
        $Fingreso=Conexion::ConvertirFecha($_POST["Fingreso"]);

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_hislaboral
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];


        $sql="INSERT INTO Rh_hislaboral(Consecutivo,Id_his,Fr,Ur,Identificacion,Retirado,Fretirado,Empresa,
            Telefono,Fingreso,Fretiro,Motivoret,Cargo,Jefeinm,Observaciones,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."'
        ,'".$_POST["Retirado"]."','".$Fretirado."','".$_POST["Empresa"]."',".$_POST["Telefono"]."
        ,'".$Fingreso."','".$Fretiro."','".$_POST["Motivoret"]."','".$_POST["Cargo"]."'
        ,'".$_POST["Jefeinm"]."','".$_POST["Observaciones"]."',".$_SESSION["Id_centro"].")";
        
        mysql_query($sql);

        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

    public function editar_historial(){
        Conexion::conectar();

        $sql="UPDATE Rh_hislaboral set Retirado='".$_POST["Retirado"]."',
                    Fretirado='".$_POST["Fretirado"]."',Empresa='".$_POST["Empresa"]."',
                    Telefono=".$_POST["Telefono"].",Fingreso='".$_POST["Fingreso"]."',
                    Fretiro='".$_POST["Fretiro"]."',Motivoret='".$_POST["Motivoret"]."'
                    ,Cargo='".$_POST["Cargo"]."',Jefeinm='".$_POST["Jefeinm"]."',
                    Observaciones='".$_POST["Observaciones"]."'
                WHERE Consecutivo=".$_POST["Consecutivo_h"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
    
    }

    /*
    *FIN HISTORIAL
    */


    /*
    *INICIO PAGOS
    */
    public function get_pagos($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_pagosadd 
                WHERE Id_pag=$id
                AND Centrocosto=".$_SESSION["Id_centro"]."
                ORDER BY Fr DESC";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->equipos[]=$row;

        }   
        return $this->equipos;
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
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

     public function editar_pagos(){
        Conexion::conectar();

        $sql="UPDATE Rh_pagosadd set Retirado='".$_POST["Retirado"]."',
                    Fretirado='".$_POST["Fretirado"]."',Tipo=".$_POST["Tipo"].",
                    Valor=".$_POST["Valor"]."
                WHERE Consecutivo=".$_POST["Consecutivo_p"]."
                AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

    /*
    *FIN PAGOS
    */

     /*
    *INICIO RECONOCIMIENTO
    */

    public function get_reconocimiento($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_estimulos 
                WHERE Id_est=$id
                AND Centrocosto=".$_SESSION["Id_centro"]."
                ORDER BY Fr DESC";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->estimulos[]=$row;

        }   
        return $this->estimulos;
    }

    public function guardar_reconocimiento(){
        Conexion::conectar();

        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);
        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_estimulos
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

        $sql="INSERT INTO Rh_estimulos(Consecutivo,Id_est,Fr,Ur,Identificacion,Tipo,
            Fecha,Observaciones,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."'
        ,".$_POST["Tipo"].",'".$fecha."',
        '".$_POST["Observaciones"]."',".$_SESSION["Id_centro"].")";
            
            
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }    

    public function editar_reconocimiento(){
        Conexion::conectar();
        $Fretirado=Conexion::ConvertirFecha($_POST["Fretirado"]);
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

        $sql="UPDATE Rh_estimulos set Retirado='".$_POST["Retirado"]."',
                    Fretirado='".$Fretirado."',Tipo=".$_POST["Tipo"].",Fecha='".$fecha."',
                    Observaciones='".$_POST["Observaciones"]."'
                WHERE Consecutivo=".$_POST["Consecutivo_r"]."
                AND Centrocosto=".$_SESSION["Id_centro"];

        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

    /*
    *FIN RECONOCIMIENTO
    */


    /*
    *INICIO EXAMEN
    */
    public function get_examen($id){
        $this->conectar();
        $sql="SELECT * 
                FROM Rh_examenes 
                WHERE Id_exa=$id
                AND Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->examen[]=$row;

        }   
        return $this->examen;
    }

    public function guardar_examen(){
        Conexion::conectar();
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

        $mayor="SELECT MAX(Consecutivo) + 1 Nuevo
                            FROM Rh_examenes
                            WHERE Centrocosto={$_SESSION["Id_centro"]}

                ";
        $resultadoMayor=mysql_query($mayor);
        $var=mysql_fetch_array($resultadoMayor);
        $nuevo=($var["Nuevo"]==NULL)?1:$var["Nuevo"];

        $sql="INSERT INTO Rh_examenes(Consecutivo,Id_exa,Fr,Ur,Identificacion,Tipo,
                        Fecha,Observaciones,Centrocosto)";
        $sql.="values(".$nuevo.",".$_POST["Id"].",now(),'".$_SESSION["Cedula"]."','".$_POST["Identificacion"]."'
        ,".$_POST["Tipo"].",'".$fecha."',
        '".$_POST["Observaciones"]."',".$_SESSION["Id_centro"].")";
        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }

     public function editar_examen(){
        Conexion::conectar();

        $fretirado=Conexion::ConvertirFecha($_POST["Fretirado"]);
        $fecha=Conexion::ConvertirFecha($_POST["Fecha"]);

        $sql="UPDATE Rh_examenes set Retirado='".$_POST["Retirado"]."',
                        Fretirado='".$fretirado."',Tipo=".$_POST["Tipo"].",Fecha='".$fecha."',
                        Observaciones='".$_POST["Observaciones"]."'
                    WHERE Consecutivo=".$_POST["Consecutivo_e"]."
                    AND Centrocosto=".$_SESSION["Id_centro"];
        
        mysql_query($sql);
        $con=$_POST["Id"];
        $tipo=$_POST["tipo"];
        echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$con."&tipo=".$tipo."'</script>";
        
    }
    /*
    *FIN EXAMEN
    */

   
        
    public function get_requi($con){
        Conexion::conectar();
        $sql="SELECT Per.Consecutivo,Per.Direccion,Per.Email,Per.Identificacion,Per.Telefono1,
                        Per.Centrotrabajo,Per.Nombres,Per.Apellidos,Per.Telefono2,Per.Cargo,
                        Centro.Nombre Centro_costo,Per.Estadoact

                    FROM Rh_personal Per
                    JOIN G_centros_costo Centro ON Per.Centrocosto=Centro.Id
                    WHERE Per.Identificacion='$con' 
                    AND Per.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;    
    }

    public function get_requi2($con){
        Conexion::conectar();

        $sql="SELECT Per.Consecutivo,Per.Direccion,Per.Email,Per.Identificacion,Per.Telefono1,
                        Per.Centrotrabajo,Per.Nombres,Per.Apellidos,Per.Telefono2,Per.Cargo,
                        Centro.Nombre Centro_costo,Per.Estadoact

                    FROM Rh_personal Per
                    JOIN G_centros_costo Centro ON Per.Centrocosto=Centro.Id
                    WHERE Per.Consecutivo='$con' 
                    AND Per.Centrocosto=".$_SESSION["Id_centro"];
        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->requi[]=$row;
        }    
        return $this->requi;
    }



    public function elimina_detalle($id,$con,$tipo,$id_au){
        Conexion::conectar();
        switch ($tipo) {
            case 'Ausencia':
                $sql="DELETE from Rh_ausentismo where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                         
                break;
            case 'Beneficiarios':
                $sql="DELETE from Rh_beneficiarios where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;
            case 'Dotacion':
                $sql="DELETE from Rh_infdotacion where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;
            case 'Estudios':
                $sql="DELETE from Rh_estudios where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;
            case 'Historial':
                $sql="DELETE from Rh_hislaboral where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;
            case 'Pagos':
                $sql="DELETE from Rh_pagosadd where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;
            case 'Examen':
                $sql="DELETE from Rh_examenes where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;
             case 'Reconocimiento':
                $sql="DELETE from Rh_estimulos where Id=$id";
                mysql_query($sql);
                echo "<script>location.href='".Conexion::ruta()."Per_gestion?Con=".$id_au."&tipo=".$tipo."'</script>";
                break;   

            default:
                # code...
                break;
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



