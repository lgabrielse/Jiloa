<?php //ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) {
   session_start() ; }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php // require_once('../../Connections/swmisconn.php'); ?>
<?php $pt = "Surg Count";  ?>
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
  $saved = ""; // set variable to blank 
?>

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
// if begin is submitted, save the message in the database
if (isset($_POST['MM_update']) && $_POST['MM_update']  == 'form1' && $_POST['edit'] == 'begin') {
$total = 0;
  $UpdateSQL = sprintf("Update surgcountbegin set swabs=%s, arteryforceps=%s, aliceforceps=%s, toothdissectingforceps=%s, plaindisectingforceps=%s, abdominalmops=%s, scissors=%s, bladehandles=%s, blades=%s, grayaimitage=%s, langendeckretractor=%s, doyensretractor=%s, needleholders=%s, needles=%s, entryby=%s, entrydt=%s WHERE surgid =%s",
                       GetSQLValueString($_POST['swabs'], "int"),
                       GetSQLValueString($_POST['arteryforceps'], "int"),
                       GetSQLValueString($_POST['aliceforceps'], "int"),
                       GetSQLValueString($_POST['toothdissectingforceps'], "int"),
                       GetSQLValueString($_POST['plaindisectingforceps'], "int"),
                       GetSQLValueString($_POST['abdominalmops'], "int"),
                       GetSQLValueString($_POST['scissors'], "int"),
                       GetSQLValueString($_POST['bladehandles'], "int"),
                       GetSQLValueString($_POST['blades'], "int"),
                       GetSQLValueString($_POST['grayaimitage'], "int"),
                       GetSQLValueString($_POST['langendeckretractor'], "int"),
                       GetSQLValueString($_POST['doyensretractor'], "int"),
                       GetSQLValueString($_POST['needleholders'], "int"),
                       GetSQLValueString($_POST['needles'], "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['surgid'], "int"));
											 
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());

$begintotal = $_POST['swabs'] + $_POST['arteryforceps'] + $_POST['aliceforceps'] + $_POST['toothdissectingforceps'] + $_POST['plaindisectingforceps'] + $_POST['abdominalmops'] + $_POST['scissors'] + $_POST['bladehandles'] + $_POST['blades'] + $_POST['grayaimitage'] + $_POST['langendeckretractor'] + $_POST['doyensretractor'] + $_POST['needleholders']+  $_POST['needles'];

  $UpdateSQL = sprintf("Update surgery set begincount = %s WHERE id =%s",
                       GetSQLValueString($begintotal, "int"),
										   GetSQLValueString($_POST['surgid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());

  $saved = "true"; // set variable to blank 
} ?>

<?php
if (isset($_POST['MM_update']) && $_POST['MM_update']  == 'form1' && $_POST['edit'] == 'end') {
$total = 0;
  $UpdateSQL = sprintf("Update surgcountend set swabs=%s, arteryforceps=%s, aliceforceps=%s, toothdissectingforceps=%s, plaindisectingforceps=%s, abdominalmops=%s, scissors=%s, bladehandles=%s, blades=%s, grayaimitage=%s, langendeckretractor=%s, doyensretractor=%s, needleholders=%s, needles=%s, entryby=%s, entrydt=%s WHERE surgid =%s",
                       GetSQLValueString($_POST['swabs'], "int"),
                       GetSQLValueString($_POST['arteryforceps'], "int"),
                       GetSQLValueString($_POST['aliceforceps'], "int"),
                       GetSQLValueString($_POST['toothdissectingforceps'], "int"),
                       GetSQLValueString($_POST['plaindisectingforceps'], "int"),
                       GetSQLValueString($_POST['abdominalmops'], "int"),
                       GetSQLValueString($_POST['scissors'], "int"),
                       GetSQLValueString($_POST['bladehandles'], "int"),
                       GetSQLValueString($_POST['blades'], "int"),
                       GetSQLValueString($_POST['grayaimitage'], "int"),
                       GetSQLValueString($_POST['langendeckretractor'], "int"),
                       GetSQLValueString($_POST['doyensretractor'], "int"),
                       GetSQLValueString($_POST['needleholders'], "int"),
                       GetSQLValueString($_POST['needles'], "int"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
										   GetSQLValueString($_POST['surgid'], "int"));
											 
  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());

$endtotal = $_POST['swabs'] + $_POST['arteryforceps'] + $_POST['aliceforceps'] + $_POST['toothdissectingforceps'] + $_POST['plaindisectingforceps'] + $_POST['abdominalmops'] + $_POST['scissors'] + $_POST['bladehandles'] + $_POST['blades'] + $_POST['grayaimitage'] + $_POST['langendeckretractor'] + $_POST['doyensretractor'] + $_POST['needleholders']+  $_POST['needles'];

  $UpdateSQL = sprintf("Update surgery set endcount = %s WHERE id =%s",
                       GetSQLValueString($endtotal, "int"),
										   GetSQLValueString($_POST['surgid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($UpdateSQL, $swmisconn) or die(mysql_error());

  $saved = "true"; // set variable to blank 
} ?>


<?php 
$colname_count = "-1";
if (isset($_GET['surgid'])) {
  $colname_count = $_GET['surgid'];
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_begcount = sprintf("SELECT id, surgid, swabs, arteryforceps, aliceforceps, toothdissectingforceps, plaindisectingforceps, abdominalmops, scissors, bladehandles, blades, grayaimitage, langendeckretractor, doyensretractor, needleholders, needles, entryby, entrydt FROM surgcountbegin WHERE surgid = %s", GetSQLValueString($colname_count, "int"));
$begcount = mysql_query($query_begcount, $swmisconn) or die(mysql_error());
$row_begcount = mysql_fetch_assoc($begcount);
$totalRows_begcount = mysql_num_rows($begcount);
 
mysql_select_db($database_swmisconn, $swmisconn);
$query_endcount = sprintf("SELECT id, surgid, swabs, arteryforceps, aliceforceps, toothdissectingforceps, plaindisectingforceps, abdominalmops, scissors, bladehandles, blades, grayaimitage, langendeckretractor, doyensretractor, needleholders, needles, entryby, entrydt FROM surgcountend WHERE surgid = %s", GetSQLValueString($colname_count, "int"));
$endcount = mysql_query($query_endcount, $swmisconn) or die(mysql_error());
$row_endcount = mysql_fetch_assoc($endcount);
$totalRows_endcount = mysql_num_rows($endcount);

?>

<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Count Surgical Items</title>
<link href="../CSS/Level3_1.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
	<?php }?>


<body>

<?php if($_GET['edit'] == 'begin') {?>
<table align="center"  bgcolor="#99ff99">
  <tr>
    <td><input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></td>
  </tr>
  <tr>
    <td align="center">BEFORE SURGERY</td>
  </tr>
  <tr>
    <td align="center" title="Id: <?php echo $row_begcount['id']; ?>&#10;Surgid: <?php echo $row_begcount['surgid']; ?>&#10;Entryby: <?php echo $row_begcount['entryby']; ?>&#10;Entrydt: <?php echo $row_begcount['entrydt']; ?>" ><span class="BlueBold_16">Update Surgical Items Count</span></td>
  </tr>
<?php }?>

<?php if($_GET['edit'] == 'end') { ?>
<table align="center"  bgcolor="#ff9900">
  <tr>
    <td><input name="button" style="background-color:#f81829" type="button" onClick="out()" value="Close" /></td>
  </tr>
  <tr>
    <td align="center">AFTER SURGERY</td>
  </tr>
    <tr>
    <td align="center" title="Id: <?php echo $row_endcount['id']; ?>&#10;Surgid: <?php echo $row_endcount['surgid']; ?>&#10;Entryby: <?php echo $row_endcount['entryby']; ?>&#10;Entrydt: <?php echo $row_endcount['entrydt']; ?>" ><span class="BlueBold_16">Update Surgical Items Count</span></td>
  </tr>

<?php }?>

  <tr>
    <td>
      <table>
<?php if($_GET['edit'] == 'begin') {?>
   <tr>  
     <td>Item</td>
     <td>Begin</td>
     <td></td>
   </tr>
<?php }?>
<?php if($_GET['edit'] == 'end') { ?>
  <tr>  
    <td>Item</td>
    <td>Begin</td>
    <td>End</td>
  </tr>
<?php }?>

      <form method="post" name="form1" action="<?php echo $editFormAction; ?>">
         <tr>  
          <td>Swabs</td>
<?php if($_GET['edit'] == 'end') {?>
          <td><input name="swabs" type="text" size="1" maxlength="3" disabled style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['swabs']; ?>"></td>
          <td><input name="swabs" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['swabs']; ?>"></td>
<?php } else {?>
          <td><input name="swabs" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['swabs']; ?>"></td>
					<td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Arteryforceps</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="arteryforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['arteryforceps']; ?>"></td>
            <td><input name="arteryforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['arteryforceps']; ?>"></td>
<?php } else {?>
            <td><input name="arteryforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['arteryforceps']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Aliceforceps</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="aliceforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['aliceforceps']; ?>"></td>
            <td><input name="aliceforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['aliceforceps']; ?>"></td>
<?php } else {?>
            <td><input name="aliceforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['aliceforceps']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Toothdissectingforceps</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="toothdissectingforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['toothdissectingforceps']; ?>"></td>
            <td><input name="toothdissectingforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['toothdissectingforceps']; ?>"></td>
<?php } else {?>
            <td><input name="toothdissectingforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['toothdissectingforceps']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Plaindisectingforceps</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="plaindisectingforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['plaindisectingforceps']; ?>"></td>
            <td><input name="plaindisectingforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['plaindisectingforceps']; ?>"></td>
<?php } else {?>
            <td><input name="plaindisectingforceps" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['plaindisectingforceps']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Abdominalmops</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="abdominalmops" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['abdominalmops']; ?>"></td>
            <td><input name="abdominalmops" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['abdominalmops']; ?>"></td>
<?php } else {?>
            <td><input name="abdominalmops" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['abdominalmops']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Scissors</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="scissors" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['scissors']; ?>"></td>
            <td><input name="scissors" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['scissors']; ?>"></td>
<?php } else {?>
            <td><input name="scissors" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['scissors']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Bladehandles</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="bladehandles" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['bladehandles']; ?>"></td>
            <td><input name="bladehandles" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['bladehandles']; ?>"></td>
<?php } else {?>
            <td><input name="bladehandles" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['bladehandles']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Blades</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="blades" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['blades']; ?>"></td>
            <td><input name="blades" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['blades']; ?>"></td>
<?php } else {?>
            <td><input name="blades" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['blades']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Grayaimitage</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="grayaimitage" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['grayaimitage']; ?>"></td>
            <td><input name="grayaimitage" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['grayaimitage']; ?>"></td>
<?php } else {?>
            <td><input name="grayaimitage" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['grayaimitage']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Langendeckretractor</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="langendeckretractor" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['langendeckretractor']; ?>"></td>
            <td><input name="langendeckretractor" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['langendeckretractor']; ?>"></td>
<?php } else {?>
            <td><input name="langendeckretractor" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['langendeckretractor']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Doyensretractor</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="doyensretractor" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['doyensretractor']; ?>"></td>
            <td><input name="doyensretractor" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['doyensretractor']; ?>"></td>
<?php } else {?>
            <td><input name="doyensretractor" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['doyensretractor']; ?>"></td>
					  <td></td>
<?php }?>
       </tr>
       <tr>  
         <td>Needleholders</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="needleholders" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['needleholders']; ?>"></td>
            <td><input name="needleholders" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['needleholders']; ?>"></td>
<?php } else {?>
            <td><input name="needleholders" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['needleholders']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
        <tr>  
          <td>Needles</td>
<?php if($_GET['edit'] == 'end') {?>
            <td><input name="needles" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" disabled value="<?php echo $row_begcount['needles']; ?>"></td>
            <td><input name="needles" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_endcount['needles']; ?>"></td>
<?php } else {?>
            <td><input name="needles" type="text" size="1" maxlength="3" style="text-align:center;" autocomplete="off" value="<?php echo $row_begcount['needles']; ?>"></td>
					  <td></td>
<?php }?>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table>
        <tr>
          <td colspan="2"></td>
        </tr>
        <tr>
<?php if($_GET['edit'] == 'end') {?>
          <td nowrap align="center"  class="BlueBold_18">Begin Total</td>
          <td nowrap align="center"  class="BlueBold_18">End Total</td>
<?php }?> 
 
        </tr>
        <tr>
<?php if($_GET['edit'] == 'begin') {?>
          <td nowrap align="left"  class="BlueBold_18">Begin Total</td>
          <td align="right"><input name="begincount" type="text" style="background-color:#fffdda; text-align:center; font-size:24px;"  size="5" maxlength="5"  value=<?php echo $_GET['begin'] ?>></td>
<?php }?> 
         
<?php if($_GET['edit'] == 'end') {?>
          <td><input  name="begincount" type="text" style="background-color:#ececec; text-align:center; font-size:18px;" size="5" maxlength="5" readonly value=<?php echo $_GET['begin'];?>></td>
          <td><input  name="endcount" type="text" style="background-color:#fffdda; text-align:center; font-size:24px;" size="5" maxlength="5" value=<?php echo $_GET['end'];?>></td>
<?php }?>          
        </tr>
        <tr>
<?php if($_GET['edit'] == 'begin') {?>
					<td align="center"><input name="submit" type="submit" style="background-color:aqua; border-color:blue; color:black; text-align: center; border-radius:4px;" value="Save"></td>
					<td>&nbsp;</td>         
<?php }?>          

<?php if($_GET['edit'] == 'end') {?>         
					<td>&nbsp;</td>         
					<td align="center"><input name="submit" type="submit" style="background-color:aqua; border-color:blue; color:black; text-align: center; border-radius:4px;"value="Save"></td>
<?php }?>          
					<input name="edit" type="hidden" value="<?php echo $_GET['edit']; ?>">
          <input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
          <input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
          <input name="surgid" type="hidden" value="<?php echo $_GET['surgid']; ?>">
					<input type="hidden" name="MM_update" value="form1" />
        </tr>
				</form>
      </table>
    </td>
  </tr>
</table>

</body>
<script language="JavaScript" type="text/JavaScript">
//function openBrWindow(theURL,winName,features) { //v2.0
//  window.open(theURL,winName,features);
//}
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
</script>

</html>
<?php
mysql_free_result($begcount);
mysql_free_result($endcount);
?>
