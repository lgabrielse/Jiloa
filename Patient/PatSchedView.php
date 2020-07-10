<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="80%">
  <tr>
    <td><form id="form1" name="form1" method="post" action="">
      <table width="100%">
        <tr>
          <td> <a href="PatShow1.php?mrn=<?php echo $_SESSION['mrn']; ?>&vid=<?php echo $_SESSION['vid']; ?>&visit=PatVisitView.php">Close</a></td>
          <td align="center" nowrap="nowrap"><a href="../mrbs-1.4.10/web/index.php" target="_blank" class="subtitlebl">Scheduling</a> </td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Click  on Scheduling to open a new tab for scheduling. This program has its own database, programs, and security, but is linked into the information system.<br />
            Since it is in a separate window, users can have MIS and Scheduling open at the same time. Data is not shared between these windows(except for login name)<br />
              If Patient name is to entered in the schedule, highlight and copy it in MIS page and paste it in the Schedule page. <br /></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
    <td height="20" align="center" >
	<?php if(allow(29,2) == 1) { ?>
<a href="../mrbs-1.4.10/web/index.php" target="_blank">Scheduling </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://mrbs.sourceforge.net/">                MRBS</a>
	<?php } ?>
</td>
          <td>&nbsp;</td>
        </tr>
      </table>
        </form>
    </td>
  </tr>
</table>

</body>
</html>
