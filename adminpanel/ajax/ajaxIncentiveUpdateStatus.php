<?php  include_once("../../config/auto_loader.php");

//die;
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
$id_incentive_previous		     =	$_REQUEST['id_incentive_previous'];
$user_id				   		   =	$_REQUEST['id_user_previous'];
$id_forward_for_approval_previous  =	$_REQUEST['id_forward_for_approval_previous'];
$status_incentive_id			   =	$_REQUEST['status_incentive_id'];
$incentive_remark				  =	$_REQUEST['incentive_remark'];
$revenue				           =	$_REQUEST['revenue'];
$approved_amount				           =	$_REQUEST['approved_amount'];



if($status_incentive_id==1){
	$user_id=$_REQUEST['id_forward_for_approval'];
	}
/*if($status_incentive_id==3)
		$approved_amount=$approved_amount;
	else
		$approved_amount=0;*/
	$User_type	=	selectColumn(TBL_USERS,'user_type'," WHERE `id` = '".addslashes($user_id)."'");
	if($User_type=='2'){
		$checkout	=	selectColumn(TBL_INCENTIVE,'checkout'," WHERE `id` = '".addslashes($id_incentive_previous)."'");
		$Dated =date('Y-m-d',strtotime(' +1 day',strtotime($checkout)));
		}else{
			$Dated =date('Y-m-d');
			}	
$sqlNotify = "INSERT INTO ".TBL_INCENTIVE_DETAILS." SET
					`id_incentive`='".$id_incentive_previous."',
					`status`='".$status_incentive_id."',
					`remarks`='".$incentive_remark."',
					`approved_amount`='".$approved_amount."',
					
					`id_user`='".$_SESSION['userId']."',
					`id_forward_for_approval`='".$user_id."',
					`dated` ='".$Dated."',
					
					";

		
		 $sqlNotify .="
					`date_created`='".date('Y-m-d H:i:s')."',
					`created_by`='".$_SESSION['userId']."',
					`modified_by`='".$_SESSION['userId']."' ";
					
	mysqli_query($connNew,$sqlNotify);	
	$insertIdDetails = mysqli_insert_id($connNew);
	
	
	
		
	$sqlNotify = "UPDATE ".TBL_INCENTIVE." SET					
					`current_status`='".$status_incentive_id."',
					`approved_amount`='".$approved_amount."',
					
					`id_currently_with`='".$user_id."'				
					
					";

		
		$sqlNotify .="
					WHERE `id`='".$id_incentive_previous."'";
					
	mysqli_query($connNew,$sqlNotify);
	
	
	
	//CALENDER
	$insertCalendar = "UPDATE `".TBL_DAILY_CALENDER."` SET 
			
			`id_user`='".addslashes($_SESSION['userId'])."',
			`doc_id` ='".$insertIdDetails."',
			`assign_user_id` = '".$user_id."',
			`dated`='".$Dated."'
			
			";
$insertCalendar .= "WHERE `visit_id` ='".addslashes($id_incentive_previous)."' and `type`='7' ";
			mysqli_query($connNew,$insertCalendar);
	//CALENDER
	//CALENDER

	/*$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
			`enquiry_details`='1',
			`type`='7',
			`id_shop` = '".addslashes($_SESSION['shop'])."',
			`id_user`='".addslashes($_SESSION['userId'])."',
			`doc_id` ='".$insertIdDetails."',
			`assign_user_id` = '".$user_id."',
			`dated`='".$Dated."',
			`visit_id` ='".addslashes($id_incentive_previous)."',
			`status` = '1'";

			mysqli_query($connNew,$insertCalendar);*/
	//CALENDER

	$enquiry_id=selectColumn(TBL_INCENTIVE,'id_enquiry',"WHERE  id = '".$id_incentive_previous."' ");
	$eId=encryptor('encrypt',$enquiry_id);

echo '<p class="help-block">Incentive has been Updated Sucessfully.</p><script>window.setTimeout(function() {window.location.href = "manageIncentive.php";}, 2000);</script>';



?>
