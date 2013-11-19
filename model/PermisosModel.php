<?php
class Permisos extends Conexion{
    
    private $requi;
    private $detalle;
    private $centro;
    private $centro2;
    private $usuario;
    private $menu;

    public function __construct(){
        $this->menu=array();
        $this->submenu=array();
        $this->requi=array();
        $this->detalle=array();
        $this->centro=array();
        $this->centro2=array();
        $this->usuario=array();
        $this->menu=array();
    }

   
    public function getUsuario($con){
        Conexion::conectar();

        $sql="SELECT *
        FROM Usuarios
        WHERE Cedula = '$con'";

        $res=mysql_query($sql);

        if($row=mysql_fetch_assoc($res)){
            $this->usuario[]=$row;
        }    
        return $this->usuario;    
    }

    public function getModulos(){
        Conexion::conectar();
        $sql="SELECT sub.Submenu,prin.Principal,prin.id_menu,sub.pagina
                FROM Menu_sub sub
                JOIN Menu_principales prin ON sub.Id_menu=prin.Id_menu
                ORDER BY prin.Principal";
        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->menu[]=$row;
        }    
        return $this->menu;    

    }


    public function get_centro(){
        Conexion::conectar();

        $sql="SELECT Id,Nombre
                FROM G_centros_costo";

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->centro[]=$row;
        }

        return $this->centro;    
    }

    public function get_centro_user($cedula){
        Conexion::conectar();

        $sql="SELECT id_centro,Centro_costo
        from Usuarios_centro
        WHERE Id_usuario=".$cedula;

        $res=mysql_query($sql);

        while($row=mysql_fetch_assoc($res)){
            $this->centro2[]=$row;
        }

        return $this->centro2;    
    }

    public function guardar_principal(){
        Conexion::conectar();
        if(isset($_POST["principal"])){
            $prin=$_POST["principal"];
            $tam=count($_POST["principal"]);
            $sql="SELECT count(cedula) as tam
                FROM Usuario_permiso_prin WHERE Cedula=".$_POST["cedula"];
            $res=mysql_query($sql);
            $dato=mysql_fetch_array($res);

            $sql2="SELECT Id_principal 
                from Usuario_permiso_prin
                WHERE Cedula=".$_POST["cedula"];
                $res2=mysql_query($sql2);

            while ($row=mysql_fetch_array($res2)) {
                    $arreglo[]=$row["Id_principal"];
            }

            if($dato["tam"]<$tam){

                foreach ($prin as $key => $value) {
                    $sql="SELECT * 
                    FROM Usuario_permiso_prin
                    WHERE Id_principal=".$prin[$key]."
                    AND Cedula=".$_POST["cedula"];
                    $res=mysql_query($sql);

                    if(mysql_num_rows($res)==0){
                        $query="SELECT * 
                        FROM Menu_principales
                        WHERE id_menu=".$prin[$key];
                        $res2=mysql_query($query);

                        if($row=mysql_fetch_array($res2)){
                            $sql2="INSERT INTO Usuario_permiso_prin(Cedula,Principal,Id_principal)";
                            $sql2.="values('".$_SESSION["cedula"]."','".$row["principal"]."',".$row["id_menu"].")";
                            mysql_query($sql2);
                        }
                    }
                }
                echo "<script>location.href='".Conexion::ruta()."Adm_permisos?b=".$_POST["cedula"]."'</script>";
                // header("Location:".Conexion::ruta()."Adm_permisos?b=".);
            }else{
                $total=array_diff($arreglo,$prin);

                foreach ($total as $key => $value) {
                    $sql="DELETE 
                    from Usuario_permiso_prin 
                    WHERE Id_principal=".$total[$key]."
                    AND cedula=".$_POST["cedula"].";";
                    mysql_query($sql);
                }
                echo "<script>location.href='".Conexion::ruta()."Adm_permisos?b=".$_POST["cedula"]."'</script>";
                // header("Location:".Conexion::ruta()."Adm_permisos?b=".$_POST["cedula"]);
            } 
            
        }


    }


    public function guardar_registro(){
        Conexion::conectar();
        $menu_p="";
        $oper=(isset($_POST["Operaciones"]))?$_POST["Operaciones"]:0;
        $log=(isset($_POST["Logistica"]))?$_POST["Logistica"]:0;
        $con=(isset($_POST["Contabilidad"]))?$_POST["Contabilidad"]:0;
        $per=(isset($_POST["Personal"]))?$_POST["Personal"]:0;
        $alm=(isset($_POST["Almacen"]))?$_POST["Almacen"]:0;

        $sqlBorrar="UPDATE Usuarios SET Trama_prin='/ ',Trama_sub='/' 
                    WHERE Cedula='".$_POST["Cedula"]."' ";
        mysql_query($sqlBorrar);

        if(isset($_POST["Administrador"])){
            $menu_p .=" Administrador /";
            foreach ($_POST["Administrador"] as $key => $value) {
                $sqlActual="SELECT Trama_prin,Trama_sub
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
                $resActual=mysql_query($sqlActual);
                $permisos=mysql_fetch_array($resActual);
                $valor=explode(",", $value);
    

                $actSub="UPDATE Usuarios SET Trama_sub = 
                CONCAT('".$permisos["Trama_sub"]."', '".$valor[1]." /')
                WHERE Cedula='".$_POST["Cedula"]."';";
                mysql_query($actSub);
                
            }

        }

        if(isset($_POST["Operaciones"])){
            $menu_p .=" Operaciones /";
            foreach ($_POST["Operaciones"] as $key => $value) {
                $sqlActual="SELECT Trama_prin,Trama_sub
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
                $resActual=mysql_query($sqlActual);
                $permisos=mysql_fetch_array($resActual);

                $valor=explode(",", $value);

                $actSub="UPDATE Usuarios SET Trama_sub = 
                CONCAT('".$permisos["Trama_sub"]."', '".$valor[1]." /')
                WHERE Cedula='".$_POST["Cedula"]."';";
                mysql_query($actSub);
                
            }
        }

        if(isset($_POST["Logistica"])){
            $menu_p .=" Logistica /";
            foreach ($_POST["Logistica"] as $key => $value) {
                $sqlActual="SELECT Trama_prin,Trama_sub
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
                $resActual=mysql_query($sqlActual);
                $permisos=mysql_fetch_array($resActual);

                $valor=explode(",", $value);

                $actSub="UPDATE Usuarios SET Trama_sub = 
                CONCAT('".$permisos["Trama_sub"]."', '".$valor[1]." /')
                WHERE Cedula='".$_POST["Cedula"]."';";
                mysql_query($actSub);
                
            }
        }

        if(isset($_POST["Contabilidad"])){
            $menu_p .=" Contabilidad /";
            foreach ($_POST["Contabilidad"] as $key => $value) {
                $sqlActual="SELECT Trama_prin,Trama_sub
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
                $resActual=mysql_query($sqlActual);
                $permisos=mysql_fetch_array($resActual);

                $valor=explode(",", $value);
                
                $actSub="UPDATE Usuarios SET Trama_sub = 
                CONCAT('".$permisos["Trama_sub"]."', '".$valor[1]." /')
                WHERE Cedula='".$_POST["Cedula"]."';";
                mysql_query($actSub);
            }
        }

        if(isset($_POST["Personal"])){
            $menu_p .=" Personal /";
            foreach ($_POST["Personal"] as $key => $value) {
                $sqlActual="SELECT Trama_prin,Trama_sub
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
                $resActual=mysql_query($sqlActual);
                $permisos=mysql_fetch_array($resActual);

                $valor=explode(",", $value);

                $actSub="UPDATE Usuarios SET Trama_sub = 
                CONCAT('".$permisos["Trama_sub"]."',' ".$valor[1]." /')
                WHERE Cedula='".$_POST["Cedula"]."';";
                mysql_query($actSub);
                
            }
        }

        if(isset($_POST["Almacen"])){
            $menu_p .=" Almacen /";
            foreach ($_POST["Almacen"] as $key => $value) {
                $sqlActual="SELECT Trama_prin,Trama_sub
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
                $resActual=mysql_query($sqlActual);
                $permisos=mysql_fetch_array($resActual);

                $valor=explode(",", $value);
                
                $actSub="UPDATE Usuarios SET Trama_sub = 
                CONCAT('".$permisos["Trama_sub"]."', '".$valor[1]." /')
                WHERE Cedula='".$_POST["Cedula"]."';";
                mysql_query($actSub);
            }
        }

         $sql="SELECT Trama_prin
                        FROM Usuarios
                        WHERE Cedula='".$_POST["Cedula"]."'";
        $res=mysql_query($sql);
        $permisos=mysql_fetch_array($res);

        $actprin="UPDATE Usuarios SET Trama_prin = 
                CONCAT('".$permisos["Trama_prin"]."','".$menu_p."')
                WHERE Cedula='".$_POST["Cedula"]."';";
        mysql_query($actprin);

        echo "<script>location.href='".Conexion::ruta()."Adm_permisos?b=".$_POST["Cedula"]."'</script>";
        // echo "<script>location.href='".Conexion::ruta()."Adm_permisos?b='".$_POST["cedula"]."'</script>";
        

    }
    

    public function CambiarValor($num){
        switch ($num) {
            case '1':
                $valor="Administrador";
                break;
            case '2':
                $valor="Logistica";
                break;
            case '3':
                $valor="Operaciones";
                break;
            case '4':
                $valor="Personal";
                break;
            case '5':
                $valor="Contabilidad";
                break;
            case '6':
                $valor="Almacen";
                break;
        }
        return $valor;
    }

    public function guardar_detalle(){
        Conexion::conectar();
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;
        $sql="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,Vn,Vt,Centrocosto)";
        $sql.="values(".$_POST["Id_requisicion"].",now(),'".$_SESSION["cedula"]."','".$_POST["producto"]."',".$_POST["cantidad"].",
        ".$_POST["vunitario"].",".$_POST["impuesto"].",".$neto.",".$total.",".$_SESSION["id_centro"].")";
        
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);
    }

     public function copiar($id){
        Conexion::conectar();
        $sql="SELECT Id_Requisicion,Producto,Cantidad,Vu,Imp,Vn,Vt 
                    FROM Alm_requidet 
                    WHERE 
                    Id=".$id;
        
        $res=mysql_query($sql);

       if($row=mysql_fetch_assoc($res)){
            $query="INSERT INTO Alm_requidet(Id_Requisicion,Fr,Ur,Producto,Cantidad,Vu,Imp,
                Vn,Vt,Centrocosto) VALUES(".$row['Id_Requisicion'].",now(),".$_SESSION["cedula"].",'".$row["Producto"]."',
                ".$row["Cantidad"].",".$row["Vu"].",".$row["Imp"].",".$row["Vn"].",
                ".$row["Vt"].",".$_SESSION["id_centro"].")";
            
            mysql_query($query);
            // echo "<script>location.href='".Conexion::ruta()."Adm_permisos?b=".$_POST["cedula"]."'</script>";
            // header("Location:".Conexion::ruta()."Requisicion?b=".$row["Id_Requisicion"]);
        }
    }
    
    public function get_detalle($id){
        $msqli=Conexion::c_mysqli();
        $sql="SELECT * from Alm_requidet where Id_Requisicion=$id and Centrocosto=".$_SESSION["id_centro"];
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
        $neto=$_POST["vunitario"]*$_POST["cantidad"];
        $iva=(FLOAT)$_POST["impuesto"]/100.0;
        $valor_iva=$iva*$neto;
        $total=(FLOAT)$neto+(FLOAT)$valor_iva;

        $sql="UPDATE Alm_requidet set Producto='".$_POST["producto"]."',Cantidad=".$_POST["cantidad"].",Vu=".$_POST["vunitario"].",
        Imp=".$_POST["impuesto"].",Vn=".$neto.",Vt=".$total." where Id=".$_POST["fid"];
        mysql_query($sql);
        header("Location:".Conexion::ruta()."Requisicion?b=".$_POST["Id_requisicion"]);   

    }
    
    public function eliminar_detalle($id){
        Conexion::conectar();
        $sql="DELETE FROM Alm_requidet where Id=".$id;
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
        inner join
        G_listas as l
        on
        c.No=l.Id_control
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



