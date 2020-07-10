<?php require_once('../../Connections/swmisconn.php'); ?>
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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "visitsect")) {
echo 'zzz';
	if (isset($_POST['visit'])) {
	   $visits = $_POST['visit'];
       $N = count($visits);
//    echo("You selected $N order(s): ");
		for($i=0; $i < $N; $i++) {
			echo $N;
			echo 'visit: '.$i.''.$visits[$i].'<br>';
		
//	mysql_select_db($database_swmisconn, $swmisconn);
//	$query_Fee = sprintf("SELECT fee from fee where id = '".$visits[$i]."'");
//	$Fee = mysql_query($query_Fee, $swmisconn) or die(mysql_error());
//	$row_Fee = mysql_fetch_assoc($Fee);
//	$totalRows_Fee = mysql_num_rows($Fee);

		}
	}
exit;
}


$col_mrn = "-1";
if (isset($_REQUEST['mrn'])) {
  $col_mrn = $_REQUEST['mrn'];
}
mysql_select_db($database_swmisconn, $swmisconn);
$query_HistVists = sprintf("SELECT id, medrecnum, visitdate, pat_type, location, urgency, discharge, diagnosis FROM patvisit WHERE medrecnum = %s ORDER BY visitdate ASC", GetSQLValueString($col_mrn, "int"));
$HistVists = mysql_query($query_HistVists, $swmisconn) or die(mysql_error());
$row_HistVists = mysql_fetch_assoc($HistVists);
$totalRows_HistVists = mysql_num_rows($HistVists);
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PatHist2Menu</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css">
<script src="../../js/jquery1.4.4/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('.check:button').toggle(function(){
        $('input:checkbox').attr('checked','checked');
        $(this).val('uncheck all')
    },function(){
        $('input:checkbox').removeAttr('checked');
        $(this).val('check all');        
    })
}) 
</script>

</head>

<body>
<p></p>
<H1 align="center">Patient History Report</H1>
<p></p>
<p></p>
<table align="center">
<!--PatHist2ReportTests.php is document of my testing for this project-->
<!--<form id="visit_sect" method="POST" action="PatHist2ReportTests.php?mrn=<?php echo $col_mrn ?>"-->
<form id="visit_sect" method="POST" action="PatHist2Report.php?mrn=<?php echo $col_mrn ?>">
	<tr>
  	<td rowspan="2" valign="top">
      <table border="1" cellpadding="2" cellspacing="2" style="border-collapse: collapse;">
        <tr>
          <td colspan="4" align="center" class="GreenBold_24" >Visit Selection</td>
        </tr>
        <tr>
          <td></td>
          <td align="center" nowrap="nowrap">Visit Date*</td>
          <td align="center" nowrap="nowrap">Location*</td>
          <td align="center" nowrap="nowrap">Diagnosis*</td>
        </tr>
        <?php do { //date_create needed for date_format
          $date=date_create($row_HistVists['visitdate']);
					$disch=date_create($row_HistVists['discharge']);
        ?>
          <tr>
            <td bgcolor="#FFFFFF"> <!--this checkbox with name="visit[]" becomes an array of visit ids used after submit to tell pgm above which visits to display-->     
            <input type="checkbox" name="visit[]" unchecked value="<?php echo $row_HistVists['id']; ?>"></td>
            <td bgcolor="#FFFFFF" title="Visit ID: <?php echo $row_HistVists['id']; ?>&#10;Mrn: <?php echo $row_HistVists['medrecnum']; ?>"><?php echo date_format($date,'D M d, Y'); ?></td>
            <td bgcolor="#FFFFFF" title="PatType: <?php echo $row_HistVists['pat_type']; ?>"><?php echo $row_HistVists['location']; ?></td>
            <td bgcolor="#FFFFFF" Title="Urgency: <?php echo $row_HistVists['urgency']; ?>&#10; Discharged: <?php echo date_format($disch,'D M d, Y'); ?>"><?php echo $row_HistVists['diagnosis']; ?></td>
          </tr>
          <?php } while ($row_HistVists = mysql_fetch_assoc($HistVists)); ?>
      </table>
    </td>
    <td nowrap><input type="button" class="check" value="check all" /></td>
    <td rowspan="2" valign="top">
      <table  border="1" cellpadding="2" cellspacing="2" style="border-collapse: collapse;">
      	<tr>
        	<td colspan="2" valign="top" class="GreenBold_24">Page Selection</td>
        </tr>
					<?php  // pages array
            $pages = array(
                  0 => "notes",
                  1 => "orders",
                  2 => "labs",
                  3 => "drugs",
                  4 => "ante",
				  5 => 'followup',
                  6 => "fluids",
                  7 => "surg"
              );  
      // Iterating through the product array
           foreach($pages as $item){ ?>
        <tr>
					<td bgcolor="#FFFFFF"> <input name="page[]" type="checkbox" value="<?php echo strtolower($item); ?>" unchecked /></td>
          <td bgcolor="#FFFFFF"><?php echo strtolower($item); ?></td>
        </tr>
                <?php } ?>
          
      </table>
    </td>
  </tr>
	<tr>
    <td nowrap>Select Visit(s)<br>and Page(s)<br>And Click<br>Submit.</td>
	  </tr>
  <tr>
  <td>&nbsp;</td>
  <td>&nbsp;</td>
  	<td>
      <input type="hidden" name="mrn" value="<?php echo $col_mrn ?>" />		
      <input type="hidden" name="MM_insert" value="visitsect" />		
		  <input type="submit" name="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="Submit" /></td>
	</tr>
</form>
</table>  
</body>
</html>
<?php
mysql_free_result($HistVists);
?>
