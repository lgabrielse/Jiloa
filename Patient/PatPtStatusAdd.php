<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php
if(!function_exists("GetSQLValueString")) {
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
}
  $saved = "";

	$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
		$editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
  if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "formptnadd")) {

  if($_POST['status'] <> $_POST['statusorig']){  
   $updateSQL = sprintf("UPDATE orders SET status=%s, entryby=%s, entrydt=%s WHERE id=%s",
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['ordid'], "int"));

   mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  }
    if(!empty($_POST['notes'])){
    $insertSQL = sprintf("INSERT INTO patnotes (medrecnum, visitid, notetype, notes, entryby, entrydt) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['medrecnum'], "int"),
                       GetSQLValueString($_POST['visitid'], "int"),
                       GetSQLValueString('PhysioTherapy', "text"),
                       GetSQLValueString($_POST['notes'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "text"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($insertSQL, $swmisconn) or die(mysql_error());
}
  $saved = "true";
}

 $_SESSION['CurrDateTime'] =  date("Y-m-d H:i:s"); 
 ?>

<!--query for an order record-->
<?php if(isset($_GET['ordid'])) {  
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);

mysql_select_db($database_swmisconn, $swmisconn);
	$query_editdel = "SELECT o.id, o.medrecnum, o.visitid, o.rate, o.doctor, status, o.comments, o.urgency, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, Format(f.fee*(o.rate/100),0) as amtdue, o.amtpaid, f.dept, f.section, f.name, f.descr, f.fee, p.lastname, p.firstname FROM orders o join fee f on o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum WHERE f.dept in ('Physiotherapy', 'admin') and o.id = '". $colname_ordid ."'";
$editdel = mysql_query($query_editdel, $swmisconn) or die(mysql_error());
$row_editdel = mysql_fetch_assoc($editdel);
$totalRows_editdel = mysql_num_rows($editdel);
	}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>
</head>
<?php if($saved == "true") {?>
	<body onload="out()">
<?php }?>

<body>

<table align="center">
  <tr>
   <td>
<form id="formptnadd" name="formptnadd" method="post" action="<?php echo $editFormAction; ?>"> 
    <table border="1" align="center"  bgcolor="#F8FDCE">
   
     <tr>
       <td nowrap="nowrap" class="BlueBold_20"><div align="center">
         <input name="button" style="background-color:#f81829" type="button" onclick="out()" value="Close" />
       </div></td>
       <td nowrap="nowrap" class="BlueBold_20"><div align="center">PT NOTES &amp; STATUS </div></td>
       <td nowrap="nowrap">If notes empty, only status is updated.<br />
         Status only updated if changed. </td>
     </tr>
     <tr>
       <td nowrap="nowrap">MRN: <?php echo $row_editdel['medrecnum']; ?></td>
       <td nowrap="nowrap">Name: <?php echo $row_editdel['lastname'].', '.$row_editdel['firstname']; ?></td>
       <td nowrap="nowrap">Order Status: 
         <select name="status">
           <option value="ordered" <?php if (!(strcmp("ordered", $row_editdel['status']))) {echo "selected=\"selected\"";} ?>>Ordered</option>
           <option value="In-Progress" <?php if (!(strcmp("In-Progress", $row_editdel['status']))) {echo "selected=\"selected\"";} ?>>In-Progress</option>
           <option value="Complete" <?php if (!(strcmp("Complete", $row_editdel['status']))) {echo "selected=\"selected\"";} ?>>Complete</option>
         </select>         </td>
     </tr>
     <tr>
		<td nowrap="nowrap">Order Comments:<br />
		  Order #: <?php echo $row_editdel['id']; ?></td>
		<td colspan="2"><textarea name="comments" cols="80" rows="1" id="comments"><?php echo $row_editdel['comments']; ?></textarea></td>
		</tr>
	  <tr>
		<td nowrap="nowrap">PT Notes</td>
		<td nowrap="nowrap"><textarea name="notes" cols="50" rows="2" id="notes"></textarea></td>
	    <td nowrap="nowrap"><input name="Submit" type="submit" id="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Save" />
	      (Status and Notes) </td>
	    </tr>
	  
				<input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $row_editdel['medrecnum']; ?>" />
				<input name="visitid" type="hidden" id="visitid" value="<?php echo $row_editdel['visitid']; ?>; ?>" />
				<input name="statusorig" type="hidden" id="statusorig" value="<?php echo $row_editdel['status']; ?>; ?>" />
				<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				<input name="ordid" type="hidden" id="ordid" value="<?php echo $_GET['ordid']; ?>" />
				<input type="hidden" name="MM_Update" value="formptnadd">
	</table>
   </form>
   </td>
  </tr>
</table>
<script  type="text/javascript">
// var frmvalidator = new Validator("formplv1");
 //frmvalidator.EnableMsgsTogether();

 frmvalidator.addValidation("doctor","dontselect=Select", "Please Select Doctor");
</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function out(){
	opener.location.reload(1); //This updates the data on the calling page
	  self.close();
}
//-->
</script>



</body>
</html>
