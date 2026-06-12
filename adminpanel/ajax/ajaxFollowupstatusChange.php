<?php  include_once("../../config/auto_loader.php");

include_once("../includes/incentiveFunctions.php");

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";*/
//exit;
$status					=	$_REQUEST['followup_status_hidden'];
$user_id				   =	$_REQUEST['followup_id_hidden'];
$followup_close_summary	=	$_REQUEST['followup_close_summary'];
$close_type				=	$_REQUEST['close_type'];
$Type					  =	$_REQUEST['followup_hidden_type'];
$revenue				   =	$_REQUEST['revenue'];
$commission				   =	$_REQUEST['commission'];

if($Type	==1){



$closeSql = "UPDATE `".TBL_FOLLOWUP_DETAILS."` SET 
            lead_status='0'
            ,followup_close_summary='$followup_close_summary' 
            ,followup_close_type_id='$close_type' 
            WHERE id='$user_id'"; 
           
 
if(executeSql($closeSql)){
  $closeSql = "UPDATE `".TBL_FOLLOWUP_DETAILS_EXPLOAD."` SET 
            lead_status='0'  
            WHERE details_id='$user_id'";

  if(executeSql($closeSql)){
    $closeSql = "UPDATE `".TBL_DAILY_CALENDER."` SET 
              status='0'
              ,type='1'  
              WHERE doc_id='$user_id'";

    executeSql($closeSql);          
  }          
}


/*echo '<td id="ChangeButton"><i data="'.$user_id.'" class="status_checks btn '.$StatusEs.'"  '.$NextFollowUpDisable.'>'.$ActiveINactive.'</td>';

echo '|||<td id="ChangeFollowUpSummary">'.$followup_close_summary.'</td>';

echo '|||<td id="ChangeFollowupButton"> <button data="" class="pull-left btn btn-success btn-xs" type="button"  '.$NextFollowUpDisable.' > 
<i data="'.$user_id.'" class="btn">Next</button> </td>';

echo '&&&&'.$user_id;*/

echo '<p class="help-block">Follow Up has been  Sucessfully close.</p><script>window.setTimeout(function() {window.location.href = "editDailyReport.php";}, 2000);</script>';
}

if($Type	==3){
	
if($status==1){
	   $StatusEs		=	'btn-success';
   		$ActiveINactive	=	"Open";
  }if($status==0){
   		$StatusEs		=	'btn-danger';
      	$ActiveINactive	=	"Close";
		$NextFollowUpDisable	= "disabled";
  }
 
 
$sql	=	executeSql("UPDATE `".TBL_FEEDBACK_DETAILS."` SET lead_status='0',feedback_summary='$followup_close_summary' WHERE id='$user_id'");
if($sql){
  $closeSql = "UPDATE `".TBL_FEEDBACK_DETAILS_EXPLOAD."` SET 
            lead_status='0'
             
            WHERE id='$user_id'";
  executeSql($closeSql);  
}

/*echo '<td id="ChangeButton"><i data="'.$user_id.'" class="status_checks btn '.$StatusEs.'"  '.$NextFollowUpDisable.'>'.$ActiveINactive.'</td>';

echo '|||<td id="ChangeFollowUpSummary">'.$followup_close_summary.'</td>';

echo '|||<td id="ChangeFollowupButton"> <button data="" class="pull-left btn btn-success btn-xs" type="button"  '.$NextFollowUpDisable.' > 
<i data="'.$user_id.'" class="btn">Next</button> </td>';

echo '&&&&'.$user_id;*/

echo '<p class="help-block"> FeedBack has been  Sucessfully close.</p><script>window.setTimeout(function() {window.location.href = "editDailyReport.php";}, 2000);</script>';


}

