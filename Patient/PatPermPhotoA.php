<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PatPermPhoto</title>
</head>

<body>
<?php if(isset($_FILES["file"]["name"])) {

$allowedExts = array("gif", "jpeg", "jpg", "png"); // validate file extension
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
if ((($_FILES["file"]["type"] == "image/gif")  // validate file type
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 50000)         // check for file size - photos should be 25 - 30 Kb
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    echo "Upload: " . $_FILES["file"]["name"] . "<br>";
    echo "Type: " . $_FILES["file"]["type"] . "<br>";
    echo "Size: " . number_format(($_FILES["file"]["size"] / 1024)) . " kB<br>";
    echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
	
      move_uploaded_file($_FILES["file"]["tmp_name"],
      "C:/wamp/www/Len/images/" . $_FILES["file"]["name"]);
      echo "Stored in: " . "C:/wamp/www/Len/images/" . $_FILES["file"]["name"];
    }
  }
else
  {
  echo "Invalid file";
  }

 ?>
<table width="100%" border="1">
  <tr>
    <td> Do something  <?php echo $_GET['file'] ?></td>
    <td>  <img src="<?php echo "../../images/".$_GET['file']?>" /></td>
  </tr>
  <tr>
	  <td>
	  <form id="form1" name="form2" method="get" action="">
		<input name="mrn" type="hidden" value="<?php echo $_SESSION['mrn']; ?>" />
		<input name="show" type="hidden" value="../Patient/PatPermEdit.php" />		
	    <input name="photo" type="hidden" value="<?php echo "../../images/".$_GET['file']?>" />
	    <input name="submit2" type="submit" style="background-color:aqua; border-color:blue; color:black;text-align: center;border-radius: 4px;" value="SAVE"/>
	  </form>
	  </td>
  </tr>
</table>

<?php }
else {  // no $_POST['file']?>
<table width="100%" border="1">
<form name="form1" id="form1" method="post" action="">
  <tr>
    <td><label for="file">Filename:</label>
		<input name="mrn" type="hidden" value="<?php echo $_SESSION['mrn']; ?>" />
		<input name="show" type="hidden" value="../Patient/PatPermEdit.php" />		
		<input type="file" name="file" id="file"><br>
		<input type="submit" name="submit" value="Submit">
	</td>
  </tr>
</form>
</table>
<?php }?>

</body>
</html>
