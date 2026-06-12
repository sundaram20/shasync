<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



if($_REQUEST['followup_type']	== 6){
	
	/*echo "<pre>";
	print_r($_SESSION);
	echo "-------------request--------<br>";
	print_r($_REQUEST);
	echo "</pre>";
	exit;*/
	
	$checkFollowExisting = executeSql(" SELECT  `".INVOICE_DETAILS."`.*  FROM `".INVOICE_DETAILS."`  WHERE `".INVOICE_DETAILS."`.`id` = '".addslashes($_REQUEST['followup_id'])."'");

	$row = $db->fetch_object2($checkFollowExisting);

	$updateInventory = executeSql("UPDATE  `".INVOICE."`  SET 
									`follow_up_date`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
									`assign_user_id`='".$_REQUEST['assign_user_id']."',
									`follow_up_summary`='".$_REQUEST['followup_description']."',
									`follow_up_date`='".date('Y-m-d',strtotime($_REQUEST['followup_date']))."',
									`lead_status`='2',
										
										`received`= '".addslashes($_REQUEST['new_received'])."',
										`balance`= '".addslashes($_REQUEST['new_balance'])."'
									where  `id`='".$row->enquiry_id."'");

	$insertfollowup = "INSERT INTO `".INVOICE_DETAILS."` SET 
				 					 		
						`enquiry_id`='".addslashes($row->enquiry_id)."',
						`id_company`='".$row->id_company."',
						`hotel_id`='".$row->hotel_id."',
						`id_user`='".$row->id_user."',
						`id_contact`='".$row->id_contact."',
						`assign_user_id`='".$_REQUEST['assign_user_id']."',
						`title`='".$row->title."',
						`first_name`='".$row->first_name."',
						`last_name`='".$row->last_name."',
						`email`='".$row->email."',
						`mobile`='".$row->mobile."',
						`details`='".$_REQUEST['followup_description']."',		
						`id_shop` = '".addslashes($_SESSION['shop'])."',	
						`type`='6',
						`lead_status`='1',
						`created_by`='".$row->created_by."',
						`modified_by`='".$_SESSION['userId']."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."'
						";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						
	mysqli_query($connNew,$insertfollowup);								
	$insertId = mysqli_insert_id($connNew); 
	$updateSQL = "UPDATE  `".TBL_DAILY_CALENDER."`  SET 
									`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
									`assign_user_id`='".$_REQUEST['assign_user_id']."',
									`doc_id`='".$insertId."'
									where   visit_id	='".addslashes($row->enquiry_id)."' AND `enquiry_details`='1' and type	='6'
									";	

	$updateInventory = executeSql($updateSQL);
	
	if($updateInventory){
		if($_REQUEST['assign_user_id'] != $_SESSION['userId']){
		$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
					`id_shop`='".$_SESSION['shop']."',
					`source`='Enquiry Follow Up',
					`id_user_assigned_to`='".$_REQUEST['assign_user_id']."',
					`id_user_assigned_by`='".$_SESSION['userId']."',
					`dated` ='".date('Y-m-d',strtotime($_REQUEST['followup_date']))."',
					`message`='".$_REQUEST['followup_description']."',";

		
		$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
					`date_created`='".date('Y-m-d H:i:s')."',
					`id_mst_user_created_by`='".$_SESSION['userId']."',
					`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

		
		executeSql($sqlNotify);	
		}	
	}
									
									
	/* $insertfollowup = "INSERT INTO `".INVOICE_DETAILS."` SET 
				 		
				 		
						`enquiry_id`='".addslashes($row->id)."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',						
						
						`type`='4',
						
						
						`created_by`='".addslashes($_SESSION['userId'])."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
						`lead_status` = '".addslashes($row->lead_status)."'";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						
						executeSql($insertfollowup);*/
						
						
	
	
	
echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Invoice Follow Up has been updated sucessfully.<br>Please Wait...</p>';	
}

?>