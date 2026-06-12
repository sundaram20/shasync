<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['followup_type']	== 1){

$checkFollowExisting = executeSql(" SELECT  `".TBL_FOLLOWUP_DETAILS."`.*  FROM `".TBL_FOLLOWUP_DETAILS."`  WHERE `".TBL_FOLLOWUP_DETAILS."`.`id` = '".addslashes($_REQUEST['followup_id'])."' ");

	$row = $db->fetch_object2($checkFollowExisting);
	
$updateInventory = executeSql("UPDATE  `".TBL_FOLLOWUP_DETAILS."`  SET 
									`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."'
									,`assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."',
									`follow_up_summary`='".addslashes($_REQUEST['followup_description'])."'
									where  `id`='".addslashes($_REQUEST['followup_id'])."'");
									
$updateInventory = executeSql("UPDATE  `".TBL_DAILY_CALENDER."`  SET 
									`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',`assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."'
									where  `doc_id`='".addslashes($_REQUEST['followup_id'])."'");
									
/*$updatefollowup = "UPDATE `".TBL_FOLLOWUP_DETAILS_EXPLOAD."` SET 
		  				  
						  `assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."',
						  						  
						  `summary`='".addslashes($_REQUEST['followup_description'])."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
						  ";
						 
	$updatefollowup .= "
							`last_modified` = '".currenDateTime()."'							
							,`status` = '1'
							WHERE details_id='".addslashes($_REQUEST['followup_id'])."' "; */

$insertfollowup = "INSERT INTO `".TBL_FOLLOWUP_DETAILS_EXPLOAD."` SET 
		  				  `details_id`='".addslashes($row->id)."',
						  `visit_id`='".addslashes($row->visit_id)."',
						   `type` = '2',	
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `hotel_id`='".addslashes($row->hotel_id)."',	
						  `id_user` = '".addslashes($_SESSION['userId'])."',
						   `assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."',						  
						  `summary`='".addslashes($_REQUEST['followup_description'])."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
						  `lead_status`='1'";
						 
$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'";
							
						  
				
							
				if(executeSql($insertfollowup)){
					if(addslashes($_REQUEST['assign_user_id'])!=$_SESSION['userId']){
				$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
								`id_shop`='".$_SESSION['shop']."',
								`source`='Follow Up',
								`id_user_assigned_to`='".addslashes($_REQUEST['assign_user_id'])."',
								`id_user_assigned_by`='".$_SESSION['userId']."',
								`dated` ='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
								`message`='".addslashes($_REQUEST['followup_description'])."',";

					
					$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
								`date_created`='".date('Y-m-d H:i:s')."',
								`id_mst_user_created_by`='".$_SESSION['userId']."',
								`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

					
					executeSql($sqlNotify);
				}
			}	
	echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Follow Up has been updated sucessfully.<br>please wait...</p><script>window.setTimeout(function() {window.location.href = "editDailyReport.php";}, 2000);</script>';
				
}

