<?php
//error_reporting(E_ALL);
set_time_limit(600);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sync/adminpanel/cronjobs/executivePortfolioYearlyReportAutoMailer.php /dev/null 2>&1 */

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

include_once(str_replace('crs', 'sales',$path)."/adminpanel/includes/reportFunctions.php");

//local PATH
//include_once("../includes/reportFunctions.php");
$DB_NAME='inroomhu_fern';
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew=$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

$sqlTeam  = "SELECT id,name,id_user_level_1,ids_user_monthly_reporting FROM ".TBL_TEAM." WHERE id_shop=6 ";

$resTeam  = mysqli_query($conn,$sqlTeam);
$cron='set';
$shop_id=6;

$content="Dear All,<br><br>

Kindly find attach Executive Yearly Portfolio Report. <br><br>

For any technical Support please call 09810164525 or mail us at support@roomstatushub.com <br><br>

Thanks & Regards,<br>
RoomStatusHUB <br>
(Support Team) 
";
while($rowTeam = mysqli_fetch_object($resTeam)){
	
	$sqlUser = "SELECT id,name FROM ".TBL_USERS." WHERE FIND_IN_SET ('".$rowTeam->id."',".TBL_USERS.".ids_team) AND user_type !=2 AND id_shop=6";
	$resUsers = mysqli_query($conn,$sqlUser);
	$numUser = mysqli_num_rows($resUsers);
	
	if($numUser>0){
		$memberArray = array();
		while($rowUser = mysqli_fetch_object($resUsers)){
			$teamId = $rowTeam->id;
			$fileName = $rowUser->name." Yearly Protfolio Report";
			$setUserArr=array();
			$setUserArr = explode(',',$rowTeam->ids_user_monthly_reporting);
			$i=0;
			$setEmail = array();
			while($i<count($setUserArr)){
				array_push($setEmail,selectColumn(TBL_USERS,'email','WHERE id="'.$setUserArr[$i].'" '));
				$i++;
			} 
			$toEmails = implode(',',$setEmail);
			$id_user = $rowUser->id;
			$mailCc = selectColumn(TBL_USERS,'email','where id="'.$id_user.'" ');
			
			 $objPHPExcel = new PHPExcel();
			 executivePortFolioYearlyReport($cron,$shop_id,$id_user,date('Y-m-d'),$fileName,$connNew,$objPHPExcel);
			 
			 //server
			 $attach = '/home/inroomhub/public_html/sync/adminpanel/mailattach/'.$fileName.'.xls';

			  //local
			 //$attach = '../mailattach/'.$fileName.'.xls';
			 
			 $sendMail = new sendMail;
			 $mailto = 'test';
			 
			 if($mailCc !='' && $mailto !='' && file_exists($attach)){
			 	$sendMail->sendRateMail('donotreply@roomstatushub.com',$toEmails,$fileName,$content,$mailCc,$attach);

			 	//$sendMail->sendRateMail('donotreply@roomstatushub.com','hiteshaloney75@gmail.com',$fileName,$content,'support@roomstatushub.com',$attach);
			}
			 $mailCc='';
			 $mailto='';		
			 unset($sendMail);

			 if(file_exists($attach)){
			 	unlink($attach);
			 }

			 unset($objPHPExcel);

		}
		
		
	}
	
}


//echo '<script>alert("test")</script>';

exit;
?>