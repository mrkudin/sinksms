<?php require_once('Connections/admin_login.php'); ?>
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

$colname_userCari = "-1";
if (isset($_GET['tracking'])) {
  $colname_userCari = $_GET['tracking'];
}
mysql_select_db($database_admin_login, $admin_login);
$query_userCari = sprintf("SELECT * FROM barang WHERE no_tracking = %s", GetSQLValueString($colname_userCari, "text"));
$userCari = mysql_query($query_userCari, $admin_login) or die(mysql_error());
$row_userCari = mysql_fetch_assoc($userCari);
$totalRows_userCari = mysql_num_rows($userCari);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
.style1 {font-family: Arial, Helvetica, sans-serif}
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
      <td height="30" bgcolor="#CCCCCC"><div align="center"><span class="style1">| <a href="home.php">Laman Utama</a> |</span></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><BR />
      <div align="center"><span class="style1">SEMAKAN STATUS BARANGAN</span></div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><div align="center"><br />
        <form id="form1" name="form1" method="get" action="userCariResult.php">
          <p class="style1">Masukkan nombor <em>tracking</em> barangan yang ingin dicari:</p>
          <p>
            <input type="text" name="tracking" id="tracking" />
            <input type="submit" name="carian" id="carian" value="Cari" />
            </p>
          <p>&nbsp;</p>
        </form>
        
        
          
</div></td>
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
mysql_free_result($userCari);
?>