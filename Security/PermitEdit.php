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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE permits SET permit=%s, main=%s, sub1=%s, sub2=%s, descr=%s, active=%s WHERE id=%s",
                       GetSQLValueString($_POST['permit'], "text"),
                       GetSQLValueString($_POST['main'], "text"),
                       GetSQLValueString($_POST['sub1'], "text"),
                       GetSQLValueString($_POST['sub2'], "text"),
                       GetSQLValueString($_POST['descr'], "text"),
                       GetSQLValueString($_POST['active'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "PermitMenu.php";
  header(sprintf("Location: %s", $updateGoTo));
}

$MMID_EDIT = "-1";
if (isset($_GET["id"])) {
  $MMID_EDIT = (get_magic_quotes_gpc()) ? $_GET["id"] : addslashes($_GET["id"]);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_EDIT = sprintf("SELECT id, permit, main, sub1, sub2, descr, active FROM permits WHERE id = %s", $MMID_EDIT);
$EDIT = mysql_query($query_EDIT, $swmisconn) or die(mysql_error());
$row_EDIT = mysql_fetch_assoc($EDIT);
$totalRows_EDIT = mysql_num_rows($EDIT);
?>

<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">


<table width="50%" border="1" bgcolor="#F8FDCE">
  <tr>
	<td>
      <form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
		  <table  bgcolor="#F8FDCE">
			  <tr>
				<td colspan="2"><div align="center">Edit Permit</div></td>
			  </tr>
			  <tr>
				<td>Permit</td>
				<td><input name="permit" type="text" id="permit" value="<?php echo $row_EDIT['permit']; ?>" /> 
				  # <?php echo $row_EDIT['id']; ?> </td>
			  </tr>
			  <tr>
				<td>main</td>
				<td><select name="main" id="main" value="<?php echo $row_EDIT['main']; ?>"  >
				  <option value="-" <?php if (!(strcmp("-", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>-</option>
				  <option value="A" <?php if (!(strcmp("A", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>A</option>
				  <option value="B" <?php if (!(strcmp("B", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>B</option>
				  <option value="C" <?php if (!(strcmp("C", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>C</option>
				  <option value="D" <?php if (!(strcmp("D", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>D</option>
				  <option value="E" <?php if (!(strcmp("E", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>E</option>
				  <option value="F" <?php if (!(strcmp("F", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>F</option>
				  <option value="G" <?php if (!(strcmp("G", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>G</option>
				  <option value="H" <?php if (!(strcmp("H", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>H</option>
				  <option value="I" <?php if (!(strcmp("I", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>I</option>
				  <option value="J" <?php if (!(strcmp("J", $row_EDIT['main']))) {echo "selected=\"selected\"";} ?>>J</option>
				  </select>				</td>
			  </tr>
			  <tr>
			    <td>sub1</td>
			    <td>
				  <select name="sub1" id="sub1" value="<?php echo $row_EDIT['sub1']; ?>"  >
				    <option value="" <?php if (!(strcmp("", $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>> </option>
				    <option value="1" <?php if (!(strcmp(1, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>1</option>
				    <option value="2" <?php if (!(strcmp(2, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>2</option>
				    <option value="3" <?php if (!(strcmp(3, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>3</option>
				    <option value="4" <?php if (!(strcmp(4, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>4</option>
				    <option value="5" <?php if (!(strcmp(5, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>5</option>
				    <option value="6" <?php if (!(strcmp(6, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>6</option>
				    <option value="7" <?php if (!(strcmp(7, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>7</option>
				    <option value="8" <?php if (!(strcmp(8, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>8</option>
<option value="9" <?php if (!(strcmp(9, $row_EDIT['sub1']))) {echo "selected=\"selected\"";} ?>>9</option>
			      </select>
			      </select>			    </td>
		      </tr>
			  <tr>
			    <td>sub2</td>
			    <td>
				  <select name="sub2" id="sub2" value="<?php echo $row_EDIT['sub2']; ?>" >
				    <option value="" <?php if (!(strcmp("", $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>> </option>
				    <option value="1" <?php if (!(strcmp(1, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>1</option>
				    <option value="2" <?php if (!(strcmp(2, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>2</option>
				    <option value="3" <?php if (!(strcmp(3, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>3</option>
				    <option value="4" <?php if (!(strcmp(4, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>4</option>
				    <option value="5" <?php if (!(strcmp(5, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>5</option>
				    <option value="6" <?php if (!(strcmp(6, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>6</option>
				    <option value="7" <?php if (!(strcmp(7, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>7</option>
				    <option value="8" <?php if (!(strcmp(8, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>8</option>
<option value="9" <?php if (!(strcmp(9, $row_EDIT['sub2']))) {echo "selected=\"selected\"";} ?>>9</option>
			      </select>			    </td>
		      </tr>
			  <tr>
				<td>Active </td>
				<td><input name="active" type="text" id="active" value="<?php echo $row_EDIT['active']; ?>" /></td>
			  </tr>
			  <tr>
			    <td>Descr</td>
			    <td><textarea name="descr" cols="30" rows="8" id="descr"><?php echo $row_EDIT['descr']; ?></textarea></td>
		    </tr>
			  <tr>
				<td><a href="PermitMenu.php">Close</a>
				  <input type="hidden" name="id" value="<?php echo $row_EDIT['id']; ?>">
				  <input type="hidden" name="MM_update" value="form1"></td><td><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit permit" /></td>
			  </tr>
		</table>   
      </form>
	  </td>
  </tr>
</table>

<br />
<?php
mysql_free_result($EDIT);
?>