if($Type	==4){
	/*print_r($_REQUEST);
	$ClaimIncentive = !empty($_POST['ClaimIncentive'])?$_POST['ClaimIncentive']:'off';
	if($ClaimIncentive=='on'){	
	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$_SESSION['userId'],$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	}
	die;*/
if($revenue==''){
	$revenue=$_REQUEST['revenue_inc'];
	}
	if($commission==''){
	$commission=$_REQUEST['commission_inc'];
	}
		

$enquiry_id=$_REQUEST['daily_Visit_id_hidden'];
$closeEnqSql = "SELECT * FROM ".TBL_DAILY_ENQUERY." WHERE id_shop='".$_SESSION['shop']."' and   id='".$enquiry_id."'";
$closeEnqQuery= mysqli_query($connNew,$closeEnqSql);

$resultcloseEnq=mysqli_fetch_object($closeEnqQuery);




 $insertfollowup = "INSERT INTO `".TBL_DAILY_ENQUERY_DETAILS."` SET 
				 					 		
						`enquiry_id`='".addslashes($enquiry_id)."',
						`id_company`='".$resultcloseEnq->id_company."',
						`hotel_id`='".$resultcloseEnq->hotel_id."',
						`id_user`='".$resultcloseEnq->id_user."',
						`id_contact`='".$resultcloseEnq->id_contact."',
						`assign_user_id`='0',
						
						
						`id_shop` = '".addslashes($_SESSION['shop'])."',	
						`details`='".$followup_close_summary."',
						`enquiry_close_summary`='".$followup_close_summary."',
						`revenue`='".$revenue."', 
						`commission`='".$commission."', 						
						
						`followup_close_type_id` = '".$_REQUEST['close_type']."',	
						`type`='4',
						`lead_status`='0',
						`created_by`='".$_SESSION['userId']."',
						`modified_by`='".$_SESSION['userId']."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".date('Y-m-d')."'
						";
						
	mysqli_query($connNew,$insertfollowup);
	
	
	$ClaimIncentive = !empty($_POST['ClaimIncentive'])?$_POST['ClaimIncentive']:'off';
	if($ClaimIncentive=='on'){	
	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$_SESSION['userId'],$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	}
	

$sql  = executeSql("UPDATE `".TBL_DAILY_ENQUERY."` SET lead_status='0'  WHERE id='".$enquiry_id."' ");
  
if($sql){

  $sql	=	executeSql("UPDATE `".TBL_DAILY_ENQUERY_DETAILS."` SET lead_status='0',followup_close_type_id='".$_REQUEST['close_type']."',enquiry_close_summary='".$followup_close_summary."',revenue='".$revenue."',commission='".$commission."'  WHERE enquiry_id='".$enquiry_id."' ");






  if($sql){
      $closeSql = "UPDATE `".TBL_DAILY_CALENDER."` SET 
                status='0'
                ,type='4'  
                WHERE   visit_id='".$enquiry_id."'";

      executeSql($closeSql);          
    }  
}

/*echo '<td id="ChangeButton"><i data="'.$user_id.'" class="status_checks btn '.$StatusEs.'"  '.$NextFollowUpDisable.'>'.$ActiveINactive.'</td>';

echo '|||<td id="ChangeFollowUpSummary">'.$followup_close_summary.'</td>';

echo '|||<td id="ChangeFollowupButton"> <button data="" class="pull-left btn btn-success btn-xs" type="button"  '.$NextFollowUpDisable.' > 
<i data="'.$user_id.'" class="btn">Next</button> </td>';

echo '&&&&'.$user_id;*/

echo '<p class="help-block"> Your Enquiry has been  Sucessfully close.<br>Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageEnquiry.php";}, 2000);</script>';


}
if($Type  ==5){

$quote_id=selectColumn(TBL_SALES_QUOTE_FOLLOWUP,'id_quote','WHERE id="'.$user_id.'" ');

$sql  = executeSql("UPDATE `".TBL_SALES_QUOTE."` SET lead_status='0' WHERE id='".$quote_id."' ");

if($sql){
  $sql  = executeSql("UPDATE `".TBL_SALES_QUOTE_FOLLOWUP."` SET lead_status='0',followup_close_type_id='".$_REQUEST['close_type']."',quote_close_summary='".$followup_close_summary."' WHERE id_quote='".$quote_id."' ");

  if($sql){
      $closeSql = "UPDATE `".TBL_DAILY_CALENDER."` SET 
                status='0'
                ,type='5'  
                WHERE visit_id='".$quote_id."'  ";

      executeSql($closeSql);          
    } 
}   

/*echo '<td id="ChangeButton"><i data="'.$user_id.'" class="status_checks btn '.$StatusEs.'"  '.$NextFollowUpDisable.'>'.$ActiveINactive.'</td>';

echo '|||<td id="ChangeFollowUpSummary">'.$followup_close_summary.'</td>';

echo '|||<td id="ChangeFollowupButton"> <button data="" class="pull-left btn btn-success btn-xs" type="button"  '.$NextFollowUpDisable.' > 
<i data="'.$user_id.'" class="btn">Next</button> </td>';

echo '&&&&'.$user_id;*/

echo '<p class="help-block"> Your Quote Followup has been  Sucessfully close.<br>Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageQuote.php";}, 1000);</script>';


}

