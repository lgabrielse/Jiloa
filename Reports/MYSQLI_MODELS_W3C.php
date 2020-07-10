<!--MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  -->

<!--SELECT  SELECT  SELECT  SELECT  SELECT  SELECT  SELECT  SELECT  SELECT  SELECT-->  
<?php  //http://www.w3schools.com/php/php_mysql_select.asp

$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$result = mysqli_query($con,"SELECT * FROM Users order by lastname, firstname");
while($row = mysqli_fetch_array($result)) {
  echo $row['firstname'] . " " . $row['lastname'];
  echo "<br>";
}
mysqli_close($con);
?> 
<?php
$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM Users");

echo "<table border='1'>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>";

while($row = mysqli_fetch_array($result)) {
  echo "<tr>";
  echo "<td>" . $row['firstname'] . "</td>";
  echo "<td>" . $row['lastname'] . "</td>";
  echo "</tr>";
}

echo "</table>";

mysqli_close($con);
?> 
<!--MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  MYSQLI I I  -->
<!--INSERT  INSERT  INSERT  INSERT  INSERT  INSERT  INSERT  INSERT-->  
<?php
$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_query($con,"INSERT INTO users (firstname, lastname, login)
VALUES ('Peter', 'Griffin', 'pete')");

mysqli_query($con,"INSERT INTO users (firstname, lastname, login)
VALUES ('Glenn', 'Quagmire', 'glen')");

mysqli_close($con);
?> 

<!--INSERT FROM FORM  INSERT FROM FORM  INSERT FROM FORM  INSERT FROM FORM  INSERT FROM FORM  INSERT FROM FORM-->  
<?php
$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// escape variables for security
$firstname = mysqli_real_escape_string($con, $_POST['firstname']);
$lastname = mysqli_real_escape_string($con, $_POST['lastname']);
$login = mysqli_real_escape_string($con, $_POST['login']);

$sql="INSERT INTO users (firstname, lastname, login)
VALUES ('$firstname', '$lastname', '$login')";

if (!mysqli_query($con,$sql)) {
  die('Error: ' . mysqli_error($con));
}
echo "1 record added";

mysqli_close($con);
?> 


<!--UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  UPDATE  -->
<?php
$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_query($con,"UPDATE users SET login = 'Petros'
WHERE firstname='Peter' AND lastname='Griffin'");

mysqli_close($con);
?> 

<!--DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE  DELETE --> 
<?php
$con=mysqli_connect("localhost","root","jiloa7","swmisbethany");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

mysqli_query($con,"DELETE FROM users WHERE LastName='Griffin'");

mysqli_close($con);
?> 
<!--See Also
http://www.phpmysqlitutorials.com/category/mysqli/     for prepared statement
http://www.pontikis.net/blog/how-to-use-php-improved-mysqli-extension-and-why-you-should   
http://codular.com/php-mysqli


-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link href="../../CSS/Level3_1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<p>&nbsp;</p>
<p>
  Firstname: 
  <input type="text" name="firstname">
  Lastname: 
  <input type="text" name="lastname">
  Login: 
  <input type="text" name="login">
  <input type="submit">
</p>
</body>
</html> 


</body>
</html>
