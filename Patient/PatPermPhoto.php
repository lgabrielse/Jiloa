<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>PatPermPhoto</title>
</head>

<body>

<?php if (isset($_POST['photo'])) {   //update patient record in database with file name
		if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
			
		$updateSQL = sprintf("UPDATE patperm SET photofile = %s  WHERE medrecnum = %s",
						   GetSQLValueString($_POST['photo'], "text"),
						   GetSQLValueString($_POST['medrecnum'], "int"));
	
		mysql_select_db($database_swmisconn, $swmisconn);
		$Result1 = mysql_query($updateSQL, $swmisconn) or die(mysql_error());
	  
		$insertGoTo = "PatShow1.php";  //?mrn=".$_POST['medrecnum']."&show=../Patient/PatPermEdit.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
//			$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
//			$insertGoTo .= str_replace('LabREedit.php&','LabREview.php&',$_SERVER['QUERY_STRING']); // replace function takes &notepage=PatNotesAdd.php out of $_SERVER['QUERY_STRING'];
		  }
	// echo $noresult;
	  header(sprintf("Location: %s", $insertGoTo));
	 }
  }
else {    // if Photo is not set

?>
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

		echo "Upload Cam file name: " . $_FILES["file"]["name"] . "     ";
		echo "Type: " . $_FILES["file"]["type"] . "     ";
		echo "Size: " . number_format(($_FILES["file"]["size"] / 1024)) . " kB<br>";
//		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
		$newfile = $_SESSION['mrn']."_".date('Y')."-".date('m')."-".date('d')."@".date('H').date('i').date('s').".jpg";
		//echo "newfile ".$newfile."<br>";

//thumb
					//Image Storage Directory
					$img_dir="C:\wamp64\www\Len\DATA_SWMIS/IMGTHUMBS/";  //must be a file address
//--						echo '$img_dir: '.$img_dir."</br>"  ;
					$img_fileName=$newfile;
//--						echo '$img_fileName '.$img_fileName."</br>";
					$img_thumb = $img_dir . $img_fileName;
//--						echo '$img_thumb '.$img_thumb."</br>";
						//Find the height and width of the image
						list($gotwidth, $gotheight, $gottype, $gotattr)= getimagesize($_FILES['file']['tmp_name']); 
						//---------- To create thumbnail of image---------------
						if($extension=="jpg" || $extension=="jpeg" ){
						$src = imagecreatefromjpeg($_FILES['file']['tmp_name']);
						}
						else if($extension=="png"){
						$src = imagecreatefrompng($_FILES['file']['tmp_name']);
						}
						else{
						$src = imagecreatefromgif($_FILES['file']['tmp_name']);
						}
						list($width,$height)=getimagesize($_FILES['file']['tmp_name']);
						
						//This application developed by www.webinfopedia.com
						//Check the image is small that 124px
						if($gotwidth>=124)  // change these $gotwidth and $newwidth numbers to adjust size of thumb
						{
							//if bigger set it to 124
						$newwidth=124;
						}else
						{
							//if small let it be original
						$newwidth=$gotwidth;
						}
						//Find the new height
						$newheight=round(($gotheight*$newwidth)/$gotwidth);
						//Creating thumbnail
						$tmp=imagecreatetruecolor($newwidth,$newheight);
						imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width,$height);
						//Create thumbnail image
						$createImageSave=imagejpeg($tmp,$img_thumb,100);
//--						echo '$tmp'.$tmp."</br>";

// End Thumb (it seems this thumbs must be done before move_uploaded_files or else the temp file goes away)                      }
	
		
		move_uploaded_file($_FILES["file"]["tmp_name"], "C:\wamp64\www\Len\DATA_SWMIS/IMAGES/".$newfile); //must be a file address
?>
	<table width="100%" border="1">
	  <tr>
	<!--	<td>  <img src="<?php //echo '../../imgthumbs/'.$img_fileName ?>" /></td>--> <!--original-->
		<!-- <td>  <img src="<?php // echo '../../DATA_SWMIS/IMGTHUMBS/'.$img_fileName ?>" /></td> -->  <!--must be a URL address-->
	  </tr>
	</table>
<?php
		}
	  }
	  
	else
	  {
	  echo "Invalid file";
	  }
	
	 ?>
	<table width="100%" border="1">
	  <tr>
		<td>  <img src='<?php echo '../../DATA_SWMIS/IMAGES/'.$newfile ?>' /></td>
	  </tr>
	  <tr>
		  <td>
		  <form id="form2" name="form2" method="POST" action="">
			<input name="medrecnum" type="hidden" value="<?php echo $_SESSION['mrn']; ?>" />
			<input name="show" type="hidden" value="../Patient/PatPermEdit.php" />		
			<input name="photo" type="hidden" value="<?php echo $newfile ;?>" />
			<input type="hidden" name="MM_update" value="form2">
			<input name="submit2" type="submit" value="SAVE"/>
		  </form>
		  </td>
	  </tr>
	</table>
	
	<?php  }
	else {  // no $_POST['show']?>
	<table width="100%" border="1">
	<!--<form name="form1" id="form1" method="post" action="">
	  <tr>
		<td><label for="file">Filename:</label>
			<input name="mrn" type="hidden" value="<?php //echo $_SESSION['mrn']; ?>" />
			<input name="show" type="hidden" value="../Patient/PatPermEdit.php" />		
			<input type="file" name="file" id="file"><br>
			<input type="submit" name="submit" value="Submit">-->
	<form action="" method="post" enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit" value="Submit">
	</form>
		</td>
	  </tr>
	</form>
	</table>
<?php }
	  }   ?>

</body>
</html>
