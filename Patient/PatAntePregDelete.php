<?php   $pt = "Patient Preg Delete"; ?>
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

if ((isset($_POST['pregid'])) && ($_POST['pregid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM anpreg WHERE id=%s",
                       GetSQLValueString($_POST['pregid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteGoTo = "PatShow1.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= str_replace('pge=PatAntePregDelete.php','pge=PatAntePregView.php',$_SERVER['QUERY_STRING']);
  }
  header(sprintf("Location: %s", $deleteGoTo));
}
?>

<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
$colname_preg = "15";
if (isset($_GET['pregid'])) {
  $colname_preg = (get_magic_quotes_gpc()) ? $_GET['pregid'] : addslashes($_GET['pregid']);
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_preg = sprintf("SELECT id, medrecnum, DATE_FORMAT(lmp,'%%b %%d, %%Y') lmp, DATE_FORMAT(edd,'%%b %%d, %%Y') edd, DATE_FORMAT(ussedd,'%%b %%d, %%Y') ussedd, DATE_FORMAT(firstvisit,'%%b %%d, %%Y') firstvisit, obg, obp, specpoints, HistPatHeart, HistPatChest, HistPatKidney, HistPatBldTransf, HistPatPPH, HistPatOperations, HistPatOther, HistFamMultPreg, HistFamTb, HistFamHypertens, HistFamHeart, HistFamOther, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM anpreg WHERE id = %s ORDER BY id ASC", $colname_preg);
$preg = mysql_query($query_preg, $swmisconn) or die(mysql_error());
$row_preg = mysql_fetch_assoc($preg);
$totalRows_preg = mysql_num_rows($preg);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>
  <body>
  
    <table width="80%" bgcolor="#FBD0D7">
<form action="" method="post">      <tr>
  <td nowrap="nowrap">MRN:<br />
    <?php echo $_SESSION['mrn'] ?></td>
        <td nowrap="nowrap" class="BlackBold_14">Pregnancy<br /> 
      # <?php echo $row_preg['id']; ?>
	  <input name="pregid" type="hidden" value="<?php echo $row_preg['id']; ?>" /></td>
        <td nowrap="nowrap">LMP:<br />
      <input name="lmp" type="text" id="lmp" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['lmp']; ?>"/></td>
        <td nowrap="nowrap">EDD:<br />
      <input name="edd" type="text" id="edd" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['edd']; ?>"/></td>
        <td nowrap="nowrap">USS EDD:<br />
      <input name="ussedd" type="text" id="ussedd" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['ussedd']; ?>"/></td>
        <td nowrap="nowrap">FirstVisit:<br />
      <input name="firstvisit" type="text" id="firstvisit" size="8" maxlength="10" readonly="readonly" value="<?php echo $row_preg['firstvisit']; ?>"/></td>
        <td nowrap="nowrap">OB Hist:<br />
          G
        <input name="obg" type="text" id="obg" size="1" maxlength="3" readonly="readonly" value="<?php echo $row_preg['obg']; ?>"/> 
        P
      <input name="obp" type="text" id="obp" size="1" maxlength="3" readonly="readonly" value="<?php echo $row_preg['obp']; ?>"/></td>
        <td nowrap="nowrap" >&nbsp;</td>
        <td nowrap="nowrap">Special<br />
        Points:</td>
      <td nowrap="nowrap"><textarea name="specpoints" cols="40" rows="1" readonly="readonly" id="specpoints"><?php echo $row_preg['specpoints']; ?></textarea></td>
    <td nowrap="nowrap"><div align="center"><a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php&act=ante&pge=PatAntePregView.php">Close</a></div></td>
        </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap" class="BlackBold_14"><div align="center">Patient<br />
      History:</div></td>
    <td nowrap="nowrap">Heart<br />
      Disease      
      <input name="HistPatHeart" type="text" id="HistPatHeart" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatHeart']; ?>"/></td>
    <td nowrap="nowrap">Chest<br />
      Disease 
      <input name="HistPatChest" type="text" id="HistPatChest" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatChest']; ?>"/></td>
    <td nowrap="nowrap">Kidney<br />
      Disease 
      <input name="HistPatKidney" type="text" id="HistPatKidney" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatKidney']; ?>"/></td>
    <td nowrap="nowrap">Blood<br />
      Transf'ns 
      <input name="HistPatBldTransf" type="text" id="HistPatBldTransf" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatBldTransf']; ?>"/></td>
    <td nowrap="nowrap">PPH
      <input name="HistPatPPH" type="text" id="HistPatPPH" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatPPH']; ?>"/></td>
    <td nowrap="nowrap">Oper-<br />
      ations 
      <input name="HistPatOperations" type="text" id="HistPatOperations" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistPatOperations']; ?>"/></td>
    <td nowrap="nowrap">Other:</td>
    <td nowrap="nowrap"><textarea name="HistPatOther" cols="40" rows="1" readonly="readonly" id="HistPatOther"><?php echo $row_preg['HistPatOther']; ?></textarea></td>
      <td nowrap="nowrap"><input type="submit" name="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete" /></td>
    </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap" class="BlackBold_14"><div align="center">Family<br />
      History:</div></td>
    <td nowrap="nowrap" >Multiple<br />
      Preg's
        <input name="HistFamMultPreg" type="text" id="HistFamMultPreg" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamMultPreg']; ?>" /></td>
    <td nowrap="nowrap" >Tuberc-<br />
      ulosis
      <input name="HistFamTb" type="text" id="HistFamTb" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamTb']; ?>" /></td>
    <td nowrap="nowrap" >Hyper-<br />
      Tension 
      <input name="HistFamHypertens" type="text" id="HistFamHypertens" size="2" readonly="readonly" value="<?php echo $row_preg['HistFamHypertens']; ?>" maxlength="3" /></td>
    <td nowrap="nowrap" >Heart<br />
      Disease      
      <input name="HistFamHeart" type="text" id="HistFamHeart" size="2" maxlength="3" readonly="readonly" value="<?php echo $row_preg['HistFamHeart']; ?>" /></td>
    <td nowrap="nowrap" >&nbsp;</td>
    <td nowrap="nowrap" >&nbsp;</td>
    <td nowrap="nowrap" >Other<br />
      Fam. Hist:</td>
    <td nowrap="nowrap" ><textarea name="HistFamOther" cols="40" rows="1" readonly="readonly" id="HistFamOther"> <?php echo $row_preg['HistFamOther']; ?></textarea></td>
    <td nowrap="nowrap">&nbsp;</td>
    </tr>
</form>
  </table>
     <p>&nbsp;</p>
  </body>
</html>