if($Type	==6){
	/*print_r($_REQUEST);
	$ClaimIncentive = !empty($_POST['ClaimIncentive'])?$_POST['ClaimIncentive']:'off';
	if($ClaimIncentive=='on'){	
	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$_SESSION['userId'],$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	}
	die;*/
if($revenue==''){
	$revenue=$_REQUEST['revenue_inc'];
	}	
	if($commission==''){
	$commission=$_REQUEST['commission_inc'];
	}	
$enquiry_id=$_REQUEST['daily_Visit_id_hidden'];
$closeEnqSql = "SELECT * FROM ".INVOICE." WHERE id_shop='".$_SESSION['shop']."' and   id='".$enquiry_id."'";
$closeEnqQuery= mysqli_query($connNew,$closeEnqSql);

$resultcloseEnq=mysqli_fetch_object($closeEnqQuery);




 $insertfollowup = "INSERT INTO `".INVOICE_DETAILS."` SET 
				 					 		
						`enquiry_id`='".addslashes($enquiry_id)."',
						`id_company`='".$resultcloseEnq->id_company."',
						`hotel_id`='".$resultcloseEnq->hotel_id."',
						`id_user`='".$resultcloseEnq->id_user."',
						`id_contact`='".$resultcloseEnq->id_contact."',
						`assign_user_id`='0',
						
						
						`id_shop` = '".addslashes($_SESSION['shop'])."',	
						`details`='".$followup_close_summary."',
						`enquiry_close_summary`='".$followup_close_summary."',
						`revenue`='".$revenue."', 	
						`commission`='".$commission."', 						
					
						`followup_close_type_id` = '".$_REQUEST['close_type']."',	
						`type`='6',
						`lead_status`='0',
						`created_by`='".$_SESSION['userId']."',
						`modified_by`='".$_SESSION['userId']."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".date('Y-m-d')."'
						";
						
	mysqli_query($connNew,$insertfollowup);
	
	
	$ClaimIncentive = !empty($_POST['ClaimIncentive'])?$_POST['ClaimIncentive']:'off';
	if($ClaimIncentive=='on'){	
	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$_SESSION['userId'],$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	}
	

$sql  = executeSql("UPDATE `".INVOICE."` SET lead_status='0'  WHERE id='".$enquiry_id."' ");
  
if($sql){

  $sql	=	executeSql("UPDATE `".INVOICE_DETAILS."` SET lead_status='0',followup_close_type_id='".$_REQUEST['close_type']."',enquiry_close_summary='".$followup_close_summary."',revenue='".$revenue."',commission='".$commission."'  WHERE enquiry_id='".$enquiry_id."' ");






  if($sql){
      $closeSql = "UPDATE `".TBL_DAILY_CALENDER."` SET 
                status='0'
                ,type='6'  
                WHERE   visit_id='".$enquiry_id."'";

      executeSql($closeSql);          
    }  
}

/*echo '<td id="ChangeButton"><i data="'.$user_id.'" class="status_checks btn '.$StatusEs.'"  '.$NextFollowUpDisable.'>'.$ActiveINactive.'</td>';

echo '|||<td id="ChangeFollowUpSummary">'.$followup_close_summary.'</td>';

echo '|||<td id="ChangeFollowupButton"> <button data="" class="pull-left btn btn-success btn-xs" type="button"  '.$NextFollowUpDisable.' > 
<i data="'.$user_id.'" class="btn">Next</button> </td>';

echo '&&&&'.$user_id;*/

echo '<p class="help-block"> Your Payment Invoice has been  Sucessfully close.<br>Please Wait...</p><script>window.setTimeout(function() {window.location.href = "invoices.php";}, 2000);</script>';


}

