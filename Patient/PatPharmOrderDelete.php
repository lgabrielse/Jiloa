<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php //require_once('../../Connections/swmisconn.php'); ?>
<?php //session_start() ?>

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
   $_SESSION['CurrDateTime'] =  date("Y-m-d H:i:s");

  $saved = "";

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

 if ((isset($_POST["MM_Delete"])) && ($_POST["MM_Delete"] == "formrx3")) {
	
  $deleteSQL = sprintf("DELETE FROM orders WHERE id = %s",
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $deleteSQL = sprintf("DELETE FROM ipmeds WHERE orderid = %s",
                       GetSQLValueString($_POST['ordid'], "int"));

  mysql_select_db($database_swmisconn, $swmisconn);
  $Result1 = mysql_query($deleteSQL, $swmisconn) or die(mysql_error());

  $saved = "true";

}
  ?>

<!--query for an order record-->
<?php
if(isset($_GET['ordid'])) {
  $colname_ordid = (get_magic_quotes_gpc()) ? $_GET['ordid'] : addslashes($_GET['ordid']);
	} else {
		if(isset($_POST['ordid'])) {
  		$colname_ordid = (get_magic_quotes_gpc()) ? $_POST['ordid'] : addslashes($_POST['ordid']);
		}
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_editdel = "SELECT o.id, o.medrecnum, o.visitid, o.feeid, o.item, o.nunits, o.unit, o.every, o.evperiod, o.fornum, o.forperiod, o.quant, o.ofee, o.rate, o.doctor, substr(o.status,1,7) status, o.comments, substr(o.urgency,1,1) urg, DATE_FORMAT(o.entrydt,'%d%b%y %H:%i') entrydt, o.entryby, Format(f.fee*(o.rate/100),0) as amtdue, o.amtpaid, f.dept, f.section, f.name, f.descr, f.fee FROM orders o, fee f WHERE o.feeid = f.id and f.dept in ('pharm', 'admin') and o.id = '". $colname_ordid ."'";
$editdel = mysql_query($query_editdel, $swmisconn) or die(mysql_error());
$row_editdel = mysql_fetch_assoc($editdel);
$totalRows_editdel = mysql_num_rows($editdel);

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" src="../../javascript_form/gen_validatorv4.js" type="text/javascript" xml:space="preserve"></script>
<script language="JavaScript" type="text/JavaScript">
//<!--
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
<form id="formrx3" name="formrx3" method="post" action="<?php echo $editFormAction; ?>"> 
    <table width="500" border="1" align="center" bgcolor="#FBD0D7">
   
     <tr>
       <td colspan="2" nowrap="nowrap"><div align="center" class="BlueBold_20">DELETE DRUG  </div></td>
       </tr>
     <tr>
		<td nowrap="nowrap">Drugs</td>
		<td nowrap="nowrap">Urg:
			<input name="urgency" type="text" id="urgency" disabled="disabled" value="<?php echo $row_editdel['urg']; ?>" size="3"/>

			<input type="hidden" name="status" value="ordered"/>Doctor:
			<input name="doctor" type="text" id="doctor" disabled="disabled" value="<?php echo $row_editdel['doctor']; ?>" size="10"/>
            Naira:
            <input name="ofee" type="text" disabled="disabled" value="<?php echo $row_editdel['ofee']; ?>" size="3" />
			Quant:
			<input name="quant" type="text" id="quant" disabled="disabled" value="<?php echo $row_editdel['quant']; ?>" size="3"/>
			
			</td>
		</tr>
	    <tr>
	      <td colspan="2"><div align="center" class="BlueBold_1414">Prescription</div></td>
	    </tr>
	    <tr>
		  <td> <input name="item" type="text" id="item" disabled="disabled" value="<?php echo $row_editdel['item']; ?>" size="30"/></td>
		  <td> <input name="nunits" type="text" id="nunits" disabled="disabled" value="<?php echo $row_editdel['nunits']; ?>" size="3" maxlength="3" />
		    Every
			  <input name="every" type="text" id="every" disabled="disabled" value="<?php echo $row_editdel['every']; ?>" size="3" />
			  <input name="evperiod" type="text" id="evperiod" disabled="disabled" value="<?php echo $row_editdel['evperiod']; ?>" size="8" />
		    for
			  <input name="fornum" type="text" id="fornum" disabled="disabled" value="<?php echo $row_editdel['fornum']; ?>" size="3" />
			  <input name="forperiod" type="text" id="forperiod" disabled="disabled" value="<?php echo $row_editdel['forperiod']; ?>" size="8" />
		  </td>
	  </tr>
	  <tr>
		<td nowrap="nowrap">Order Comments:</td>
		<td nowrap="nowrap"><textarea name="comments" cols="60" rows="3" id="comments" disabled="disabled"><?php echo $row_editdel['comments']; ?></textarea></td>
	    </tr>
			  <tr>
	    <td nowrap="nowrap">Order #: <?php echo $row_editdel['id']; ?></td>
	    <td nowrap="nowrap"><div align="right">
	      <input name="Submit2" type="submit" id="Submit2" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Delete Order" />
	      </div></td>
	    </tr>
	  
				<input name="medrecnum" type="hidden" id="medrecnum" value="<?php echo $_SESSION['mrn']; ?>" />
				<input name="visitid" type="hidden" id="visitid" value="<?php echo $_SESSION['vid']; ?>" />
				<input name="entryby" type="hidden" id="entryby" value="<?php echo $_SESSION['user']; ?>" />
				<input name="entrydt" type="hidden" id="entrydt" value="<?php echo $_SESSION['CurrDateTime']; ?>" />
				<input name="ordid" type="hidden" id="ordid" value="<?php echo $_GET['ordid']; ?>" />
				<input name="MM_Delete" type="hidden" value="formrx3">
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
