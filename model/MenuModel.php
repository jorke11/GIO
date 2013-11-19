<?php
class Menu extends Conexion{

    private $menu;
    private $menu2;
    private $submenu;
    private $submenu2;
    private $submenu3;
    private $menu_s;

    public function __contruct(){
        $this->menu=array();
        $this->menu2=array();
        $this->submenu=array();
        $this->submenu2=array();
        $this->submenu3=array();
        $this->menu_s=array();
        }

	public function get_principal(){
        $mysqli=Conexion::c_mysqli();
        
        $sql="SELECT id_menu,principal
                FROM Menu_principales;";
        $res=$mysqli->query($sql);
        
        while($row=$res->fetch_assoc()){
            $this->menu[]=$row;
        }
        
        return $this->menu;
        $mysqli->close();
    }

    public function get_principal_user($prin){
        $arreglo=array();
        $valor=explode("/", $prin);
        
        foreach ($valor as $key => $value) {
            if($value!=""){
                $arreglo[$key-1]=array('id_menu'=>$key,'principal'=>$value);
            }
            
        }
        return $arreglo;
    }


	public function get_submenu($id){
        $mysqli=Conexion::c_mysqli();
        $sql="SELECT id_menu,submenu,pagina 
                FROM Menu_sub 
                WHERE id_menu=$id
                ORDER BY id_submenu;";
        
        $res=$mysqli->query($sql);
        if($res->num_rows){
            while($row=$res->fetch_assoc()){
                $this->submenu[]=$row;
            }
            $mysqli->close();
            return $this->submenu;
        }else{
            $mysqli->close();
            return 0;
        }
    }
    public function get_submenu_user($trama,$num){
        $valor=explode("/", $trama);
        $x=0;
        foreach ($valor as $key => $value) {
            if($value!=""){
                $sqlBusqueda="SELECT id_menu,submenu,pagina
                            FROM Menu_sub
                            WHERE pagina like '%".trim($value)."%'
                            AND id_menu=$num;";
                $res=mysql_query($sqlBusqueda);
                $datos=mysql_fetch_assoc($res);

                if(mysql_num_rows($res) > 0){
                    $this->menu_s[$x]=$datos;
                    ++$x;
                }
            }
        }
        return $this->menu_s;
    
    }

    public function get_submenu2(){
        $mysqli=Conexion::c_mysqli();
        
        $sql="SELECT id_menu,submenu,pagina from Menu_sub";

        $res=$mysqli->query($sql);
        
        if($res->num_rows){
            while($row=$res->fetch_assoc()){
                $this->submenu2[]=$row;
            }
            $mysqli->close();
            return $this->submenu2;
        }else{
            $mysqli->close();
            return 0;
        }
    }

}
?>