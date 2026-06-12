<?php
session_start();
include_once("../config/data.config.php");
require("../phplib/PHPMailer/PHPMailerAutoload.php");
require("../phplib/class.mailer.php");
$sendAutoMail = new sendMail;

	echo $path = getcwd();	

	//mail("support@roomstatushub.com","testing2","".$path."");
	mail("support@roomstatushub.com","testing2","hello");


//$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
//$sql = "SELECT * FROM `fs_automailer_config` ";
//$res = mysqli_query($conn,$sql);
	
//if($res){
	//while($rowData = mysqli_fetch_object($res)){
			//$sqlSession = "SELECT * FROM `fs_users` WHERE id=".$rowData->last_modified_by." AND id_shop=".$rowData->id_shop." ";
			//$resSession = mysqli_query($conn,$sqlSession);
			//
			//
			//$sendAutoMail->ClearAllRecipients($rowData->receiver_email);
		//unset($rowData->receiver_email);
		//if($resSession){
			
			//$sessionData = mysqli_fetch_object($resSession);
			/*echo "<pre>";
			print_r($sessionData);
			echo "</pre>";*/

			/*$_SESSION['userName'] = $sessionData->username;
			$_SESSION['userEmail'] = $sessionData->email;
			$_SESSION['userLevel'] = $sessionData->user_level;
			$_SESSION['userLastLogin'] = $sessionData->last_login;
			$_SESSION['shop'] = $rowData->id_shop;
			$_SESSION['userId'] = $rowData->last_modified_by;
			$_SESSION['hotel_access'] = $rowData->hotel_tagged;
			$_SESSION['sessionId'] = session_id();*/
			
			//if($_SESSION['hotel_access']!=''){
			//	$condhotelAccess = "  AND A.id_hotel IN (".$_SESSION['hotel_access'].")";
			//}
			
			//$autoDate = date("Y-m-d",strtotime("2018-09-04"));
			//$reports = explode(",",$rowData->report);
			//echo "<pre>";
			//print_r($_SESSION);
			//echo "</pre>";
			//$objPHPExcel = new PHPExcel();
			//for($i=0 ;$i<count($reports);$i++){
			

			/*$_SESSION['userName'] = "whadmin";
			$_SESSION['userEmail'] = "welcomheritage@welcomheritage.com";
			$_SESSION['userLevel'] = 1;
			$_SESSION['userLastLogin'] = "2018-10-20 12:02:03";
			$_SESSION['shop'] = 2;
			$_SESSION['userId'] = 10;
			//$_SESSION['hotel_access'] = $rowData->hotel_tagged;
			$_SESSION['sessionId'] = session_id();*/
			

				//$sqlReport = "SELECT * FROM  `fs_report_master` WHERE id = ".$reports[$i]." ";
				//$resReport = mysqli_query($conn,$sqlReport);
				/*if($resReport){
					$reportData = mysqli_fetch_object($resReport);
					$reportName = $reportData->name; //name of the file
					$fileName = $reportData->file_name; // name of the report
					$excelSheet = $reportData->excelSheetName; // excel file name
					$randName = "daily_booking_";
					$randName .= rand(1,2000);		*/	
					
					$randName = "daily_booking_report";
					
					//$randName .= rand(1,2000);	
					//echo '<script type="text/javascript">location.href = "dayWise.php?Download=Generate&autoDate='.$autoDate.'&filePath=SET&randName='.$randName.'";</script>';
					//header("LOCATION:dayWise.php?Download=Generate&autoDate=$autoDate&filePath=SET&randName=$randName");

					//echo '<script type="text/javascript">window.open("dayWise.php?Download=Generate&autoDate='.$autoDate.'&filePath=SET&randName='.$randName.'");</script>';

					//$content = "Dear ".$rowData->receiver_name.",<br><br> Kindly find the attachment of ".$reportName."<br><br> Regards, <br> RoomStatusHUB";
					
					
					$content = "Dear Support Team,<br><br> Kindly find the attachment of Booking Report<br><br> Regards, <br> RoomStatusHUB";
					
					$sendAutoMail->autoMail('automailer@roomstatushub.com',"support@roomstatushub.com","Daily Booking Report",$content,"",$randName,$randName);
					
					
					//unlink('autoMailerExport\daily_booking_report.xls');			
					

					//unlink("D:\wamp\www\\roomstatushub\adminpanel\autoMailerExport\\".$randName.".xls");
					

				//}	
			//}

		//}
			
	//}	
//}
			
?>