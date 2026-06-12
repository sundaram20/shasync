<?php
session_start();
set_time_limit(600);
/**********
Author : Hitesh Aloney
Date : 10-01-2020
Description : Dashbord Graphs PDF automailer.
***********/



//CRON URL COMMAND
/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/dashboardReportPdfAutomailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH  ///////////////////
/*
* Uncomment If working on local
*/
include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include("../../phplib/dompdf/dompdf_config.inc.php");
include("../../phplib/phpgraphlib/phpgraphlib.php");
include('../../phplib/phpgraphlib/phpgraphlib_pie.php');
//include('../../adminpanel/includes/dashboardGraphFunction.php');
include('../../adminpanel/dashboard/function.php');

///////////////// CRON JOB PATH ///////////////////
/*
* Uncomment If working on server
*/
// $path = getcwd().'/public_html/crs';
// include_once($path."/config/data.config.php");
// include_once(str_replace('crs','sales',$path)."/phplib/data.constant.php");	
// include_once($path."/phplib/functions.library.php");
// include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
// include_once(str_replace('crs','sales',$path)."/phplib/class.mailer.php");
// include_once($path."/phplib/class.database.php");
// include($path."/phplib/dompdf/dompdf_config.inc.php");
// include_once(str_replace('crs','sales',$path)."/phplib/phpgraphlib/phpgraphlib.php");	

/*********** DATEA BASE CONNECTIONS *************/
$DB_NAME='inroomhu_crsRoomstatus';
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

/************** Setting Variables **********************/

// local file save path
$attachPath='../mailattach/';

//cron job save path
// $attachPath=str_replace('crs','sales',$path).'/adminpanel/mailattach/';

$unitUser = $_SESSION['unit_user'];

//$leadGraphData = leadGraphData(date('Y-m-d',strtotime($_REQUEST['report_date'])), $_REQUEST['id_team'], $_SESSION['teamMembers'], $_SESSION['shop'], $unitUser);

tableViewfunction("12-02-2021 to 12-02-2021",'','0','1','0','7','1');

$Filename='PickupReport_'.date("Y-m-d");


$attach = '/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$Filename.'.pdf';

		  //local
		 //$attach = '../mailattach/'.$fileName.'.xls';
		 $sendMail = new sendMail;
		 $mailto = 'support@roomstatushub.com';
		 if($mailto !='' && file_exists($attach)){
		 echo	$sendMail->sendRateMail('donotreply@roomstatushub.com',$mailto,$Filename .' Pickup Summary Report','Pickup Summary Report','support@roomstatushub.com',$attach);

		 	
		}
		 unset($sendMail);
		 if(file_exists($attach)){
		 	unlink($attach);
		 }	



?>