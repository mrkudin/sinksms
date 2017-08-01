<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_admin_login = "localhost";
$database_admin_login = "sinksms";
$username_admin_login = "root";
$password_admin_login = "";
$admin_login = mysql_pconnect($hostname_admin_login, $username_admin_login, $password_admin_login) or trigger_error(mysql_error(),E_USER_ERROR); 
?>