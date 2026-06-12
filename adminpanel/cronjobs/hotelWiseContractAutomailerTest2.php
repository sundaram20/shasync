<?php
//CRON URL
//error_reporting(E_ALL);
set_time_limit(350000);



///////////////// CRON JOB PATH ///////////////////
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




include_once($path."/adminpanel/includes/reportFunctions_HotelContract.php");
$DB_HOST                        = "ls-fe9795ed24d236257aec9c2c540e75e322c1c9d3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
//$DB_HOST                        = "ls-73c1d44d0baaf1a357e5233ea2688df20d6ae29b.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306"; 
$DB_NAME						='inroomhu_fern';
$DB_USERNAME                    = "inroomhu_fern";              // Database Username
$DB_PASSWORD                    = "Welcom@123";
$DB_REPORT_ERROR                = true;                        // To Report Error
$DB_PERSISTENT_CONN             = false;

$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$id_shop=6;
$summer = selectColumn(TBL_RATE_SEASON,'id','WHERE start_date="'.date('Y-04-01').'" AND id_shop="6" ');
$winter = selectColumn(TBL_RATE_SEASON,'id','WHERE start_date="'.date('Y-10-01').'" AND id_shop="6" ');


$season =array();


 $seasonSql = "SELECT id FROM ".TBL_RATE_SEASON." WHERE id_shop=".$id_shop."  AND status=1  and id IN('86')";


$resSeason = mysqli_query($connNew,$seasonSql);

while($rowSeason = mysqli_fetch_object($resSeason)){
	array_push($season,$rowSeason->id);
}

 $attachPath='/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/';
//$season =array();
//array_push($season,$summer);
//array_push($season,$winter);
//print_r($season);
 $hotelSql = "SELECT id FROM ".TBL_HOTELS." WHERE id_shop=".$id_shop."  AND status=1 and id IN('114') ORDER BY display_order ";

//and display_order BETWEEN 1 and 5 order by display_order;	

$resHotel = mysqli_query($connNew,$hotelSql);



$hotelCo=0;
while($rowHotel = mysqli_fetch_object($resHotel)){
	$hotelCo++;
	$hotelID=$rowHotel->id;
	$hotelName = selectColumn(TBL_HOTELS,'name','WHERE id="'.$hotelID.'" ');
	$hotelCity = selectColumn(TBL_HOTELS,'city','WHERE id="'.$hotelID.'" ');
	$hotelEmail = selectColumn(TBL_HOTELS,'email','WHERE id="'.$hotelID.'" ');

	//mail('support@roomstatushub.com','HOTEL CRON START',$hotelName.'_'.$hotelCity.'_'.date('d-M-Y H:i:s'));

	if($hotelEmail!=''){
		for($i=0;$i<count($season);$i++){

			$SqlRateExist	="SELECT `fs_rate_details`.*,`fs_rate`.* FROM `fs_rate_details` right join `fs_rate` on `fs_rate`.id=`fs_rate_details`.rate_id WHERE 1=1 AND `fs_rate`.status=1 AND fs_rate.company_id!=0 AND `fs_rate`.`seasonId` ='".$season[$i]."' AND `fs_rate_details`.`hotel_id`='".$hotelID."'";	
$resRateExist = mysqli_query($connNew,$SqlRateExist);
$NumbresRateExist	=	mysqli_num_rows($resRateExist);

if($NumbresRateExist>0){
			  $seasonName = selectColumn(TBL_RATE_SEASON,'name','WHERE id="'.$season[$i].'" ');

			if($seasonName!=''){
			

			$fileName = $hotelName.'_'.$hotelCity.' Contracted Rate Report for '.ucwords(strtolower($seasonName));
			$attachBooking=$hotelName.'_'.$hotelCity.' Contracted Rate Report for '.ucwords(strtolower($seasonName)).'.xls';
			$objPHPExcel = new PHPExcel();
			hotelWiseContractReportNEW('set',$id_shop,$hotelID,$season[$i],$db,$objPHPExcel);
			unset($objPHPExcel);
			 //server
			 $attach = '/var/www/vhosts/roomstatushub.in/httpdocs/sync/adminpanel/mailattach/'.$fileName.'.xls';

			  
			$mailto = 'test';
			$mailCc = 'test';
			
			   $content = 'Dear Team,<br><br>
			 				
			 				Kindly find attached the Hotel Contracted Rates Report – '.$seasonName.' which supercedes the last sent report. Kindly check the Remarks Column for Inclusions. 

						<br><br> 
						 Thanks & Regards<br><br>
						<b>RoomStatusHUB Team</b><br>
						
						';
						
						
 $mailto="roomstatushublogs@gmail.com";
if($mailto !=''){// && file_exists($attach)){
		 
$msg = $content;
$msg = wordwrap($msg,70);
$From="support@roomstatushub.com";
$sub= $fileName;

 $AddAddress=explode(',',str_replace(';',',',$hotelEmail));
	
//$recipients =explode(",",$_POST['ccId']);
 $cc = array();
 $cc[]='mohit@fernhotels.com';
$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "support@roomstatushub.com";
$mail->Password = "room9876#";
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $msg;
$mail->AddBCC('roomstatushublogs@gmail.com');
//$mail->AddAddress('roomstatushublogs@gmail.com');
	
	foreach($AddAddress as $AddAddressmail)
	{	
		
   		$mail->AddAddress($AddAddressmail);
	}		
	foreach($cc as $ccmail)
		{	
		
   		$mail->AddCC($ccmail);
		}
		 $attachname1=$attachBooking;
   		$mail->addAttachment($attachPath.$attachname1,$attachBooking,"base64","application/Excel");
			  
	
$sendMail = $mail->Send(); 
		
		 	
		}
		 unset($sendMail);






}
	
						
					}	
				
			 
		}
		
		
	} 
}

?>