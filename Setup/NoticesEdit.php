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
  $updateSQL = sprintf("UPDATE notices SET notice=%s, tooltip=%s, bkgcolor=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['notice'], "text"),
                       GetSQLValueString($_POST['tooltip'], "text"),
                       GetSQLValueString($_POST['bkgcolor'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());

  $updateGoTo = "NoticesList.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
//    $updateGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Edit = "-1";
if (isset($_GET['id'])) {
  $colname_Edit = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_Edit = sprintf("SELECT id, notice, tooltip, bkgcolor FROM notices WHERE id = %s", $colname_Edit);
$Edit = mysql_query($query_Edit, $swmisconn) or die(mysql_error());
$row_Edit = mysql_fetch_assoc($Edit);
$totalRows_Edit = mysql_num_rows($Edit);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" id="form1" name="form1" method="POST">
      <table width="100%" bgcolor="#F8FDCE">
        <tr>
          <td>&nbsp;</td>
          <td nowrap="nowrap" class="subtitlebk">Edit Drop Down Menu Item </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">notice:</div></td>
          <td><input name="notice" type="text" id="notice" value="<?php echo $row_Edit['notice'];?>" size="60" maxlength="60" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14">&nbsp;</td>
          <td>( For New Line in notice field, use &lt;br/&gt; ) </td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">tooltip:</div></td>
          <td><input name="tooltip" type="text" id="tooltip" value="<?php echo $row_Edit['tooltip']; ?>" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">bkgcolor:</div></td>
          	<td><select name="bkgcolor">
          	  <option value="" <?php if (!(strcmp("", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>>Select</option>
          	  <option class="flagBlackonWhite" value="flagBlackonWhite" <?php if (!(strcmp("flagBlackonWhite", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagBlackonWhite</option>
          	  <option class="flagBlackonPink" value="flagBlackonPink" <?php if (!(strcmp("flagBlackonPink", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagBlackonPink</option>
          	  <option class="flagBlackonOrange" value="flagBlackonOrange" <?php if (!(strcmp("flagBlackonOrange", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagBlackonOrange</option>
          	  <option class="flagBlackonYellow" value="flagBlackonYellow" <?php if (!(strcmp("flagBlackonYellow", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagBlackonYellow</option>
          	  <option class="flagWhiteonBlack" value="flagWhiteonBlack" <?php if (!(strcmp("flagWhiteonBlack", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagWhiteonBlack</option>
          	  <option class="flagWhiteonRed" value="flagWhiteonRed" <?php if (!(strcmp("flagWhiteonRed", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagWhiteonRed</option>
          	  <option class="flagWhiteonBlue" value="flagWhiteonBlue" <?php if (!(strcmp("flagWhiteonBlue", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagWhiteonBlue</option>
          	  <option class="flagWhiteonGreen" value="flagWhiteonGreen" <?php if (!(strcmp("flagWhiteonGreen", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagWhiteonGreen</option>
          	  <option class="flagWhiteonBrown" value="flagWhiteonBrown" <?php if (!(strcmp("flagWhiteonBrown", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagWhiteonBrown</option>
          	  <option class="flagWhiteonPurple" value="flagWhiteonPurple" <?php if (!(strcmp("flagWhiteonPurple", $row_Edit['bkgcolor']))) {echo "selected=\"selected\"";} ?>> flagWhiteonPurple</option>
      </select>			</td>
        </tr>
        <tr>
          <td><input name="id" type="hidden" id="id" value="<?php echo $row_Edit['id']; ?>" /></td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Edit DDL Item" />
          </p></td>
        </tr>
      </table>
        
      <input type="hidden" name="MM_update" value="form1">
    </form>
    </td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($Edit);
?>
