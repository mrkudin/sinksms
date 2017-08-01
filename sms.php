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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title><style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
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
}
#hantar {
	background-image:url(images/button_hantar.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:91px;
	border:none;
	text-indent:-999em;
}
#hantar:hover {
	opacity:0.7;
}
.box {
    border-radius: 4px;
}
.box:focus {
	background-color: lightblue;
}
.style1 {font-family: Arial, Helvetica, sans-serif}
.style4 {color: #FFFFFF}
.style6 {
	font-family: Arial, Helvetica, sans-serif;
	color: #FF0000;
	font-weight: bold;
}
-->
</style></head>

<body><br />
<div align="center">
  <table width="734" border="1">
    <tr>
      <td><?php include 'header.php' ?></td>
    </tr>
    <tr>
      <td height="30" bgcolor="#CCCCCC"><div align="center"><em><span class="style6">*Sila lengkapkan proses ini sebelum ke laman lain</span></em></div></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br /><div align="center"><span class="style1">PENGHANTARAN SMS KEPADA PENERIMA BARANG</span></div>
      <br /></td>
    </tr>
    <tr>
      <td bgcolor="#FFFFFF"><br /><br /><form id="form1" name="form1" method="post" action="sms-func.php">
        <div align="center">
          <table width="472" border="0">
              <tr>
                <td><strong>No. telefon:</strong></td>
                <td width="317"><div align="right">
                    <input class="box" placeholder="Contoh: 0123456789" name="nofon" type="text" id="nofon" size="48" value="<?php echo $_SESSION['notel']; ?>" readonly />
                </div></td>
              </tr>
              <tr>
                <td valign="top"><strong>Mesej:</strong></td>
                <td><div align="right">
                    <textarea class="box" name="teks" cols="50" rows="6" id="teks">Salam Sejahtera. Barangan anda (<?php echo $_SESSION['tracking']; ?>) telah selamat sampai di UC Bestari. Sila datang ke Unit HEPA UC Bestari untuk proses tuntutan barang dalam masa satu hari bekerja. Info lanjut sila layari http://sink-sms.ucbestari.edu.my. Terima Kasih.</textarea>
                </div></td>
              </tr>
                  </table>
        </div>
        <p align="center">
          <input type="submit" name="hantar" id="hantar" value="Hantar" />
        </p>
      </form><br /></td>
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