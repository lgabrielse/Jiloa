


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>Setup hours for alert expected within __ hours... </p>
<p>In this report:</p>
<p>Patient visit within ___________ days/hours<br />
  Time since Registration  _________ hours/min
    and rate &lt;&gt; 0 and not paid <br />
Time since Visit entered _________ hours/min 
and rate &lt;&gt; 0 and not paid<br />
Time since Lab Order ___________  hours/min 
and rate &lt;&gt; 0 and not paid<br />
Time since PT Order ___________  hours/min 
and rate &lt;&gt; 0 and not paid<br />
Time since SUR Order ___________  hours/min 
and rate &lt;&gt; 0 and not paid<br />
Time since Rad Order ___________  hours/min 
and rate &lt;&gt; 0 and not paid<br />
Time since Drug Order ___________  hours/min 
and rate &lt;&gt; 0 and not paid</p>

<?php
$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$colname_daysback = 60;
$result = mysqli_query($con,"SELECT p.medrecnum, p.lastname, p.firstname, p.dob, p.gender, p.ethnicgroup, v.id, v.visitdate, v.status, v.pat_type, v.location, v.entrydt vdt, o.rate, o.status, o.entrydt odt, o.amtpaid, f.dept, f.name  FROM patperm p join patvisit v on p.medrecnum = v.medrecnum join orders o on p.medrecnum = o.medrecnum join fee f on o.feeid = f.id Where visitdate >= (SYSDATE() - INTERVAL " .($colname_daysback + 1)." DAY) and amtpaid is null order by o.entrydt");

echo "<table border='1'>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>";

while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo " <td>" . $row['firstname'] . "</td>";
  echo " <td>" . $row['lastname'] . "</td>";
  echo "</tr>";
}

echo "</table>";

mysqli_close($con);
?> 

</body>
</html>
