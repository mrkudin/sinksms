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
?><?php

function SendSMS ($host, $port, $username, $password, $phoneNoRecip, $msgText) { 

/* Parameters:
    $host - IP address or host name of the NowSMS server
    $port - "Port number for the web interface" of the NowSMS Server
    $username - "SMS Users" account on the NowSMS server
    $password - Password defined for the "SMS Users" account on the NowSMS Server
    $phoneNoRecip - One or more phone numbers (comma delimited) to receive the text message
    $msgText - Text of the message
    Additional NowSMS URL parameters can follow $msgText as additional parameters (e.g., "SMSCRoute=routename", "Sender=1234")
*/
 
    $fp = fsockopen($host, $port, $errno, $errstr);
    if (!$fp) {
        echo "errno: $errno \n";
        echo "errstr: $errstr\n";
        return $result;
    }

    $getString = "GET /?Phone=" . rawurlencode($phoneNoRecip) . "&Text=" . rawurlencode($msgText);
    /* Parse extra function parameters for more URL parameters */
    $numArgs = 6;
    while ($numArgs < func_num_args()) {
       $argString = func_get_arg($numArgs);
       $getString = $getString . "&" . substr($argString, 0, strpos($argString,'=')) . "=" . rawurlencode(substr($argString, strpos($argString,'=')+1));
       $numArgs += 1;
    }
    $getString = $getString . " HTTP/1.0\n";
    
    fwrite($fp, $getString);
    if ($username != "") {
       $auth = $username . ":" . $password;
       $auth = base64_encode($auth);
       fwrite($fp, "Authorization: Basic " . $auth . "\n");
    }
    fwrite($fp, "\n");
  
    $res = "";
 
    while(!feof($fp)) {
        $res .= fread($fp,1);
    }
    fclose($fp);
    
 
    return $res;
} 


if (isset($_REQUEST['nofon'])) {
   if (isset($_REQUEST['teks'])) {
      $x = SendSMS("127.0.0.1", 8800, "kudin", "superadmin", $_REQUEST['nofon'], 
                    $_REQUEST['teks']);
      echo $x;
   }
   else {
      echo "ERROR : Message not sent -- Text parameter is missing!\r\n";
   }
}
else {
   echo "ERROR : Message not sent -- Phone parameter is missing!\r\n";
}

  $_SESSION['notel'] = NULL;
  $_SESSION['tracking'] = NULL;
/*  unset($_SESSION['notel']);
  unset($_SESSION['tracking']); */

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sedang memproses...</title>
<meta http-equiv="refresh" content="5; URL=view_rekod.php">
<meta name="keywords" content="automatic redirection">
<style type="text/css">
<!--
.style1 {font-family: Georgia, "Times New Roman", Times, serif}
-->
</style>
</head>

<body>
<script>
    alert("SMS berjaya dihantar. Klik 'OK' untuk teruskan.");
	window.location= "view_rekod.php";
</script>
</body>
</html>