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
//echo $path = getcwd().'';
$path='/var/www/vhosts/roomstatushub.in/httpdocs/sales';
//include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
//include_once($path."/phplib/roomstatus.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

//Local File Path 
//include_once('../includes/reportFunctions.php');
//error_reporting(E_ALL);
//Cron Path
include_once($path."/adminpanel/includes/reportFunctions.php");
//$DB_HOST                        = "ls-fe9795ed24d236257aec9c2c540e75e322c1c9d3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
$DB_HOST                        = "ls-13ad08cbb1eb4c382975d4ad27d9e03bc480b83e.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com"; 
$DB_NAME						='inroomhu_fern_1';
$DB_USERNAME                    = "inroomhu_fern";              // Database Username
$DB_PASSWORD                    = "Welcom@123";
$DB_REPORT_ERROR                = true;                        // To Report Error
	$DB_PERSISTENT_CONN             = false;
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
$attach = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName;

//local
//$attach = '../mailattach/'.$fileName;
//$sendMail = new sendMail;
//$sendMail->sendRateMail('donotreply@roomstatushub.com','mohit@fernhotels.com','Company Log  Report','PFA Company Log Report','support@roomstatushub.in',$attach);



// && file_exists($attach)){
		 
$msg = "Please find the attachment for PFA Company Log Report<br/><br/> RoomStatusHUB Team.";
$msg = wordwrap($msg,70);
$From="support@roomstatushub.com";
$sub= 'Company Log  Report';
$mailto='mohit@fernhotels.com';
$cc = array();    
//$cc[]='sundaram@roomstatushub.com';
$cc[]='admindel@fernhotels.com';
$cc[]="roomstatushublogs@gmail.com";
//$recipients =explode(",",$_POST['ccId']);

$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "support@roomstatushub.com";
$mail->Password = "Gcs9876#";
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $msg;
$mail->AddAddress($mailto);	

	//$mail->AddCC($vendorEmailCC);
	foreach($cc as $ccmail)
	{	
		//echo '<br>=='.$name;
   		$mail->AddCC($ccmail);
	}		
		
		
   		$mail->addAttachment($attach,$fileName,"base64","application/excel");
				  
	
	$sendMail = $mail->Send(); 

if(file_exists($attach))
	unlink($attach);

?>