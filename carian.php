<?php require_once('Connections/admin_login.php'); ?>
<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "adminlogin.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "adminlogin.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

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
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
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

$maxRows_Carian = 10;
$pageNum_Carian = 0;
if (isset($_GET['pageNum_Carian'])) {
  $pageNum_Carian = $_GET['pageNum_Carian'];
}
$startRow_Carian = $pageNum_Carian * $maxRows_Carian;

$colname_Carian = "-1";
if (isset($_GET['cari'])) {
  $colname_Carian = $_GET['cari'];
}
mysql_select_db($database_admin_login, $admin_login);
$query_Carian = sprintf("SELECT * FROM barang WHERE no_tracking LIKE %s", GetSQLValueString($colname_Carian, "text"));
$query_limit_Carian = sprintf("%s LIMIT %d, %d", $query_Carian, $startRow_Carian, $maxRows_Carian);
$Carian = mysql_query($query_limit_Carian, $admin_login) or die(mysql_error());
$row_Carian = mysql_fetch_assoc($Carian);

if (isset($_GET['totalRows_Carian'])) {
  $totalRows_Carian = $_GET['totalRows_Carian'];
} else {
  $all_Carian = mysql_query($query_Carian);
  $totalRows_Carian = mysql_num_rows($all_Carian);
}
$totalPages_Carian = ceil($totalRows_Carian/$maxRows_Carian)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
#senarai {
	background-image:url(images/button_senarai-rekod.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:169px;
	border:none;
	text-indent:-999em;
}
#senarai:hover {
	opacity:0.7;
	filter: alpha(opacity=70);
}
a:link {
  color: black;
  background-color: transparent;
  text-decoration: none;
}
a:visited {
  color: black;
  background-color:transparent;
}
a:hover {
  color: red ;
  background-color: transparent ;
}
.style1 {font-family: Arial, Helvetica, sans-serif}
.style4 {color: #FFFFFF}
-->
</style>
</head>

<body><br />
<div align="center">
  <table width="734" border="1">
    <tr>
      <td><?php include 'header.php' ?></td>
    </tr>
    <tr>
      <td height="30" bgcolor="#CCCCCC"><div align="center"><span class="style1">| <a href="menu_admin.php">Menu Admin</a> | <a href="<?php echo $logoutAction ?>">Log Keluar</a> |</span></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br /><div align="center" class="style1">Hasil Carian:</div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><div align="center">
        <table width="521" border="0">
          <tr>
            <td width="255" bgcolor="#CCCCCC"><div align="center"><strong>Nombor <em>tracking:</em></strong></div></td>
            <td width="256" bgcolor="#CCCCCC"><div align="center"><strong>Nama penerima:</strong></div></td>
          </tr>
          <tr>
            <?php do { ?>
            <td><div align="center"><a href="details.php?tracking=<?php echo $row_Carian['no_tracking']; ?>"><?php echo $row_Carian['no_tracking']; ?></a></div></td>
            <td><div align="center"><?php echo $row_Carian['penerima']; ?></div></td>
            <?php } while ($row_Carian = mysql_fetch_assoc($Carian)); ?>
          </tr>
        </table>
        <p>
          <input type="button" name="senarai" id="senarai" value="Senarai Rekod" onclick="window.location='view_rekod.php'" />
        </p>
      </div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p><span class="style4">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</span></p>
</div>
</body>
</html>
<?php
mysql_free_result($Carian);
?>
