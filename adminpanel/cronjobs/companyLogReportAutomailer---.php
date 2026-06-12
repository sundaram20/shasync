<?php
//error_reporting(E_ALL);

set_time_limit(600);


//TEST HERE///
/*$path = getcwd().'/public_html/sales';
mail('hiteshaloney75@gmail.com','TEST CRON',$path);
exit;*/
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/companyLogReportAutomailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");*/



///////////////// CRON JOB PATH ///////////////////
$path = getcwd().'/public_html/crs';
include_once($path."/config/data.config.php");
include_once(str_replace('crs','sales',$path)."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once(str_replace('crs','sales',$path)."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

//Local File Path 
//include_once('../includes/reportFunctions.php');
//error_reporting(E_ALL);
//Cron Path
include_once(str_replace('crs', 'sales',$path)."/adminpanel/includes/reportFunctions.php");
$DB_NAME='inroomhu_fern';
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$objPHPExcel = new PHPExcel();


$sql="SELECT id from ".TBL_AREAS." WHERE id_shop=6 and status=1 ";
$res=mysqli_query($connNew,$sql);
$ids_areas="";
while($row=mysqli_fetch_object($res)){
	$ids_areas.=','.$row->id;
}

$date=date('Y-m-d',strtotime('-1 days',strtotime(date('Y-m-d'))));

companyAdditionReport(6,$date,$date,$connNew,$ids_areas,'set');

$fileName='Company_Addition_Report '.$date.'.xls';
//server
$attach = '/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName;

//local
//$attach = '../mailattach/'.$fileName;
$sendMail = new sendMail;
$sendMail->sendRateMail('donotreply@roomstatushub.com','mohit@fernhotels.com','Company Log  Report','PFA Company Log Report','support@roomstatushub.in',$attach);

//$sendMail->sendRateMail('donotreply@roomstatushub.in','support@roomstatushub.com','Company Log  Report','PFA Comppany Log Report','hiteshaloney75@gmail.com',$attach);

if(file_exists($attach))
	unlink($attach);

?>