if($_REQUEST['followup_type']	== 3){
	
	
$checkFollowExisting = executeSql(" SELECT  `".TBL_FEEDBACK_DETAILS."`.*  FROM `".TBL_FEEDBACK_DETAILS."`  WHERE `".TBL_FEEDBACK_DETAILS."`.`id` = '".addslashes($_REQUEST['followup_id'])."' ");

	$row = $db->fetch_object2($checkFollowExisting);
	
$updateInventory = executeSql("UPDATE  `".TBL_FEEDBACK_DETAILS."`  SET 
									`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
									`assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."'
									where  `id`='".addslashes($_REQUEST['followup_id'])."'");
									
$updateInventory = executeSql("UPDATE  `".TBL_DAILY_CALENDER."`  SET 
									`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',`assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."'
									where  `doc_id`='".addslashes($_REQUEST['followup_id'])."' and visit_id	='".addslashes($_REQUEST['daily_Visit_id'])."' and type	='".addslashes($_REQUEST['followup_type'])."' ");
									
									
$insertfollowup = "INSERT INTO `".TBL_FEEDBACK_DETAILS_EXPLOAD."` SET 
		  				  `details_id`='".addslashes($row->id)."',
						  `visit_id`='".addslashes($row->visit_id)."',
						  `id_shop` = '".addslashes($_SESSION['shop'])."',
						  `assign_user_id`='".addslashes($_REQUEST['assign_user_id'])."',
						   `type` = '3',	
						  `hotel_id`='".addslashes($row->hotel_id)."',	
						  `id_user` = '".$row->id_user."',					  
						  `summary`='".addslashes($_REQUEST['followup_description'])."',
						  `dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
						  `lead_status`='".addslashes($row->lead_status)."'";
						 
				$insertfollowup .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'							
							,`status` = '1'"; 
						  
if(executeSql($insertfollowup)){
			if(addslashes($_REQUEST['assign_user_id'])!=$_SESSION['userId']){	
				

				$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
								`id_shop`='".$_SESSION['shop']."',
								`source`='Feedback',
								`id_user_assigned_to`='".addslashes($_REQUEST['assign_user_id'])."',
								`id_user_assigned_by`='".$_SESSION['userId']."',
								`dated` ='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
								`message`='".addslashes($_REQUEST['followup_description'])."',";

					
					$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
								`date_created`='".date('Y-m-d H:i:s')."',
								`id_mst_user_created_by`='".$_SESSION['userId']."',
								`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

					
					executeSql($sqlNotify);
				}
			}	

				echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Follow Up has been updated sucessfully.<br>please wait...</p><script>window.setTimeout(function() {window.location.href = "editDailyReport.php";}, 2000);</script>';
				
}

