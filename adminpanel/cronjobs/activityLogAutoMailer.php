<?php
set_time_limit(600);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/activityLogAutoMailer.php /dev/null 2>&1 */

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

//$DB_NAME='inroomhu_fern';
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
$sqlTeam  = "SELECT id,name,id_user_level_2,id_user_level_1,ids_user_monthly_reporting FROM ".TBL_TEAM." WHERE id_shop=6 ";
$resTeam  = mysqli_query($conn,$sqlTeam);
$cron='set';
$shop_id=6;


while($rowTeam = mysqli_fetch_object($resTeam)){
	
$sqlUser = "SELECT id,name FROM ".TBL_USERS." WHERE FIND_IN_SET ('".$rowTeam->id."',".TBL_USERS.".ids_team) AND NOT FIND_IN_SET(id,'".$rowTeam->id_user_level_1.",".$rowTeam->id_user_level_2."') AND id_shop=6";
	
	$resUsers = mysqli_query($conn,$sqlUser);
	$numUser = mysqli_num_rows($resUsers);
	
	if($numUser>0){
		$memberArray = array();
		while($rowUser = mysqli_fetch_object($resUsers)){
			$teamId = $rowTeam->id;
			$fileName = $rowUser->name;
			$setUserArr=array();
			$setUserArr = explode(',',$rowTeam->ids_user_monthly_reporting);
			$i=0;
			$setEmail = array();
			while($i<count($setUserArr)){
				array_push($setEmail,selectColumn(TBL_USERS,'email','WHERE id="'.$setUserArr[$i].'" '));
				$i++;
			} 
			$toEmails = implode(',',$setEmail);
			$dateStr = date('d-m-Y');
			$checkin =date('01-m-Y',strtotime('-1 month',strtotime($dateStr)));
			$checkout = date('t-m-Y',strtotime('-1 month',strtotime($dateStr)));
			$id_user = $rowUser->id;
			$mailCc = selectColumn(TBL_USERS,'email','where id="'.$id_user.'" ');
			
			 $objPHPExcel = new PHPExcel();
			 activityLogReport($checkin,$checkout,$id_user,$shop_id,$cron,$conn,$objPHPExcel,$fileName);
			 unset($objPHPExcel);
			 //server
			 $attach = str_replace('crs','sales',$path).'/adminpanel/mailattach/'.$fileName.'.xls';

			  //local
			 //$attach = '../mailattach/'.$fileName.'.xls';
			 $sendMail = new sendMail;
			 $mailto = 'test';
			 
			 if($mailCc !='' && $mailto !='' && file_exists($attach)){
			 	//$sendMail->sendRateMail('donotreply@roomstatushub.com',$toEmails,$fileName .' Activity Log Report',' PFA Activity log report',$mailCc,$attach);
			 	$sendMail->sendRateMail('donotreply@roomstatushub.in','support@roomstatushub.in',$fileName .' Activity Log Report',' PFA Activity log report','support1roomstatushub.in',$attach);
				}
			 $mailCc='';
			 $mailto='';		
			 unset($sendMail);
			 if(file_exists($attach)){
			 	unlink($attach);
			 }	

		}
		
		
	}
	
}


//echo '<script>alert("test")</script>';
exit;
?>