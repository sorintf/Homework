<?php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('opcache.enable', 0);

session_start();
error_reporting(E_ALL);
//error_reporting(E_ERROR);
// ini_set('max_execution_time', 21600); //6 ore
ini_set('max_execution_time', 600); //10 minute

date_default_timezone_set('Europe/Bucharest');
setlocale(LC_ALL, 'ro_RO.utf8');
#strftime("%e %b %Y", strtotime("2019-12-05"));

define("DB_HOST", "localhost");
define("DB_NAME", "homework_u");
define("DB_USER", "damon");
define("DB_PASS", "zx*-!M7IS[mrn8n1");
define("DB_SECRET", "h4Rm0n!a");

define("COOKIE_RUNTIME", 1209600);
define("COOKIE_DOMAIN", "");
define("COOKIE_SECRET_KEY", "poi39O895wtXcYN9Vh2SbRoYGCJkkuRT");

define("BASE_URL", "http://localhost/homework");
define("PLATFORM_NAME", "MyCompany");

define("FORMAT_DATE", "Y-m-d");
define("FORMAT_DATE_RO_L", "d-m-Y H:i:s");
define("FORMAT_DATE_RO_S", "d-m-Y");

define("MESSAGE_DATABASE_ERROR", "Eroare conectare la baza de date");

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}else{
	$action = "";
}
if ($action=='logout') {
	$action = 'f_index';
}