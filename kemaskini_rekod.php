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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE barang SET penerima=%s, no_tel=%s, id=%s, jenis=%s, status=%s, tarikh_terima=%s WHERE no_tracking=%s",
                       GetSQLValueString($_POST['nama'], "text"),
                       GetSQLValueString($_POST['no_tel'], "text"),
                       GetSQLValueString($_POST['matrik'], "text"),
                       GetSQLValueString($_POST['jenis'], "text"),
                       GetSQLValueString($_POST['status'], "text"),
                       GetSQLValueString($_POST['tarikh_tuntut'], "text"),
                       GetSQLValueString($_POST['tracking'], "text"));

  mysql_select_db($database_admin_login, $admin_login);
  $Result1 = mysql_query($updateSQL, $admin_login) or die(mysql_error());

  $updateGoTo = "kemaskini_berjaya.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_admin_login, $admin_login);
$kemaskini = $_GET['tracking'];
$query_kemaskini = "SELECT * FROM barang WHERE no_tracking = '$kemaskini'";
$kemaskini = mysql_query($query_kemaskini, $admin_login) or die(mysql_error());
$row_kemaskini = mysql_fetch_assoc($kemaskini);
$totalRows_kemaskini = mysql_num_rows($kemaskini);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
#batal {
	background-image:url(images/button_batal.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:77px;
	border:none;
	text-indent:-999em;
}
#batal:hover {
	opacity:0.7;
}
#kemaskini {
	background-image:url(images/button_kemaskini.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:116px;
	border:none;
	text-indent:-999em;
}
#kemaskini:hover {
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
.style3 {font-size: 12px}
.style4 {font-family: Arial, Helvetica, sans-serif}
.style5 {color: #FFFFFF}
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
      <td height="30" bgcolor="#CCCCCC"><div align="center"><span class="style4">| <a href="menu_admin.php">Menu Admin</a> | <a href="<?php echo $logoutAction ?>">Log Keluar</a> |</span></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br /><div align="center" class="style4">KEMASKINI STATUS BARANGAN</div>
      <br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><form id="form1" name="form1" method="post" action="<?php echo $editFormAction; ?>">
        <table width="645" border="0" align="center">
          <tr>
            <td width="219" bgcolor="#CCCCCC"><strong>No</strong><strong>mbor <em>tracking:</em></strong></td>
            <td width="416"><input class="box" name="tracking" type="text" id="tracking" value="<?php echo $row_kemaskini['no_tracking']; ?>" size="50" readonly="readonly" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Tarikh sampai:</strong></td>
            <td><input class="box" name="tarikh_sampai" type="text" id="tarikh_sampai" value="<?php echo $row_kemaskini['tarikh_sampai']; ?>" size="50" readonly="readonly" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nama penerima:</strong></td>
            <td><input class="box" name="nama" type="text" id="nama" value="<?php echo $row_kemaskini['penerima']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nombor telefon:</strong></td>
            <td><input class="box" name="no_tel" type="text" id="no_tel" value="<?php echo $row_kemaskini['no_tel']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nombor matrik/staff:</strong></td>
            <td><input class="box" name="matrik" type="text" id="matrik" value="<?php echo $row_kemaskini['id']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Jenis barangan:</strong></td>
            <td><input class="box" name="jenis" type="text" id="jenis" value="<?php echo $row_kemaskini['jenis']; ?>" size="50" /></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Status barangan:</strong></td>
            <td><select name="status" id="status">
                <option value="BELUM DITUNTUT" <?php if (!(strcmp("BELUM DITUNTUT", $row_kemaskini['status']))) {echo "selected=\"selected\"";} ?>>BELUM DITUNTUT</option>
                <option value="TELAH DITUNTUT" <?php if (!(strcmp("TELAH DITUNTUT", $row_kemaskini['status']))) {echo "selected=\"selected\"";} ?>>TELAH DITUNTUT</option>
              </select>
                <span class="style3">*sila pilih &quot;TELAH DITUNTUT untuk menukar status barangan</span> </td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Tarikh dituntut:</strong></td>
            <td><input class="box" type="date" name="tarikh_tuntut" id="tarikh_tuntut" /></td>
          </tr>
        </table>
        <p align="center">
          <input type="button" name="batal" id="batal" value="BATAL" onclick="window.location='view_rekod.php'" />
          <input type="submit" name="kemaskini" id="kemaskini" value="KEMASKINI" />
        </p>
        <div align="center">
          <input type="hidden" name="MM_update" value="form1" />
          </div>
      </form><br /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p><span class="style5">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</span></p>
</div>
<div align="center"></div>
</body>
</html>
<?php
mysql_free_result($kemaskini);
?>