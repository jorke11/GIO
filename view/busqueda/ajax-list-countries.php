<?
include ("Conecta.php");


if(isset($_GET['Buscapro']) && isset($_GET['letters'])){
$letters = $_GET['letters'];
$letters = preg_replace("/[^a-z0-9 ]/si","",$letters);
$res = mysql_query("select Identificacion,Nombre from Proveedores where Identificacion like '%".$letters."%'   or Nombre like '%".$letters."%' order by Nombre     ") or die(mysql_error());
#echo "1###select ID,countryName from ajax_countries where countryName like '".$letters."%'|";
while($inf = mysql_fetch_array($res)){
echo $inf["Identificacion"]."###".$inf["Identificacion"]."&nbsp;&nbsp;&nbsp;".$inf["Nombre"]."|";
}              
}
?>
