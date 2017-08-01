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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO `admin` (`user`, pass, nama) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['pass'], "text"),
                       GetSQLValueString($_POST['nama'], "text"));

  mysql_select_db($database_admin_login, $admin_login);
  $Result1 = mysql_query($insertSQL, $admin_login) or die(mysql_error());

  $insertGoTo = "adminlogin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_admin_login, $admin_login);
$query_daftar_admin = "SELECT `user`, pass, nama FROM `admin`";
$daftar_admin = mysql_query($query_daftar_admin, $admin_login) or die(mysql_error());
$row_daftar_admin = mysql_fetch_assoc($daftar_admin);
$totalRows_daftar_admin = mysql_num_rows($daftar_admin);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title><style type="text/css">
<!--
.style1 {font-family: Arial, Helvetica, sans-serif}
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
#daftar {
	background-image:url(images/button_daftar.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:33px;
	width:92px;
	border:none;
	text-indent:-999em;
}
#daftar:hover {
	opacity:0.7;
}
#kosongkan {
	background-image:url(images/button_kosongkan.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:137px;
	border:none;
	text-indent:-999em;
}
#kosongkan:hover {
	opacity:0.7;
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
.box {
    border-radius: 4px;
}
.box:focus {
	background-color: lightblue;
}
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
      <td bgcolor="#ADD8E6"><BR /><div align="center" class="style1">MASUKKAN BUTIRAN DAFTAR PENTADBIR SEPERTI BERIKUT:</div>
      <BR /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><BR /><br /><form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
        <table width="577" border="0" align="center">
          <tr>
            <td bgcolor="#CCCCCC"><strong>ID:</strong></td>
            <td width="300">              <div align="right">
              <input class="box" name="username" type="text" id="username" size="50" />            
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Kata Laluan:</strong></td>
            <td><div align="right">
                <input class="box" name="pass" type="password" id="pass" size="50" />
            </div></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nama Pentadbir:</strong></td>
            <td><div align="right">
                <input class="box" name="nama" type="text" id="nama" size="50" />
            </div></td>
          </tr>
        </table>
        <p align="center">
          <input type="reset" name="kosongkan" id="kosongkan" value="Reset" />
          <input type="submit" name="daftar" id="daftar" value="Submit" />
        </p>
        <div align="center">
          <input type="hidden" name="MM_insert" value="form1" />
          </div>
      </form><BR /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p><span class="style4">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</span></p>
</div>
<div align="center"></div>
</body>
</html>
<?php
mysql_free_result($daftar_admin);
?>
