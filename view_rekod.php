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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_senarai_rekod = 10;
$pageNum_senarai_rekod = 0;
if (isset($_GET['pageNum_senarai_rekod'])) {
  $pageNum_senarai_rekod = $_GET['pageNum_senarai_rekod'];
}
$startRow_senarai_rekod = $pageNum_senarai_rekod * $maxRows_senarai_rekod;

mysql_select_db($database_admin_login, $admin_login);
$query_senarai_rekod = "SELECT no_tracking, status FROM barang";
$query_limit_senarai_rekod = sprintf("%s LIMIT %d, %d", $query_senarai_rekod, $startRow_senarai_rekod, $maxRows_senarai_rekod);
$senarai_rekod = mysql_query($query_limit_senarai_rekod, $admin_login) or die(mysql_error());
$row_senarai_rekod = mysql_fetch_assoc($senarai_rekod);

if (isset($_GET['totalRows_senarai_rekod'])) {
  $totalRows_senarai_rekod = $_GET['totalRows_senarai_rekod'];
} else {
  $all_senarai_rekod = mysql_query($query_senarai_rekod);
  $totalRows_senarai_rekod = mysql_num_rows($all_senarai_rekod);
}
$totalPages_senarai_rekod = ceil($totalRows_senarai_rekod/$maxRows_senarai_rekod)-1;

$queryString_senarai_rekod = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_senarai_rekod") == false && 
        stristr($param, "totalRows_senarai_rekod") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_senarai_rekod = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_senarai_rekod = sprintf("&totalRows_senarai_rekod=%d%s", $totalRows_senarai_rekod, $queryString_senarai_rekod);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
#cari {
    border-radius: 4px;
}
#cari:focus {
	background-color: lightblue;
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
.style1 {font-family: Georgia, "Times New Roman", Times, serif}
.style2 {font-family: Arial, Helvetica, sans-serif}
.style4 {color: #FFFFFF}
-->
</style></head>

<body><br />
<div align="center">
  <table width="734" border="1">
    <tr>
      <td><?php include 'header.php' ?></td>
    </tr>
    <tr>
      <td height="30" bgcolor="#CCCCCC"><div align="center"><span class="style2">| <a href="menu_admin.php">Menu Admin</a> | <a href="<?php echo $logoutAction ?>">Log Keluar</a> |</span></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br /><div align="center"><span class="style2">Paparan Rekod</span></div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><form id="form1" name="form1" method="get" action="carian.php">

          <div align="center">
            <p>
              <input type="text" name="cari" id="cari" placeholder="Cth: EP123456789MY" />
              <input type="submit" name="cari2" id="cari2" value="Cari" />
            </p>
            <table border="0">
              <tr>
                <td bgcolor="#CCCCCC" width="253"><div align="center"><strong>Nombor <em>tracking:</em></strong></div></td>
                <td bgcolor="#CCCCCC" width="253"><div align="center"><strong>Status pengambilan:</strong></div></td>
              </tr>
              <?php do { ?>
              <tr>
                <td><div align="center"><a href="details.php?tracking=<?php echo $row_senarai_rekod['no_tracking']; ?>"><?php echo $row_senarai_rekod['no_tracking']; ?></a></div></td>
                <td><div align="center"><?php echo $row_senarai_rekod['status']; ?></div></td>
              </tr>
              <?php } while ($row_senarai_rekod = mysql_fetch_assoc($senarai_rekod)); ?>
            </table>
            <p><a href="<?php printf("%s?pageNum_senarai_rekod=%d%s", $currentPage, 0, $queryString_senarai_rekod); ?>">Mula</a> || <a href="<?php printf("%s?pageNum_senarai_rekod=%d%s", $currentPage, max(0, $pageNum_senarai_rekod - 1), $queryString_senarai_rekod); ?>">&lt; Sebelum</a> || <a href="<?php printf("%s?pageNum_senarai_rekod=%d%s", $currentPage, min($totalPages_senarai_rekod, $pageNum_senarai_rekod + 1), $queryString_senarai_rekod); ?>">Seterusnya &gt;</a> || <a href="<?php printf("%s?pageNum_senarai_rekod=%d%s", $currentPage, $totalPages_senarai_rekod, $queryString_senarai_rekod); ?>">Akhir</a></p>
          </div>
      </form> <br /></td>
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
mysql_free_result($senarai_rekod);
?>
