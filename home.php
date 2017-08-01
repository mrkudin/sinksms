<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
#admin {
	background-image:url(images/button_pentadbir.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:42px;
	width:140px;
	border:none;
	text-indent:-999em;
}
#admin:hover {
	opacity:0.7;
}
#user {
	background-image:url(images/button_pengguna.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:42px;
	width:138px;
	border:none;
	text-indent:-999em;
}
#user:hover {
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
.style3 {font-family: Arial, Helvetica, sans-serif}
.bg {
  opacity: 0.5;
}
.style4 {color: #FFFFFF}
-->
</style>

</head>

<body><br />
<div align="center">
  <table width="734" border="1">
    <tr>
      <td width="728"><?php include 'header.php' ?></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><br />
        <div align="center" class="style3">SELAMAT DATANG KE SISTEM NOTIFIKASI KURIER MELALUI SMS</div>
        <br />
      <div align="center" class="style1"></div></td>
    </tr>
    <tr>
      <td height="144" bgcolor="#FFFFFF"><br /><div align="center">
        <p class="style3">Sila pilih jenis pengguna:</p>
        <p>
          <input name="admin" type="button" class="admin" id="admin" onclick="window.location='adminlogin.php'" value="PENTADBIR" />
        </p>
        <p>
          <input name="user" type="button" class="user" id="user" onclick="window.location='user.php'" value="PENERIMA BARANG" />
        </p>
      </div><br /></td>
    </tr>
    <tr>
      <td bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p class="style4">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</p>
</div>
</body>
</html>
