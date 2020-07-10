<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once('../../Connections/swmisconn.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>

<?php
 if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "visitsect")) {
		if (isset($_POST['visit'])) {
	     $visits = $_POST['visit'];
       $N = count($visits);
	echo '$N: '.$N.'<br>';
		   for($i=0; $i < $N; $i++) {
	echo 'visit: '.$i.'---'.$visits[$i].'<br>';
		}

	echo '<br>';
	echo '<br>';
$vid = 34800;
if(in_array($vid, $_POST['visit'])){
	echo 'page: '.$vid.'<br>';
}
	echo '<br>';
	echo '<br>';



	}
	
	if (isset($_POST['page'])) {
	     $pages = $_POST['page'];
       $N = count($pages);
	echo '$N: '.$N.'<br>';
		   for($i=0; $i < $N; $i++) {
	echo 'page: '.$i.'---'.$pages[$i].'<br>';
	
	}		
	
	echo '<br>';
	echo '<br>';
$val = 'visits';
if(in_array($val, $_POST['page'])){
	echo 'page: '.$val.'<br>';

	echo '<br>';
	echo '<br>';
		}
	}
}

print_r($_POST['visit']);
var_dump($_POST['visit']);

print_r($_POST['page']);
var_dump($_POST['page']);

?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PatHist2Report</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
</head>

<body>
</body>
</html>