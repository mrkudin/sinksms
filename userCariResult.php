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

$colname_userCarianResult = "-1";
if (isset($_GET['tracking'])) {
  $colname_userCarianResult = $_GET['tracking'];
}
mysql_select_db($database_admin_login, $admin_login);
$query_userCarianResult = sprintf("SELECT * FROM barang WHERE no_tracking = %s", GetSQLValueString($colname_userCarianResult, "text"));
$userCarianResult = mysql_query($query_userCarianResult, $admin_login) or die(mysql_error());
$row_userCarianResult = mysql_fetch_assoc($userCarianResult);
$totalRows_userCarianResult = mysql_num_rows($userCarianResult);
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
#back {
	background-image:url(images/button_carian-lain.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:122px;
	border:none;
	text-indent:-999em;
}
#back:hover {
	opacity:0.7;
	filter: alpha(opacity=70);
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
      <td bgcolor="#ADD8E6"><br /><div align="center"><span class="style1">Maklumat lanjut bagi: <b><u><?php echo $row_userCarianResult['no_tracking']; ?></u></b></span></div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><div align="center">
        <table width="532" border="0">
          <tr>
            <td bgcolor="#CCCCCC" width="258"><strong>No <em>tracking</em>:</strong></td>
            <td width="258"><?php echo $row_userCarianResult['no_tracking']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Tarikh sampai:</strong></td>
            <td><?php echo $row_userCarianResult['tarikh_sampai']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nama penerima:</strong></td>
            <td><?php echo $row_userCarianResult['penerima']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nombor telefon:</strong></td>
            <td><?php echo $row_userCarianResult['no_tel']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Nombor matrik/staff:</strong></td>
            <td><?php echo $row_userCarianResult['id']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Jenis barangan:</strong></td>
            <td><?php echo $row_userCarianResult['jenis']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Status pengambilan:</strong></td>
            <td><?php echo $row_userCarianResult['status']; ?></td>
          </tr>
          <tr>
            <td bgcolor="#CCCCCC"><strong>Tarikh dituntut:</strong></td>
            <td><?php echo $row_userCarianResult['tarikh_terima']; ?></td>
          </tr>
        </table>
        <p>
          <input type="button" name="back" id="back" value="Carian Lain" onclick="window.location='user.php'" />
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
mysql_free_result($userCarianResult);
?>