if($Type	==8){
	/*print_r($_REQUEST);
	$ClaimIncentive = !empty($_POST['ClaimIncentive'])?$_POST['ClaimIncentive']:'off';
	if($ClaimIncentive=='on'){	
	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$_SESSION['userId'],$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	}
	die;*/
if($revenue==''){
	$revenue=$_REQUEST['revenue_inc'];
	}	
	if($commission==''){
	$commission=$_REQUEST['commission_inc'];
	}	
$enquiry_id=$_REQUEST['daily_Visit_id_hidden'];
$closeEnqSql = "SELECT * FROM ".TBL_DAILY_TASK." WHERE id_shop='".$_SESSION['shop']."' and   id='".$enquiry_id."'";
$closeEnqQuery= mysqli_query($connNew,$closeEnqSql);

$resultcloseEnq=mysqli_fetch_object($closeEnqQuery);




 $insertfollowup = "INSERT INTO `".TBL_DAILY_TASK_DETAILS."` SET 
				 					 		
						`enquiry_id`='".addslashes($enquiry_id)."',
						`id_company`='".$resultcloseEnq->id_company."',
						`hotel_id`='".$resultcloseEnq->hotel_id."',
						`id_user`='".$resultcloseEnq->id_user."',
						`id_contact`='".$resultcloseEnq->id_contact."',
						`assign_user_id`='0',
						
						
						`id_shop` = '".addslashes($_SESSION['shop'])."',	
						`details`='".$followup_close_summary."',
						`enquiry_close_summary`='".$followup_close_summary."',
						`revenue`='".$revenue."', 
						`commission`='".$commission."', 						
						
						`followup_close_type_id` = '".$_REQUEST['close_type']."',	
						`type`='8',
						`lead_status`='0',
						`created_by`='".$_SESSION['userId']."',
						`modified_by`='".$_SESSION['userId']."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".date('Y-m-d')."'
						";
						
	mysqli_query($connNew,$insertfollowup);
	
	
	$ClaimIncentive = !empty($_POST['ClaimIncentive'])?$_POST['ClaimIncentive']:'off';
	if($ClaimIncentive=='on'){	
	ClaimIncentiveSave($_REQUEST['id_hotel_inc'],$_REQUEST['guest_name_inc'],$_REQUEST['checkin_inc'],$_REQUEST['checkout_inc'],$_REQUEST['no_room_inc'],$_REQUEST['no_pax_inc'],$_REQUEST['room_rate_inc'],$_REQUEST['banquet_revenue_amount_inc'],$_REQUEST['revenue_inc'],$_REQUEST['id_forward_for_approval'],$connNew,$_SESSION['userId'],$enquiry_id,$_REQUEST['remarks_inc'],$_REQUEST['query_type']);                     
	}
	

$sql  = executeSql("UPDATE `".TBL_DAILY_TASK."` SET lead_status='0'  WHERE id='".$enquiry_id."' ");
  
if($sql){

  $sql	=	executeSql("UPDATE `".TBL_DAILY_TASK_DETAILS."` SET lead_status='0',followup_close_type_id='".$_REQUEST['close_type']."',enquiry_close_summary='".$followup_close_summary."',revenue='".$revenue."',commission='".$commission."'  WHERE enquiry_id='".$enquiry_id."' ");






  if($sql){
      $closeSql = "UPDATE `".TBL_DAILY_CALENDER."` SET 
                status='0'
                ,type='8'  
                WHERE   visit_id='".$enquiry_id."'";

      executeSql($closeSql);          
    }  
}

/*echo '<td id="ChangeButton"><i data="'.$user_id.'" class="status_checks btn '.$StatusEs.'"  '.$NextFollowUpDisable.'>'.$ActiveINactive.'</td>';

echo '|||<td id="ChangeFollowUpSummary">'.$followup_close_summary.'</td>';

echo '|||<td id="ChangeFollowupButton"> <button data="" class="pull-left btn btn-success btn-xs" type="button"  '.$NextFollowUpDisable.' > 
<i data="'.$user_id.'" class="btn">Next</button> </td>';

echo '&&&&'.$user_id;*/

echo '<p class="help-block"> Your Enquiry has been  Sucessfully close.<br>Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageCalls.php";}, 2000);</script>';


}

?>
