<?php
require_once("lib/config.php");
if(isset($_SESSION["No"])){


        if(isset($_GET["control"])){
            $control=$_GET["control"];
        }else{
            $control="login";
        }
        
        if(is_file("controller/".$control."Controller.php")){
            require_once("controller/".$control."Controller.php");
        }else{
         require_once("controller/loginController.php");
        }
        
}else{
    require_once("controller/loginController.php");    
}


?>