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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formpappe")) {
  $updateSQL = sprintf("UPDATE anprevpregs SET medrecnum=%s, pregid=%s, name=%s, dob=%s, pregdur=%s, plptext=%s, birthweight=%s, babystatus=%s, gender=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['pregid'], "int"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['dob'], "date"),
                       GetSQLValueString($_POST['pregdur'], "text"),
                       GetSQLValueString($_POST['plptext'], "text"),
                       GetSQLValueString($_POST['birthweight'], "text"),
                       GetSQLValueString($_POST['babystatus'], "text"),
                       GetSQLValueString($_POST['gender'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
     $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
   $updateGoTo .= str_replace('pge=PatAntePrevPregEdit.php','pge=PatAntePrevPregView.php',$_SERVER['QUERY_STRING']); 
   }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_prevpreg = "-1";
if (isset($_GET['prevpregid'])) {
  $colname_prevpreg = (get_magic_quotes_gpc()) ? $_GET['prevpregid'] : addslashes($_GET['prevpregid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_prevpreg = sprintf("SELECT id, medrecnum, pregid, name, dob, pregdur, plptext, birthweight, babystatus, gender, entryby, entrydt FROM anprevpregs WHERE id = %s", $colname_prevpreg);
$prevpreg = mysql_query($query_prevpreg, $swmisconn) or die(mysql_error());
$row_prevpreg = mysql_fetch_assoc($prevpreg);
$totalRows_prevpreg = mysql_num_rows($prevpreg);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
    <link rel="stylesheet" href="../../jquery-ui-1.11.2.custom/jquery-ui.css" />   
	<script src="../../jquery-1.11.1.js"></script>
    <script src="../../jquery-ui-1.11.2.custom/jquery-ui.min.js"></script>
	<script>
	$(document).ready(function(){
    $.datepicker.setDefaults({ 
     dateFormat: 'yy-mm-dd'
    });
	 dateFormat: "yy-mm-dd";
          $( "#dob" ).datepicker();
       });
   </script>
    <link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="80%" bgcolor="#F8FDCE">
  <tr>
    <td>
	<form name="formpappe" id="formpappe" method="POST" action="<?php echo $editFormAction; ?>">
	<table width="100%" bgcolor="#F8FDCE">
	  <tr>
		<td nowrap="nowrap" class="Black_14"><div align="center">MRN</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center" class="BlackBold_10">Preg<br />Record</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Name:</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">DOB</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Preg.Duration</div></td>
		<td nowrap="NOWRAP" class="Black_14"><div align="center">Pregnancy, Labour, Pueperium</div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Birth Weight </div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Baby Status </div></td>
		<td nowrap="nowrap" class="Black_14"><div align="center">Gender</div></td>
		<td><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a></div></td>
	  </tr>
	  <tr>
		<td title="Last Entry By: <?php echo $row_prevpreg['entryby']; ?>&10#;Last Entry Date: <?php echo $row_prevpreg['entrydt']; ?>"><input name="medrecnum" type="text" id="medrecnum" size="5" maxlength="9" readonly="readonly" value="<?php echo $_SESSION['mrn'] ?>" /></td>
		<td><input name="pregid" type="text" id="pregid" value="<?php echo $row_prevpreg['pregid']; ?>" size="5" maxlength="9" readonly="readonly"/></td>
		<td><input name="name" type="text" id="name" value="<?php echo $row_prevpreg['name']; ?>" /></td>
		<td><input name="dob" type="text" id="dob" value="<?php echo $row_prevpreg['dob']; ?>" size="10" maxlength="12" /></td>
		<td><input name="pregdur" type="text" id="pregdur" value="<?php echo $row_prevpreg['pregdur']; ?>" size="15" maxlength="30" /></td>
		<td nowrap="nowrap"><textarea name="plptext" cols="30" rows="1" id="plptext"><?php echo $row_prevpreg['plptext']; ?></textarea></td>
		<td nowrap="nowrap"><input name="birthweight" type="text" id="birthweight" value="<?php echo $row_prevpreg['birthweight']; ?>" size="5" maxlength="10" />
		  kg</td>
		<td title="a)A: Alive&#10;b)FBS: Fresh Still Birth&#10;c)MSB: Macerated Still Birth&#10;d)INND: Infant Neonatal Death" >
      <select name="babystatus" id="babystatus">
		  <option value="A" <?php if (!(strcmp("A", $row_prevpreg['babystatus']))) {echo "selected=\"selected\"";} ?>>A</option>
<option value="FSB" <?php if (!(strcmp("FSB", $row_prevpreg['babystatus']))) {echo "selected=\"selected\"";} ?>>FSB</option>
<option value="MSB" <?php if (!(strcmp("MSB", $row_prevpreg['babystatus']))) {echo "selected=\"selected\"";} ?>>MSB</option>
		    <option value="INND" <?php if (!(strcmp("INND", $row_prevpreg['babystatus']))) {echo "selected=\"selected\"";} ?>>INND</option>
		</select>		</td>
		<td><select name="gender" id="gender">
		  <option value="Male" <?php if (!(strcmp("Male", $row_prevpreg['gender']))) {echo "selected=\"selected\"";} ?>>Male</option>
		  <option value="Female" <?php if (!(strcmp("Female", $row_prevpreg['gender']))) {echo "selected=\"selected\"";} ?>>Female</option>
		    <option value="Miscarriage" <?php if (!(strcmp("Miscarriage", $row_prevpreg['gender']))) {echo "selected=\"selected\"";} ?>>Miscarriage</option>
		</select>		</td>
		<td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit" />
		        <input type="hidden" name="id" id="id" value="<?php echo $row_prevpreg['id']; ?>"/>
	  			<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
	  			<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i"); ?>" />
	  	</td>
	  </tr>
	</table>
	<input type="hidden" name="MM_update" value="formpappe">
	</form>
	</td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($prevpreg);
?>
