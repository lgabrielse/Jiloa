<?php function recurse_copy($src, $dst) {

$dir = opendir($src);
$result = ($dir === false ? false : true);

if ($result !== false){
    $result = @mkdir($dst);

    if ($result === true){
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' ) && $result) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $result = recurseCopy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    $result = copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir);
    }
}

return $result;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<?php recurse_copy("C://Source", "C://S_Destination")
?>
Result: <?php $result ?> 

</body>
</html>
