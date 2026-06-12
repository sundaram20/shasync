<?php

include_once("../../config/auto_loader.php");



$shop_id	 = $_SESSION['shop'];
$user_id	 = $_REQUEST['user_id'];
$_REQUEST['endDate']=date('Y-m-d',strtotime('-1 days',strtotime($_REQUEST['endDate'])));  


$ExeUserName	=	selectColumn('fs_users','name'," WHERE `id` = '".addslashes($user_id)."'");
$myownteam_id	=	selectColumn('fs_users','myownteam_id'," WHERE `id` = '".addslashes($user_id)."'");
$TeamName		=	selectColumn('mst_team','name'," WHERE `id` = '".addslashes($myownteam_id)."'");

$allocation_date ="And ( allocation_date BETWEEN '".date('Y-m-d',strtotime($_REQUEST['startDate']))."' And '".date('Y-m-d',strtotime($_REQUEST['endDate']))."')";

$sqlFollowUpExplode1 = "SELECT * FROM `fs_weeklyplanner`  WHERE `user_id` ='".$user_id."' ".$allocation_date." ORDER BY allocation_date asc";

$resQue = mysqli_query($connNew,$sqlFollowUpExplode1);
$numRows= mysqli_num_rows($resQue);
if($numRows==0){
	
	echo $message = "Weekly Planner is Null";
	die;
	}


$WeeklyPlanner=array();
while($RowFollowUpExplode=mysqli_fetch_object($resQue)){
	$id_account	= $RowFollowUpExplode->id_account;
	$type	= $RowFollowUpExplode->type;
	
	
	if($type=='1'){
		
		
		if($id_account=='1'){
		$DataPlanner	=	'Visit';// Existing Customer';
		}else{
			$DataPlanner	=	'Visit'; // 'New Account';
			
			}
		
		}else{
			
			$DataPlanner	=	'Activity';
			
			}
	
	
	//print_r($RowFollowUpExplode);
	if($RowFollowUpExplode->description!=''){
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['description']=$RowFollowUpExplode->description;
	}else{
		
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['description']='-';	
		}
		
		
		
		if($RowFollowUpExplode->id_company>0){
		
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['company_name']=selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".addslashes($RowFollowUpExplode->id_company)."'"); 
		
		}elseif($RowFollowUpExplode->id_other_activity>0){
		
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['company_name']=selectColumn('sales_other_activity','name'," WHERE `id` = '".addslashes($RowFollowUpExplode->id_other_activity)."'");  	
		}elseif($id_account=='2'){
		
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['company_name']='New Account';
		}
		
		
		
		
	if($RowFollowUpExplode->id_contact>0){
		$sqlCus = "SELECT * FROM fs_customer  WHERE  type='2' and id_customer='".addslashes($RowFollowUpExplode->id_contact)."'";

	$resCus = mysqli_query($connNew,$sqlCus);
	$Rowcus=mysqli_fetch_object($resCus);
	$Custome			= $Rowcus->title.''.$Rowcus->first_name.' '.$Rowcus->last_name;
	
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['company_contact']=$Custome;
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['contact_mobile']=$Rowcus->mobile;

	
	}elseif($RowFollowUpExplode->contact_name!=''){
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['company_contact']=$RowFollowUpExplode->contact_name;
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['contact_mobile']=$RowFollowUpExplode->contact_mobile;
		
	}else{
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['company_contact']='-';
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['contact_mobile']='-';
		}
		
		
		
		
		
	   $WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['type']=$DataPlanner;
	
	
	
	if($RowFollowUpExplode->contact_mobile!=''){
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['contact_mobile']=$RowFollowUpExplode->contact_mobile;
	}else{
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['contact_mobile']='-';
		}
	
	if($RowFollowUpExplode->id_other_activity>0){
	$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['activity']=selectColumn('sales_other_activity','name'," WHERE `id` = '".addslashes($RowFollowUpExplode->id_other_activity)."'"); 
	}else{
		$WeeklyPlanner[$RowFollowUpExplode->allocation_date][$DataPlanner][$RowFollowUpExplode->id]['activity']='-'; 
		}
}








//debugData($WeeklyPlanner);


$content = '<style>
.bordered tr th{
    border: 1px solid black !important; border-collapse: collapse !important;
    border-collapse: collapse !important;
  }
  
  .bordered tr td{
    border: 1px solid black !important;
    border-collapse: collapse !important;
  }
</style>';

$content .= '<div class="container">
  
  &nbsp;
  
  
  <table class="bordered" width="800" style="border: 1px solid black !important; border-collapse: collapse !important;">
    <tbody>';
	
	$content .= '<tr align="center" style="background-color: #4f81bc;color:#fff; 
	">
        <th style="color:#fff; border: 1px solid black !important;" colspan="4"><b>Weekly Planner '.date('M jS ',strtotime($_REQUEST['startDate'])).' - '.date('M jS',strtotime($_REQUEST['endDate'])).'  ('.$ExeUserName.' '.$TeamName.')
