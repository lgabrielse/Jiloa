<?php if (session_status() == PHP_SESSION_NONE) {
    session_start(); }?>
<?php $colname_mrn= "-1";
if (isset($_SESSION['mrn'])) {
  $colname_mrn = $_SESSION['mrn'];
}
$colname_vid = "-1";
if (isset($_SESSION['vid'])) {
  $colname_vid = $_SESSION['vid'];
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Anesthesia Update</title>
</head>

<body>
<h1>Anesthesia Update</h1>
<a href="PatShow1.php?mrn=<?php echo $colname_mrn ?>&vid=<?php echo $colname_vid ?>&visit=PatVisitView.php&act=lab&pge=PatSurgEdit.php">Update Surgery</a>
<?php echo $_SERVER['QUERY_STRING']; ?>
</body>
</html>