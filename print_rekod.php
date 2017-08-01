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

$colname_printDetailedRekod = "-1";
if (isset($_GET['tracking'])) {
  $colname_printDetailedRekod = $_GET['tracking'];
}
mysql_select_db($database_admin_login, $admin_login);
$query_printDetailedRekod = sprintf("SELECT * FROM barang WHERE no_tracking = %s", GetSQLValueString($colname_printDetailedRekod, "text"));
$printDetailedRekod = mysql_query($query_printDetailedRekod, $admin_login) or die(mysql_error());
$row_printDetailedRekod = mysql_fetch_assoc($printDetailedRekod);
$totalRows_printDetailedRekod = mysql_num_rows($printDetailedRekod);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SISTEM NOTIFIKASI KURIER MELALUI SMS | SiNK-SMS</title>
<script>
    window.print();
</script>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
.style1 {font-family: Arial, Helvetica, sans-serif}
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
      <td height="30" bgcolor="#CCCCCC"><div align="center" class="style1">CETAKAN STATUS BARANGAN</div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br /><div align="center">MAKLUMAT BARANGAN BAGI <u><?php echo $row_printDetailedRekod['no_tracking']; ?></u></div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><br /><table width="532" border="0" align="center">
        <tr>
          <td width="261" bgcolor="#CCCCCC"><strong>Nombor <em>tracking:</em></strong></td>
          <td width="261"><?php echo $row_printDetailedRekod['no_tracking']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC"><strong>Tarikh sampai:</strong></td>
          <td><?php echo $row_printDetailedRekod['tarikh_sampai']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC"><strong>Nama penerima:</strong></td>
          <td><?php echo $row_printDetailedRekod['penerima']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC"><strong>Nombor telefon:</strong></td>
          <td><?php echo $row_printDetailedRekod['no_tel']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC"><strong>Nombor matrik/Staff:</strong></td>
          <td><?php echo $row_printDetailedRekod['id']; ?></td>
        </tr>
        <tr>
          <td bgcolor="#CCCCCC"><strong>Jenis barangan:</strong></td>
          <td><?php echo $row_printDetailedRekod['jenis']; ?></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#CCCCCC"><strong>Status pengambilan:</strong></td>
          <td><?php echo $row_printDetailedRekod['status']; ?></td>
        </tr>
        <tr>
          <td valign="top" bgcolor="#CCCCCC"><strong>Tarikh dituntut:</strong></td>
          <td><?php echo $row_printDetailedRekod['tarikh_terima']; ?></td>
        </tr>
      </table><br /><br /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
</div></body>
</html>
<?php
mysql_free_result($printDetailedRekod);
?>
