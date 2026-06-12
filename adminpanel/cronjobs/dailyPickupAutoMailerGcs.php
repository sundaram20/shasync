<?php
error_reporting(E_ALL);
set_time_limit(600);
//CRON URL



///////////////// CRON JOB PATH ///////////////////
//$path = getcwd().'/httpdocs/crs';
$path = '/var/www/vhosts/roomstatushub.in/httpdocs/sync';
include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once($path."/adminpanel/includes/functionDailyPickupReport.php");


//$DB_NAME='sales';
/*********** DATEA BASE CONNECTIONS *************/
	$DB_NAME='inroomhu_crsRoomstatus';
	$HOST_NAME = $_SERVER['SERVER_NAME'];
	$DOCUMENT_ROOT  = $_SERVER['DOCUMENT_ROOT'];

$DB_HOST                        = "ls-fe9795ed24d236257aec9c2c540e75e322c1c9d3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
	//$DB_HOST                        = "ls-73c1d44d0baaf1a357e5233ea2688df20d6ae29b.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306";                   // Database Host Server
	$DB_USERNAME                    = "gcs";              // Database Username
	$DB_PASSWORD                    = "Kallal2022#";              // Password for he Db User
	$DB_NAME                        = "gcs";              // Database name
	$DB_REPORT_ERROR                = true;                        // To Report Error
	$DB_PERSISTENT_CONN             = false;   
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

/************** Setting Variables **********************/



$cron='set';
$shop_id='2';


$cc = array();
   $rowTeam->name.$rowTeam->id;
   
   	$sqlHotelMapping = mysqli_query($connNew,"SELECT * FROM ".TBL_USERS." WHERE status=1 and ids_team='21'  group by email");		
$HotelMappingID	 =	mysqli_num_rows($sqlHotelMapping);
while($HotelMappingresult	 =	mysqli_fetch_object($sqlHotelMapping)){
   	
	// Head Email
   		$ids_team		= selectColumn(TBL_USERS,'ids_team','WHERE  id="'.$HotelMappingresult->id.'"');
		$ids_team		= explode(',',$ids_team);
			
		$id_user_level_1 = selectColumn(TBL_TEAM,'id_user_level_1','WHERE id="'.$ids_team[0].'" ');	
		$ccHead		  = selectColumn(TBL_USERS,'CONCAT(email,"|",name)','WHERE id="'.$id_user_level_1.'" ');
		$ccHeadArray 	 = explode("|",$ccHead);

		$cc[] 	   =    $HotelMappingresult->email;
		$ccHeadName 		=	$HotelMappingresult->name;
		
}

		$UserId = $rowTeam->id;
		//$_REQUEST['period'],$connNew,$db,$shop_id,$CronSet,$_REQUEST['period'];
		 
		 
		 $period = date('d-m-Y');
		 
		$ContentTable='';
		$mailContent='';
		echo $ContentTable=functionDailyReport($period,$connNew,$db,$shop_id,$cron,$period);
		
		 if($ContentTable!=''){
			 
			 
			 
	$mailContent .= "Dear ".$rowTeam->name.",<br/><br/>";
	$mailContent .= "Daily Pickup Report.<br/><br/>";
	$mailContent .=$ContentTable."<br/><br/>";
	$mailContent .="<br/><br/>Thanks & Regards<br/>RoomStatusHUB";	 
 	
	
    		//$cc[]=$ccHeadEmail;	
	//$cc[]='sundaram@roomstatushub.com';		 
			// $cc[]  = 'roomstatushublogs@gmail.com';
	$to  = 'roomstatushublogs@gmail.com';//$rowTeam->email;
 //$to  = 'sundaram@roomstatushub.com';
	$From ="support@roomstatushub.com";
	echo $sub = 'Gcs - Daily Pickup Report';


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
$mail->AddAddress($to);	

	//$mail->AddCC($vendorEmailCC);
	foreach($cc as $ccmail)
	{	
		//echo '<br>=='.$name;
   		$mail->AddCC($ccmail);
	}		
				  
	
	 $sendMail = $mail->Send(); 
		

		 }
	
 			 unset($ContentTable);
		     unset($mailContent);
		     unset($headers);
mysqli_close($connNew);
exit;
?>