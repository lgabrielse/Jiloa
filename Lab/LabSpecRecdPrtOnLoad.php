<?php  $pt = "Spec Coll Msg"; ?>
<?php session_start()?> 
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>
<?php
 $_SESSION['user']= $_GET['user'];
 $_SESSION['mrn']= $_GET['mrn'];

	$colname_ordlist = "-1";
if (isset($_GET['ordlist'])) {
  $colname_ordlist = (get_magic_quotes_gpc()) ? $_GET['ordlist'] : addslashes($_GET['ordlist']);
  
mysql_select_db($database_swmisconn, $swmisconn);
$query_SpecRecd = "SELECT p.hospital, p.lastname, p.firstname, p.othername, o.id ordid, DATE_FORMAT(o.entrydt,'%d-%b-%Y') entrydt, left(f.dept,3) dept, f.section, f.name, s.name spname FROM orders o join fee f on o.feeid = f.id join patperm p on o.medrecnum = p.medrecnum join specimens s on f.specid = s.id WHERE o.id in ($colname_ordlist) ORDER BY o.entrydt ASC";
$SpecRecd = mysql_query($query_SpecRecd, $swmisconn) or die(mysql_error());
$row_SpecRecd = mysql_fetch_assoc($SpecRecd);
$totalRows_SpecRecd = mysql_num_rows($SpecRecd);
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
<script>
      function Print()
         {
          if (document.all) 
             {
               WebBrowser1.ExecWB(6, 6) //use 6, 1 to prompt the print dialog or 6, 6 to omit it; 
                WebBrowser1.outerHTML = "";
             }
         else
            {
             window.print();
             }
         }
</script>


</head>

<object id="WebBrowser1" width="0" height="0" 
    classid="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2">
    </object>
<body onload="Print()">


<table align="center">
  <tr>
    <td align="center" >
      <a href="#" onclick="Print()">Printed Specimens Collected</a> </td>
  </tr>
  <tr>
    <td align="center" class="Black_18">MRN: <?php echo $_GET['mrn']; ?> &nbsp;&nbsp; Specimens Collected</td>
  </tr>
  <tr>
    <td align="center" nowrap="nowrap" class="Black_30"><a href="LabSpecList.php?mrn=<?php echo $_GET['mrn']; ?>"><?php echo $row_SpecRecd['hospital'] ?> Hospital</a></td>
  </tr>
  <tr>
    <td align="center" class="Black_18"><?php echo $row_SpecRecd['lastname'] ?>,<?php echo $row_SpecRecd['firstname'] ?>(<?php echo $row_SpecRecd['othername'] ?>)</td>
  </tr>
  <tr>
    <td align="center" class="Black_24"><?php echo date('d-M-Y H:i') ?></td>
  </tr>
<?php $total = 0;
 do { 
	$total = $total + 1;?>
    <tr>
      <td class="Black_18"><div align="left">Ord#<?php echo $row_SpecRecd['ordid']; ?>&nbsp;&nbsp;&nbsp;<?php echo $row_SpecRecd['spname']; ?></div></td>
  </tr>
  <tr>
    <td align="left" class="Black_18">&nbsp;&nbsp;&nbsp;
	  <?php echo $row_SpecRecd['dept']; ?>
      <?php echo $row_SpecRecd['section']; ?>
	  <?php echo $row_SpecRecd['name']; ?></td>
  </tr>
  <tr>
    <td align="center" class="Black_18">________________________</td>
    </tr>
<?php } while ($row_SpecRecd = mysql_fetch_assoc($SpecRecd)); ?>
  <tr>
  </tr>
  <tr>
    <td align="center" class="Black_18"><?php echo $_GET['user']; ?> Received: <?php echo $total ?></td>
  </tr>
</table>

</body>
</html>
<?php
mysql_free_result($SpecRecd);
?>
