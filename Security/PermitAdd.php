<?php ob_start(); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>



<?php
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO permits (permit, main, sub1, sub2, descr, active) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['permit'], "text"),
                       GetSQLValueString($_POST['main'], "text"),
                       GetSQLValueString($_POST['sub1'], "text"),
                       GetSQLValueString($_POST['sub2'], "text"),
                        GetSQLValueString($_POST['descr'], "text"),
                      GetSQLValueString($_POST['active'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "PermitMenu.php";
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table width="50%" border="1" bgcolor="#BCFACC">
  <tr>
    <td valign="top">
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
		  <table width="100%" bgcolor="#BCFACC">
			  <tr>
				<td colspan="2"><div align="center">Add Permit</div></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Permit </td>
				<td><input name="permit" type="text" id="permit" autocomplete="off" /></td>
			  </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Menu-Main</td>
				<td><select name="main" id="main" autocomplete="off">
				  <option value="-">-</option>
				  <option value="A">A</option>
				  <option value="B">B</option>
				  <option value="C">C</option>
				  <option value="D">D</option>
				  <option value="E">E</option>
				  <option value="F">F</option>
				  <option value="G">G</option>
				  <option value="H">H</option>
				  <option value="I">I</option>
				  <option value="J">J</option>
				  <option value="Z">Z</option>
				  </select>				</td>
			  </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Menu-Sub1</td>
			    <td>
				  <select name="sub1" id="sub1" autocomplete="off">
			      <option value=""> </option>
			      <option value="1">1</option>
			      <option value="2">2</option>
			      <option value="3">3</option>
			      <option value="4">4</option>
			      <option value="5">5</option>
			      <option value="6">6</option>
			      <option value="7">7</option>
			      <option value="8">8</option>
			      <option value="9">9</option>
			      </select>			    </td>
		      </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Menu-Sub2</td>
			    <td>
				  <select name="sub2" id="sub2" autocomplete="off">
			      <option value=""> </option>
			      <option value="1">1</option>
			      <option value="2">2</option>
			      <option value="3">3</option>
			      <option value="4">4</option>
			      <option value="5">5</option>
			      <option value="6">6</option>
			      <option value="7">7</option>
			      <option value="8">8</option>
			      <option value="9">9</option>
			      </select>			    </td>
		      </tr>
			  <tr>
				<td align="right" nowrap="nowrap">Active</td>
				<td><select name="active" autocomplete="off">
				  <option value="Y">Y</option>
				  <option value="N">N</option>
				  </select>				</td>
			  </tr>
			  <tr>
			    <td align="right" nowrap="nowrap">Description</td>
			    <td><textarea name="descr" id="descr"></textarea></td>
		    </tr>
			  <tr>
				<td><input type="hidden" name="MM_insert" value="form1">
			    <a href="PermitMenu.php">Close</a></td>
				<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Permit" /></td>
			  </tr>
	    </table>   
      </form>
    </td>
  </tr>
</table>
</body>
</html>
