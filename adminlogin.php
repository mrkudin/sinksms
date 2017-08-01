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

mysql_select_db($database_admin_login, $admin_login);
$query_Recordset1 = "SELECT `admin`.user, `admin`.pass FROM `admin`";
$Recordset1 = mysql_query($query_Recordset1, $admin_login) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=$_POST['pass'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "menu_admin.php";
  $MM_redirectLoginFailed = "login_gagal.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_admin_login, $admin_login);
  
  $LoginRS__query=sprintf("SELECT `user`, pass FROM `admin` WHERE `user`=%s AND pass=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $admin_login) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistem Notifikasi Kurier melalui SMS | SiNK-SMS</title>
<style type="text/css">
<!--
body { background-image:url(images/ucb3.png); background-repeat:no-repeat; background-attachment:fixed; background-position:center }
.divider {
	width:5px;
	height:auto;
	display:inline-block;
}
#submit {
	background-image:url(images/button_login.png);
	background-repeat:no-repeat;
	background-color:transparent;
	height:34px;
	width:135px;
	border:none;
	text-indent:-999em;
}
#submit:hover {
	opacity:0.7;
	filter: alpha(opacity=70);
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
      <td height="30" class="style2" bgcolor="#CCCCCC"><div align="center">| <a href="home.php">Laman Utama</a> |</div></td>
    </tr>
    <tr>
      <td class="style2" bgcolor="#ADD8E6"><br /><div align="center">SILA MASUKKAN BUTIRAN LOGIN ANDA</div><br /></td>
    </tr>
    <tr>
      <td class="style2" bgcolor="#FFFFFF"><br /><br /><form action="<?php echo $loginFormAction; ?>" id="admin" name="admin" method="post">
        <div align="center">
          <table width="438" border="0">
              <tr>
                <td width="174">ID</td>
                <td width="10">:</td>
                <td width="240"><input class="box" name="user" type="text" id="username" size="40" /></td>
              </tr>
              <tr>
                <td>Kata laluan</td>
                <td>:</td>
                <td><input class="box" name="pass" type="password" id="pass" size="40" /></td>
              </tr>
                  </table>
        </div>
        <p align="center">
          <input type="reset" name="clear" id="clear" value="Kosongkan" />
          <input name="submit" type="submit" id="submit" value="Login" />
        </p>
        </form>
      <br /></td>
    </tr>
    <tr>
      <td class="style2" bgcolor="#ADD8E6"><?php include 'footer.php' ?></td>
    </tr>
  </table>
  <p><span class="style4">Paparan terbaik menggunakan Google Chrome dengan resolusi 1024 x 768</span></p>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
