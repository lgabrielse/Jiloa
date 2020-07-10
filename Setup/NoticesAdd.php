<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
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
  $insertSQL = sprintf("INSERT INTO notices (notice, tooltip, bkgcolor, entryby, entrydt) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['notice'], "text"),
                       GetSQLValueString($_POST['tooltip'], "text"),
                       GetSQLValueString($_POST['bkgcolor'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "NoticesList.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//    $insertGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $insertGoTo));
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%" bgcolor="#BCFACC">
        <tr>
          <td>&nbsp;</td>
          <td nowrap="nowrap" class="subtitlebk">Add Patient Notice Item </td>
        </tr>

        <tr>
          <td colspan="2" class="BlackBold_14"><p class="Black_1011">width of words determine width of display<br />
            ( For new line in notice field, use &lt;br/&gt; ) <br />
          e.g GOOD &lt;br/&gt; DAY <br />
            ( For new line in tooltip field, use '& #10;' ) <br />
          e.g GOOD '& #10;' DAY  (without quotes or spaces)</p></td>
          </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Notice:</div></td>
          <td><input name="notice" type="text" id="notice" />           </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">tooltip:</div></td>
          <td><input name="tooltip" type="text" id="tooltip" autocomplete="off" /></td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">bkgcolor:</div></td>
          	<td><select name="bkgcolor">
				<option>Select</option>
				<option class="flagBlackonWhite" value="flagBlackonWhite"> flagBlackonWhite</option>
				<option class="flagBlackonPink" value="flagBlackonPink"> flagBlackonPink</option>
				<option class="flagBlackonOrange" value="flagBlackonOrange"> flagBlackonOrange</option>
				<option class="flagBlackonYellow" value="flagBlackonYellow"> flagBlackonYellow</option>
				<option class="flagWhiteonBlack" value="flagWhiteonBlack"> flagWhiteonBlack</option>
				<option class="flagWhiteonRed" value="flagWhiteonRed"> flagWhiteonRed</option>
				<option class="flagWhiteonBlue" value="flagWhiteonBlue"> flagWhiteonBlue</option>
				<option class="flagWhiteonGreen" value="flagWhiteonGreen"> flagWhiteonGreen</option>
				<option class="flagWhiteonBrown" value="flagWhiteonBrown"> flagWhiteonBrown</option>
				<option class="flagWhiteonPurple" value="flagWhiteonPurple"> flagWhiteonPurple</option>
      </select>			</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Notice Item" />
          </p></td>
        </tr>
      </table>
	    <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
        <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
        <input type="hidden" name="MM_insert" value="form1">
    </form>
    </td>
  </tr>
</table>
</body>
</html>
<?php
ob_end_flush();
?>
