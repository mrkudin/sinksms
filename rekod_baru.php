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
  $insertSQL = sprintf("INSERT INTO barang (no_tracking, tarikh_sampai, penerima, no_tel, id, jenis, status) VALUES (%s, %s, %s, %s, %s, %s, 'BELUM DITUNTUT')",
                       GetSQLValueString($_POST['tracking'], "text"),
                       GetSQLValueString($_POST['tarikh'], "text"),
                       GetSQLValueString($_POST['penerima'], "text"),
                       GetSQLValueString($_POST['notel'], "text"),
                       GetSQLValueString($_POST['id'], "text"),
                       GetSQLValueString($_POST['jenis'], "text"),
					   GetSQLValueString($_POST['status'], "text"));

  mysql_select_db($database_admin_login, $admin_login);
  $Result1 = mysql_query($insertSQL, $admin_login) or die(mysql_error());
  
  $_SESSION['notel'] = $_POST['notel'];
  $_SESSION['tracking'] = $_POST['tracking'];

  $insertGoTo = "berjaya.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_admin_login, $admin_login);
$query_simpan = "SELECT * FROM barang";
$simpan = mysql_query($query_simpan, $admin_login) or die(mysql_error());
$row_simpan = mysql_fetch_assoc($simpan);
$totalRows_simpan = mysql_num_rows($simpan);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
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
#clear {
	background-image:url(images/button_kosongkan.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:137px;
	border:none;
	text-indent:-999em;
}
#clear:hover {
	opacity:0.7;
	filter: alpha(opacity=70);
}
#simpan {
	background-image:url(images/button_simpan.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:107px;
	border:none;
	text-indent:-999em;
}
#simpan:hover {
	opacity:0.7;
	filter: alpha(opacity=70);
}
.box {
    border-radius: 4px;
}
.box:focus {
	background-color: lightblue;
}
.style2 {font-family: Arial, Helvetica, sans-serif}
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
      <td height="28" bgcolor="#CCCCCC"><div align="center"><span class="style2">| <a href="menu_admin.php">Menu Admin</a> | <a href="<?php echo $logoutAction ?>">Log Keluar</a> |</span></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6" class="style2"><br /><div align="center">KEMASUKKAN REKOD BARANGAN BARU</div><br /></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF"><br /><form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
        <table width="645" border="0">
          <tr>
            <td bgcolor="#CCCCCC" width="329">Nombor <em>tracking:</em></td>
            <td width="300"><input class="box" name="tracking" type="text" id="tracking" placeholder="Cth: EP123456789MY" size="50" maxlength="13" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC">Tarikh sampai:</td>
            <td><input class="box" name="tarikh" type="date" id="tarikh" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC">Nama penerima:</td>
            <td><input class="box" name="penerima" type="text" id="penerima" size="50" maxlength="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC">Nombor telefon:</td>
            <td><input class="box" name="notel" type="text" id="notel" placeholder="Cth: 0123456789" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC">Nombor matrik/staff:</td>
            <td><input class="box" name="id" type="text" id="id" placeholder="*Tinggalkan kosong sekiranya tiada" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC">Jenis barangan</td>
            <td><input class="box" name="jenis" type="text" id="jenis" placeholder="Cth: Bungkusan (rujuk pada resit bungkusan)" size="50" /></td>
          </tr>
        </table>
        <p>
          <input type="reset" name="clear" id="clear" value="Kosongkan" />
          <input type="submit" name="simpan" id="simpan" value="Simpan" />
        </p>
        <input type="hidden" name="MM_insert" value="form1" />
      </form><br /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p><strong></strong><span class="style4">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</span></p>
</div>
</body>
</html>
<?php
mysql_free_result($simpan);
?>
