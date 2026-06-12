<?php
/********* Dynamic DB Connection Based on Corporate Code *******/
date_default_timezone_set('Asia/Kolkata');
$DB_HOST                        = "localhost";                
$DB_USERNAME                    = "inroomhu_crsRoom";          
$DB_PASSWORD                    = "Kallal9876#";
$DB                             = "inroomhu_app";
$conn  = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB);
?>
