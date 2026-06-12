<?php
error_reporting(E_ALL);
set_time_limit(600);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/followupReportAutoMailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once("../../adminpanel/includes/reportFunctionsFollowupNotification.php");*/


///////////////// CRON JOB PATH ///////////////////
//$path = getcwd().'/httpdocs/crs';
$path = '/var/www/vhosts/roomstatushub.in/httpdocs/sales';
include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once($path."/adminpanel/includes/reportFunctionsFollowupNotification.php");


$DB_NAME='gcs';
//$DB_NAME='sales';
/*********** DATEA BASE CONNECTIONS *************/
	$DB_NAME='inroomhu_crsRoomstatus';
	$HOST_NAME = $_SERVER['SERVER_NAME'];
	$DOCUMENT_ROOT  = $_SERVER['DOCUMENT_ROOT'];

//$DB_HOST                        = "ls-fe9795ed24d236257aec9c2c540e75e322c1c9d3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
  $DB_HOST                        = "ls-fe9795ed24d236257aec9c2c540e75e322c1c9d3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";                   // Database Host Server
	$DB_USERNAME                    = "gcs";              // Database Username
	$DB_PASSWORD                    = "Kallal2022#";              // Password for he Db User
	$DB_NAME                        = "gcs";              // Database name
	$DB_REPORT_ERROR                = true;                        // To Report Error
	$DB_PERSISTENT_CONN             = false;   
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

/************** Setting Variables **********************/


 $sqlTeam  = "SELECT * FROM ".TBL_USERS." WHERE id_shop='2' and status='1'";
$resTeam  = mysqli_query($connNew,$sqlTeam);
$cron='set';
 $shop_id=2;



while($rowTeam = mysqli_fetch_object($resTeam)){
    $rowTeam->name.$rowTeam->id;
   
   	
   	
	// Head Email
   		 $ids_team		= selectColumn(TBL_USERS,'ids_team','WHERE id="'.$rowTeam->id.'" ');
		$ids_team		= explode(',',$ids_team);
			
		$id_user_level_1 = selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team[0].'" ');	
		$ccHead		  = selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray 	 = explode("|",$ccHead);

		$ccHeadEmail 	   =    $ccHeadArray[0];
		$ccHeadName 		=	$ccHeadArray[1];
		
		
		$UserId = $rowTeam->id;
		
		 
		 $dateStr = date('d-m-Y');
		 $checkin =date('01-m-Y',strtotime('-1 month',strtotime($dateStr)));
		 echo $checkout = date('30-m-Y');
		 
		$ContentTable='';
		$mailContent='';
	
	echo '<br>======'.$shop_id.'==='.$cron.'==='.$checkin.'==='.$checkout.'==='.$UserId;
		echo $ContentTable=followUpNotificationsReport($connNew,$shop_id,$cron,$checkin,$checkout,$UserId);
		 if($ContentTable!=''){
			 
			 
			 
	$mailContent .= "Dear ".$rowTeam->name.",<br/><br/>";
	$mailContent .= "You have below Follow ups Pending.<br/><br/>";
	$mailContent .=$ContentTable."<br/><br/>";
	$mailContent .="<br/><br/>Thanks & Regards<br/>RoomStatusHUB";	 
 	
	$cc = array();
    $cc[]=$ccHeadEmail;
	//$cc[]='mohit@fernhotels.com';
	//$cc[]='noshir@fernhotels.com';
	//$cc[]='sundaram@roomstatushub.com';		 
	$cc[]  = 'roomstatushublogs@gmail.com';
	$to  = $rowTeam->email;
	$From ="support@roomstatushub.com";
	$sub = 'Gcs Pending Follow ups';


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
$mail->Password = "kxfm xrpv znoi xmhx";
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $mailContent;
$mail->AddAddress('roomstatushublogs@gmail.com');	

	//$mail->AddCC($vendorEmailCC);
	foreach($cc as $ccmail)
	{	
		//echo '<br>=='.$name;
   		$mail->AddCC($ccmail);
	}		
				  
	
	$sendMail = $mail->Send(); 
		

		 }
	}	
 			 unset($ContentTable);
		     unset($mailContent);
		     unset($headers);
mysqli_close($connNew);
exit;
?>