if($_REQUEST['followup_type']	== 4){
	
	/*echo "<pre>";
	print_r($_SESSION);
	echo "-------------request--------<br>";
	print_r($_REQUEST);
	echo "</pre>";
	exit;*/
	
	$checkFollowExisting = executeSql(" SELECT  `".TBL_DAILY_ENQUERY_DETAILS."`.*  FROM `".TBL_DAILY_ENQUERY_DETAILS."`  WHERE `".TBL_DAILY_ENQUERY_DETAILS."`.`id` = '".addslashes($_REQUEST['followup_id'])."'");

	$row = $db->fetch_object2($checkFollowExisting);

	$updateInventory = executeSql("UPDATE  `".TBL_DAILY_ENQUERY."`  SET 
									`follow_up_date`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
									`assign_user_id`='".$_REQUEST['assign_user_id']."',
									`follow_up_summary`='".$_REQUEST['followup_description']."',
									`follow_up_date`='".date('Y-m-d',strtotime($_REQUEST['followup_date']))."'
									where  `id`='".$row->enquiry_id."'");

	$insertfollowup = "INSERT INTO `".TBL_DAILY_ENQUERY_DETAILS."` SET 
				 					 		
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
						`followup_open_type_id` = '".$_REQUEST['open_type']."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',	
						`type`='4',
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
									where   visit_id	='".addslashes($row->enquiry_id)."' AND `enquiry_details`='1' and type	='4'
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
									
									
	/* $insertfollowup = "INSERT INTO `".TBL_DAILY_ENQUERY_DETAILS."` SET 
				 		
				 		
						`enquiry_id`='".addslashes($row->id)."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',						
						
						`type`='4',
						
						
						`created_by`='".addslashes($_SESSION['userId'])."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
						`lead_status` = '".addslashes($row->lead_status)."'";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						
						executeSql($insertfollowup);*/
						
						
	
	
	
echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Lead Follow Up has been updated sucessfully.<br>Please Wait...</p>';	
}

if($_REQUEST['followup_type']	== 5){
	
	/*echo "<pre>";
	print_r($_SESSION);
	echo "-------------request--------<br>";
	print_r($_REQUEST);
	echo "</pre>";
	exit;*/
	
	$checkFollowExisting = executeSql(" SELECT  `".TBL_SALES_QUOTE_FOLLOWUP."`.*  FROM `".TBL_SALES_QUOTE_FOLLOWUP."`  WHERE `id` = '".addslashes($_REQUEST['followup_id'])."'");

	$row = $db->fetch_object2($checkFollowExisting);

	$updateInventory = executeSql("UPDATE  `".TBL_SALES_QUOTE."`  SET 
									`follow_up_date`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
									`assign_user_id`='".$_REQUEST['assign_user_id']."',
									`follow_up_summary`='".$_REQUEST['followup_description']."'
									
									where  `id`='".$row->id_quote."'");

	$insertQuoteFoll="INSERT INTO ".TBL_SALES_QUOTE_FOLLOWUP." 
					 SET id_quote='".$row->id_quote."',
					 	 id_company='".$row->id_company."',
					 	 type=5,
					 	 hotel_id='".$row->hotel_id."',
					 	 id_contact='".$row->id_contact."',
					 	 id_user='".$row->id_user."',
					 	 assign_user_id='".$_REQUEST['assign_user_id']."',
					 	 dated='".date('Y-m-d',strtotime($_REQUEST['followup_date']))."',
					 	 created_date='".date('Y-m-d')."',
					 	 title='".$row->title."',
					 	 first_name='".$row->first_name."',
					 	 last_name='".$row->last_name."',
					 	 email='".$row->email."',
					 	 mobile='".$row->mobile."',
					 	 details='".$_REQUEST['followup_description']."',
					 	 id_shop='".$_SESSION['shop']."',
					 	 status=1,
					 	 lead_status=1,
					 	 id_mst_user_created_by='".$_SESSION['userId']."',
					 	 id_mst_user_modified_by='".$_SESSION['userId']."'
					 	  ";								
	mysqli_query($connNew,$insertQuoteFoll)	;

	$insertId = mysqli_insert_id($connNew);							
	$quoteSQl="UPDATE  `".TBL_DAILY_CALENDER."`  SET 
									`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
									`doc_id`='".$insertId."',
									`assign_user_id`='".$_REQUEST['assign_user_id']."'
									where  `visit_id`='".$row->id_quote."' and  `enquiry_details`='1' and type	='5'";																
	$updateInventory = executeSql($quoteSQl);
	
	if($updateInventory){
		if($_REQUEST['assign_user_id'] != $_SESSION['userId']){
		$sqlNotify = "INSERT INTO ".TBL_NOTIFICATION." SET
					`id_shop`='".$_SESSION['shop']."',
					`source`='Quote Follow Up',
					`id_user_assigned_to`='".$_REQUEST['assign_user_id']."',
					`id_user_assigned_by`='".$_SESSION['userId']."',
					`dated` ='".date('Y-m-d',strtotime($_REQUEST['followup_date']))."',
					`message`='".$_REQUEST['followup_description']."',";

		
		$sqlNotify .="`last_modified`='".date('Y-m-d H:i:s')."',
					`date_created`='".date('Y-m-d H:i:s')."',
					`id_mst_user_created_by`='".$_SESSION['userId']."',
					`id_mst_user_modified_by`='".$_SESSION['userId']."' ";

		
		//executeSql($sqlNotify);	
		}	
	}
									
									
	/* $insertfollowup = "INSERT INTO `".TBL_DAILY_ENQUERY_DETAILS."` SET 
				 		
				 		
						`enquiry_id`='".addslashes($row->id)."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',						
						
						`type`='4',
						
						
						`created_by`='".addslashes($_SESSION['userId'])."',						
						`created_date`  = '".date('Y-m-d')."',
						`dated`='".addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date'])))."',
						`lead_status` = '".addslashes($row->lead_status)."'";
			  
						$reminderDate = date('Y-m-d',strtotime($_SESSION['followup_date'][$dataCode]));	
						
						executeSql($insertfollowup);*/
echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Quote Follow Up has been updated sucessfully.<br>Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageQuote.php";}, 1000);</script>';	
}


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
									`follow_up_date`='".date('Y-m-d',strtotime($_REQUEST['followup_date']))."'
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
						
						
	
	
	
echo '<p class="help-block">'.addslashes(date('Y-m-d',strtotime($_REQUEST['followup_date']))).' Lead Follow Up has been updated sucessfully.<br>Please Wait...</p>';	
}

if($_REQUEST['followup_type']	== 7){
	
	echo "<pre>";
	print_r($_SESSION);
	echo "-------------request--------<br>";
	print_r($_REQUEST);
	echo "</pre>";
	exit;
	
echo 'calender update file opened';
}

?>