</b></th>
        
      </tr>';
	  $content .= '<tr align="center" style="color:#000;
	  ">
        <th style="color:#000; border: 1px solid black !important;
		"><b>Type</b></th>
        <th style="color:#000; border: 1px solid black !important;
		"><b>Connect</b></th>        
        <th style="color:#000; border: 1px solid black !important;
		"><b>Focus</b></th>
     
      </tr>';
		foreach($WeeklyPlanner as $planDate=>$plannerData){
			
			$content .= '<tr align="left" style="color:#000;">
        <th style="color:#000;margin-left:10px !important;  border: 1px solid black !important;
		"  colspan="4"><b>'.date('jS l',strtotime($planDate)).'</b></th>
        
      </tr>';
	  
	  foreach($plannerData as $plannervalue){ 
		foreach($plannervalue as $plannervalue2){  
		
		
		$CompanyContact	=$plannervalue2['company_contact'];
		$CompanyMobile=	$plannervalue2['contact_mobile'];
		$content .= '<tr align="center" class="table-bordered">
        <td style="border: 1px solid black !important;">'.$plannervalue2['type'].'</td>
		<td style="border: 1px solid black !important;">'.$plannervalue2['company_name'].'<br>'.$CompanyContact.'<br>'.$CompanyMobile.'</td>       
       <td style="border: 1px solid black !important;"> '.$plannervalue2['description'].'</td>
       
      </tr>';
		}
		  
	  }
			}	
			
;
	  
	  

	  
	  
     
   $content .= ' </tbody>
  </table>
  
 
  
<div class="row">
  <div class="col-sm-12 text-muted  no-shadow">
    
    With Kind Regards,<br />
   
    <br />
   </div>
</div>';


//echo  $content;die;

// To send HTML mail, the Content-type header must be set

$headers[] = 'MIME-Version: 1.0';

$headers[] = 'Content-type: text/html; charset=iso-8859-1';



// Additional headers

$headers[] = 'To: '.$_POST['mailId'];


	$From='roomstatushublogs@gmail.com';


	 $myownteam_id	=selectColumn(TBL_USERS,'myownteam_id','WHERE id="'.$_SESSION['userId'].'" and status="1" ');	
	 $idsTo = selectColumn(TBL_TEAM,'ids_user_dsr_reporting','WHERE  id="'.$myownteam_id.'"');

	$idsArray =  explode(',', $idsTo);
	$to = array();
	for($i=0 ;$i<count($idsArray);$i++){
		$email = selectColumn(TBL_USERS,'email','WHERE id="'.$idsArray[$i].'" ');
		if(!is_null($email)){
		    array_push($to, $email);
		    
		}
	}
	
//	print_r($to);
	//die;

$cc = selectColumn(TBL_USERS,'email','WHERE id="'.$_SESSION['userId'].'" ');


//mail($_POST['mailId'], $_POST['subject'], $_POST['sendcontent'], implode("\r\n", $headers));
$smtp_email	= selectColumn(TBL_SHOP,'smtp_email'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
$smtp_password	=selectColumn(TBL_SHOP,'smtp_password'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
$smtp_host	= selectColumn(TBL_SHOP,'smtp_host'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
$smtp_port	=selectColumn(TBL_SHOP,'smtp_port'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");


	define ('GUSER',"support@roomstatushub.com");
	define ('GPWD',"simlim9876#");	
	
//$to=$_POST['mailId'];
$From=$From;
$sub= 'Weekly Planner '.date('M jS ',strtotime($_REQUEST['startDate'])).' - '.date('M jS',strtotime($_REQUEST['endDate'])).'  ('.$ExeUserName.' '.$TeamName.')';
$content=$content;

//$recipients =explode(",",$_POST['ccId']);

$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
//$mail->Username = "support@roomstatushub.com";
//$mail->Password = "room9876#";

$mail->Username = GUSER;
$mail->Password = GPWD;
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $content;
//$mail->AddAddress($From);	
	
foreach($to as $toname)
{	

   $mail->AddAddress($toname);
}	
//foreach($recipients as $name)
//{	
//echo '<br>=='.$name;
   $mail->AddCC($cc);
//}
$mail->AddCC($cc);
	
$mail	=	$mail->Send();




if($mail==1){
echo $message = "Mail Sent Successfully";
}else{
echo $message = "Cannot send email now. Try again later.";

}
//unlink($attachPath.$file_name);
//echo "<script type='text/javascript'>alert('$message');window.location.href='weeklyPlanner.php';</script>";




?>