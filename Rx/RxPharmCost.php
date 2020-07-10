<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php //session_start()?>

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

  if ((isset($_POST["MM_Update"])) && ($_POST["MM_Update"] == "formrx2")) {

  $updateSQL = sprintf("UPDATE orders SET quant=%s, ofee=%s, amtdue=%s, status=%s, entryby=%s, entrydt=%s, comments=%s WHERE id=%s",
                       GetSQLValueString($_POST['quant'], "text"),
                       GetSQLValueString($_POST['ofee'], "int"),
                       GetSQLValueString($_POST['ofee'], "int"),  
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['entryby'], "text"),
                       GetSQLValueString($_POST['entrydt'], "date"),
                       GetSQLValueString($_POST['comments'], "text"),
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
  $saved = "true";
}

?>

<!--query for an order record-->
<?php if(isset($_GET['ordid'])) {  
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);

mysql_select_db($database_swmisconn, $swmisconn);
$query_editdel = "SELECT o.id, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, o.quant, o.ofee, o.rate, o.doctor, o.billstatus, substr(o.status,1,7) status, o.comments, o.urgency, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, v.pat_type FROM orders o join fee f on o.feeid = f.id join patvisit v on o.visitid = v.id WHERE f.dept = 'pharm' and o.id = '". $colname_ordid ."'";
$editdel = mysql_query($query_editdel, $swmisconn) or die(mysql_error());
$row_editdel = mysql_fetch_assoc($editdel);
$totalRows_editdel = mysql_num_rows($editdel);
	}
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cost Drug</title>
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
<form id="formrx2" name="formrx2" method="post" action="<?php echo $editFormAction; ?>"> 
    <table border="1"  bgcolor="#F8FDCE">
   
     <tr>
       <td colspan="2" nowrap="nowrap" title="Order status will become RxCosted &#10;Billing status will become Due"><div align="center" class="BlueBold_20">COST A DRUG </div></td>
     </tr>
     <tr>
		<td  nowrap="nowrap">Drugs - Patient Type: <?php echo $row_editdel['pat_type']; ?></td>
		<td colspan="2" nowrap="nowrap">
      Urg:
		  <input name="urg" type="text" id="urg" disabled="disabled" value="<?php echo $row_editdel['urgency']; ?>" readonly="readonly"/>
		  Doctor:
      <input name="doctor" type="text" id="doctor" disabled="disabled" value="<?php echo $row_editdel['doctor']; ?>"/>           
      Quant:
      <input name="quant" type="text" id="quant" value="<?php echo $row_editdel['quant']; ?>" size="4" autocomplete="off" />
      Naira:            
      <input name="ofee" type="text" value="<?php echo $row_editdel['ofee']; ?>" size="6" autocomplete="off"/>
<?php if($row_editdel['billstatus'] == 'paylater') {?>
			<span class="flagBlackonOrange">PL</span>
<?php }?>      
      
      
      </td>
		</tr>
	    <tr>
	      <td colspan="2"><div align="center" class="BlueBold_1414">Prescription</div></td>
	    </tr>
	    <tr>
		  <td><input name="itemv" type="text" id="itemv" disabled="disabled" value="<?php echo $row_editdel['item']; ?>" size="30" maxlength="100" /></td>
		  <td nowrap="nowrap" bgcolor="#F8FDCE">
		      <input name="nunitsv" type="text" id="nunitsv" disabled="disabled" value="<?php echo $row_editdel['nunits']; ?>" size="3" maxlength="3" />
<?php if(strpos(' Tablet(s) Capsules(s)', $row_editdel['unit']) > 0) { ?>
		      <input name="unitv" type="text" id="unitv" disabled="disabled" align="center" value="<?php echo $row_editdel['unit']; ?>" size="8" maxlength="8" />
		      <input name="evperiodv" type="text" id="evperiodv" disabled="disabled" value="<?php echo $row_editdel['evperiod']; ?>" size="3" maxlength="3" />
<?php } else {?>
		      <input name="unit2" type="text" id="unit2" disabled="disabled" value="<?php echo $row_editdel['unit']; ?>" size="3" maxlength="3" />
		    Every
		  <input name="every2" type="text" id="every2" disabled="disabled" value="<?php echo $row_editdel['every']; ?>" size="3" />
		  <input name="evperiod2" type="text" id="evperiod2" disabled="disabled" value="<?php echo $row_editdel['evperiod']; ?>" size="3" />
<?php } ?>
		    for
            <input name="fornum" type="text" id="fornum" disabled="disabled" readonly="readonly" value="<?php echo $row_editdel['fornum']; ?>" size="3" />
            <input name="forperiod" type="text" id="forperiod" disabled="disabled" value="<?php echo $row_editdel['forperiod']; ?>" size="3" />		</td>
	  </tr>




	  <tr>
		<td nowrap="nowrap">Order Comments:<br /></td>
		<td nowrap="nowrap"><textarea name="comments" cols="60" rows="3" id="comments"><?php echo $row_editdel['comments']; ?></textarea></td>
	  </tr>
	  <tr>
	    <td nowrap="nowrap">Order #: <?php echo $row_editdel['id']; ?></td>
	    <td nowrap="nowrap"><div align="right">
	      Quant, Naira, and Comments can be changed.
	      <input name="Submit" type="submit" id="Submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Cost Drug" />
	      </div></td>
	    </tr> 
				<input name="status" type="hidden" id="status" value="RxCosted" />
				<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				<input name="entrydt" type="hidden" id="entrydt" value="<?php echo date("Y-m-d H:i:s"); ?>" />
				<input name="ordid" type="hidden" id="ordid" value="<?php echo $_GET['ordid']; ?>" />
				<input type="hidden" name="MM_Update" value="formrx2">
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



</body>
</html>
