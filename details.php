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

mysql_select_db($database_admin_login, $admin_login);
$query_padamRekod = "SELECT * FROM barang";
$padamRekod = mysql_query($query_padamRekod, $admin_login) or die(mysql_error());
$row_padamRekod = mysql_fetch_assoc($padamRekod);
$totalRows_padamRekod = mysql_num_rows($padamRekod);

mysql_select_db($database_admin_login, $admin_login);
$details = $_GET['tracking'];
$query_detail_barang = "SELECT * FROM barang WHERE no_tracking = '$details'";
$detail_barang = mysql_query($query_detail_barang, $admin_login) or die(mysql_error());
$row_detail_barang = mysql_fetch_assoc($detail_barang);
$totalRows_detail_barang = mysql_num_rows($detail_barang);
?>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
#kemaskini {
	background-image:url(images/button_kemaskini-rekod.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:167px;
	border:none;
	text-indent:-999em;
}
#kemaskini:hover {
	opacity:0.7;
}
#padam {
	background-image:url(images/button_padam-rekod.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:143px;
	border:none;
	text-indent:-999em;
}
#padam:hover {
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
.style1 {font-family: Arial, Helvetica, sans-serif}
.style4 {color: #FFFFFF}
-->
</style>
<br /><div align="center">
  <table width="734" border="1">
    <tr>
      <td><?php include 'header.php' ?></td>
    </tr>
    <tr>
      <td height="30" bgcolor="#CCCCCC"><div align="center"><span class="style1">| <a href="menu_admin.php">Menu Admin</a> | <a href="view_rekod.php">Senarai Rekod</a> | <a href="<?php echo $logoutAction ?>">Log Keluar</a> |</span></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br /><div align="center"><span class="style1">Maklumat lanjut bagi:<strong> <u><?php echo $details; ?></u></strong></span></div>
      <br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><form id="form1" name="form1" method="post" action="">
        <table width="532" border="0" align="center">
          <tr>
            <td width="261" bgcolor="#CCCCCC"><strong>Nombor <em>tracking:</em></strong></td>
            <td width="261"><?php echo $row_detail_barang['no_tracking']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Tarikh sampai:</strong></td>
            <td><?php echo $row_detail_barang['tarikh_sampai']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nama penerima:</strong></td>
            <td><?php echo $row_detail_barang['penerima']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nombor telefon:</strong></td>
            <td><?php echo $row_detail_barang['no_tel']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nombor matrik/Staff:</strong></td>
            <td><?php echo $row_detail_barang['id']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Jenis barangan:</strong></td>
            <td><?php echo $row_detail_barang['jenis']; ?></td>
          </tr>
          <tr>
            <td valign="top" bgcolor="#CCCCCC"><strong>Status pengambilan:</strong></td>
            <td><p><?php echo $row_detail_barang['status']; ?></p></td>
          </tr>
          <tr>
            <td valign="top" bgcolor="#CCCCCC"><strong>Tarikh dituntut:</strong></td>
            <td><p><?php echo $row_detail_barang['tarikh_terima']; ?></p></td>
          </tr>
          <tr>
            <td height="32" valign="top">&nbsp;</td>
            <td><u><a href="print_rekod.php?tracking=<?php echo $row_detail_barang['no_tracking']; ?>" target="_blank">CETAK MAKLUMAT</a></u></td>
          </tr>
          <tr>
            <td height="60" colspan="2">
                <br /><div align="center">
                    <input type="button" name="kemaskini" id="kemaskini" value="KEMASKINI REKOD" onClick="window.location='kemaskini_rekod.php?tracking=<?php echo $details; ?>'"/>
                    <input type="button" name="padam" id="padam" value="PADAM REKOD" onClick="window.location='confirm_padam.php?tracking=<?php echo $details; ?>'"/>
                    </a><br />
                </div></td></tr>
        </table>
      </form><br /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p><span class="style4">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</span><br />
  </p>
  </div>
<?php
mysql_free_result($detail_barang);

mysql_free_result($padamRekod);
?>
