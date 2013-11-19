<?php
class Menu extends Conexion{

    private $menu;
    private $menu2;
    private $submenu;
    private $submenu2;

    public function __contruct(){
        $this->menu[]=array();
        $this->menu2[]=array();
        $this->submenu[]=array();
        $this->submenu2[]=array();
        }

	public function get_principal(){
        $mysqli=Conexion::c_mysqli();
        
        $sql="SELECT * from Menu_principales;";
        $res=$mysqli->query($sql);
        
        while($row=$res->fetch_assoc()){
            $this->menu[]=$row;
        }
        
        return $this->menu;
        $mysqli->close();
    }

    public function get_principal_user($cedula){
        $mysqli=Conexion::c_mysqli();
        
        $sql="SELECT id_principal as id_menu,Principal as principal 
        from Usuario_permiso_prin 
        WHERE Cedula=".$cedula."
        AND id_principal>0";
        $res=$mysqli->query($sql);
        
        while($row=$res->fetch_assoc()){
            $this->menu2[]=$row;
        }
        
        return $this->menu2;
        $mysqli->close();
    }

	public function get_submenu($id){
        $mysqli=Conexion::c_mysqli();
        $sql="SELECT id_menu,submenu,pagina from Menu_sub where id_menu=$id;";
        
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
    public function get_submenu_user($cedula,$a){
        $mysqli=Conexion::c_mysqli();
        
        echo $sql="SELECT id_principal,id_submenu,Pagina
        from Usuario_permiso_sub 
        WHERE Cedula=".$cedula."
        AND id_submenu=".$a;
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