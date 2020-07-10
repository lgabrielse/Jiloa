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
  $insertSQL = sprintf("INSERT INTO specimens (name, method, container, minvolume, preservative, viablelimit, storage, instructions, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['method'], "text"),
                       GetSQLValueString($_POST['container'], "text"),
                       GetSQLValueString($_POST['minvolume'], "text"),
                       GetSQLValueString($_POST['preservative'], "text"),
                       GetSQLValueString($_POST['viablelimit'], "text"),
                       GetSQLValueString($_POST['storage'], "text"),
                       GetSQLValueString($_POST['instructions'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());

  $insertGoTo = "Specimens.php";
//  if (isset($_SERVER['QUERY_STRING'])) {
//    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//    $insertGoTo .= $_SERVER['QUERY_STRING'];
//  }
  header(sprintf("Location: %s", $insertGoTo));
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SpecimenAdd</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>


<table width="50%" align="center">
  <tr>
    <td><form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="100%"  bgcolor="#BCFACC">
        <tr>
          <td>&nbsp;</td>
          <td class="subtitlebk">Add specimen </td>
        </tr>

        <tr>
          <td class="BlackBold_14"><div align="right">Name:</div></td>
          <td><input name="name" type="text" id="name" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Method:</div></td>
          <td><input name="method" type="text" id="method" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Container:</div></td>
          <td><input name="container" type="text" id="container" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Minvolume:</div></td>
          <td><input name="minvolume" type="text" id="minvolume" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Preservative:</div></td>
          <td><input name="preservative"type="text"  id="preservative"  size="50" maxlength="50" ></textarea></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">ViableLimit:</div></td>
          <td><input name="viablelimit" type="text" id="viablelimit" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Storage:</div></td>
          <td><input name="storage" type="text" id="storage" size="50" maxlength="50" /></td>
        </tr>
        <tr>
          <td class="BlackBold_14"><div align="right">Instructions:</div></td>
          <td><textarea name="instructions" id="instructions"></textarea></td>
        </tr>
        <tr>
          <td><input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
            <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" /></td>
          <td><p>
            <input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Add Specimen" />
          </p></td>
        </tr>
      </table>
        <input type="hidden" name="MM_insert" value="form1">
    </form>
    </td>
  </tr>
</table>

</body>
</html>

