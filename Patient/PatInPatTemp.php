<?php 
// http://web-tech.ga-usa.com/2012/05/creating-a-custom-hot-to-cold-temperature-color-gradient-for-use-with-rrdtool/
// Centigrade-Farenheit.xlsx
$Temps = array(
array(35.0, 95.0, "#0032ff"),
array(35.4, 95.7, "#0054ff"),
array(35.6, 96.1, "#0074ff"),
array(35.8, 96.4, "#0094ff"),
array(36.0, 96.8, "#00b4ff"),
array(36.2, 97.2, "#00d4ff"),
array(36.4, 97.5, "#00fff4"),
array(36.6, 97.9, "#00ffa8"),
array(36.8, 98.2, "#00ff5c"),
array(37.0, 98.6, "#00ff10"),
array(37.2, 99.0, "#3eff00"),
array(37.4, 99.3, "#8aff00"),
array(37.6, 99.7, "#d7ff00"),
array(37.8, 100.0, "#FFfa00"),
array(38.0, 100.4, "#FFe600"),
array(38.2, 100.8, "#FFd200"),
array(38.6, 101.5, "#FFaa00"),
array(38.8, 101.8, "#FF9600"),
array(39.0, 102.2, "#FF8200"),
array(39.2, 102.6, "#FF6e00"),
array(39.4, 102.9, "#FF5a00"),
array(39.6, 103.3, "#FF4600"),
array(39.8, 103.6, "#FF3200"),
array(40.0, 104.0, "#FF1e00"), 
array(40.2, 104.4, "#FF0a00"),
array(40.4, 104.7, "#FF0010"),
array(40.6, 105.1, "#FF0030"),
array(40.8, 105.4, "#FF0050")
);
?>
<?php
//for ($x = 0; $x <= 10; $x++) {
//    echo "The number is: $x <br>";
//}
?> 
<?php
//for ($row = 0; $row < 3; $row++) {
//  echo "<p><b>Row number $row</b></p>";
//  echo "<ul>";
//  echo "<li>";
//  for ($col = 0; $col < 28; $col++) {
//    echo $Temps[$col][$row];
//  }
//	echo"</li>";
//  echo "</ul>";
//}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>

<table border="1">
<?php
for ($row = 0; $row < 3; $row++) {
?>
 <tr>
  <td><b>Temp Celcius <?php echo $row ?></b></td>
<?php
  for ($col = 0; $col < 28; $col++) {
?>
   <td><?php echo $Temps[$col][$row] ?></td>
<?php } ?>
  </tr>
<?php } ?>
</table>


</body>
</html>
