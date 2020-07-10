<?php //require_once('../../Connections/swmisconn.php'); ?><?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php require_once($_SERVER['DOCUMENT_ROOT'].$_SESSION['sysconn']); ?>

<?php // Visit data
mysql_select_db($database_swmisconn, $swmisconn);
$query_visitlist = sprintf("SELECT id, medrecnum, DATE_FORMAT(visitdate,'%%d-%%b-%%Y') visitdate, pat_type, location, urgency, DATE_FORMAT(discharge,'%%d-%%b-%%Y') discharge, visitreason, diagnosis, weight, height, entryby, DATE_FORMAT(entrydt,'%%d-%%b-%%Y') entrydt FROM patvisit WHERE id = %s", $visitid);
$visitlist = mysql_query($query_visitlist, $swmisconn) or die(mysql_error());
$row_visitlist = mysql_fetch_assoc($visitlist);
$totalRows_visitlist = mysql_num_rows($visitlist);

?>
      <table width="100%" border="0">
        <tr>
          <td colspan="2" nowrap="nowrap" class="BlackBold_11"><br />
            Urgency:</td>
		  <td nowrap="nowrap" class="BlackBold_11"><div align="center">Visit Date:<br />
		  Location:</div></td>
          <td class="BlackBold_11"><div align="center" class="Black_11">Disch. Date: <br />
          Patient Type: </div></td>
          <td nowrap="nowrap" class="BlackBold_11"><div align="center">Height:<br />
            Weight:<br />
          </div></td>
          <td class="BlackBold_11"><div align="center">Visit Reason </div></td>
          <td class="BlackBold_11"><div align="center">Diagnosis:</div></td>
        </tr>

        <tr>
          <td nowrap="nowrap"><div align="center">VisitID</div></td>
          <td nowrap="nowrap" class="BlueBold_14" title="Entry Date: <?php echo $row_visitlist['entrydt']; ?>&#10;Entry By: <?php echo $row_visitlist['entryby']; ?>"><div align="center"><?php echo $row_visitlist['id']; ?></div></td>

            <td nowrap="nowrap"><input name="visitdate" type="text" id="visitdate" readonly="readonly" value="<?php echo $row_visitlist['visitdate']; ?>" size="12" /></td>
            <td><input name="discharge" type="text" id="discharge" readonly="readonly" value="<?php echo $row_visitlist['discharge']; ?>" size="15" /></td>
            <td nowrap="nowrap"><input name="height" type="text" id="height" readonly="readonly" value="<?php echo $row_visitlist['height']; ?>" size="12" /></td>
            <td><textarea name="visitreason" cols="30" rows="1" id="visitreason" readonly="readonly"><?php echo $row_visitlist['visitreason']; ?></textarea></td>
            <td><textarea name="diagnosis" cols="30" rows="1" id="diagnosis" readonly="readonly"><?php echo $row_visitlist['diagnosis']; ?></textarea></td>
        </tr>
        <tr>
            <td colspan="2"><input name="urgency" type="text" id="urgency" readonly="readonly" value="<?php echo $row_visitlist['urgency']; ?>" size="8" /></td>

              <td><input name="location" type="text" id="location" readonly="readonly" value="<?php echo $row_visitlist['location']; ?>" size="15" /></td>
              <td><input name="pat_type" type="text" id="pat_type readonly="readonly"" value="<?php echo $row_visitlist['pat_type']; ?>" size="15" /></td>
            <td nowrap="nowrap"><input name="weight" type="text" id="weight" readonly="readonly" value="<?php echo $row_visitlist['weight']; ?>" size="12" /></td>
        </tr>
     <table>
