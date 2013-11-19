<?php
$cn=mysql_connect ('localhost', 'monitore_admin'       ,'soporte123z') or die ('I cannot connect to the database because: ' . mysql_error());     
mysql_select_db ('monitore_ipam'     ,$cn); 
?>