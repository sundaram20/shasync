<?php

//error_reporting(E_ALL);



	//WARNING : If you don't know how to indent a code kindly learn that first : 


	function ClaimIncentiveSave($id_hotel_inc,$guest_name_inc,$checkin_inc,$checkout_inc,$no_room_inc,$no_pax_inc,$room_rate_inc,$banquet_revenue_amount_inc,$revenue_inc,$id_forward_for_approval,$connNew,$user_id,$enquiry_id,$remarks_inc,$query_type){                                      
	
	if($_REQUEST['query_type']=='add'){
	    
	    $created_bySQL	        = mysqli_query($connNew,"SELECT created_by from `".TBL_DAILY_ENQUERY."`  where `id` = '".$enquiry_id."'");
		$created_bySQLRow       = mysqli_fetch_assoc($created_bySQL);
		$created_by	            = $created_bySQLRow['created_by'];
		$hotel_accesssql	    = mysqli_query($connNew,"SELECT hotel_access from `".TBL_USERS."` WHERE `id` = '".$created_by."'");
		$hotel_accessRow        = mysqli_fetch_assoc($hotel_accesssql);
		$SourceHotelNameCreated	= $hotel_accessRow['hotel_access'];


		 $insertIncentive = "INSERT INTO `".TBL_INCENTIVE."` SET 
				 					 		
						`id_enquiry`='".addslashes($enquiry_id)."',
						`id_shop`='".$_SESSION['shop']."',
						`hotel_id`='".$id_hotel_inc."',
						`id_user`='".$_SESSION['userId']."',						
						`id_forward_for_approval`='".$id_forward_for_approval."',
						`revenue`='".$revenue_inc."',
					    `sourcehotel_id`='".$SourceHotelNameCreated."',
						`guest_name`='".$guest_name_inc."',
						`checkin`='".date('Y-m-d',strtotime($checkin_inc))."',
						`checkout`='".date('Y-m-d',strtotime($checkout_inc))."',
						`no_room`='".$no_room_inc."',
						`no_pax`='".$no_pax_inc."',
						`banquet_revenue_amount`='".$banquet_revenue_amount_inc."',		
						`room_rate` = '".$room_rate_inc."',	
						`follow_up_close_summary`='".$remarks_inc."',
						`current_status`='0',
						`id_currently_with`='".$id_forward_for_approval."',
						`created_by`='".$_SESSION['userId']."',
						`modified_by`='".$_SESSION['userId']."',						
						`date_created`  = '".date('Y-m-d H:i:s')."',
						`date_modified`='".date('Y-m-d H:i:s')."'
						";
			
					
	mysqli_query($connNew,$insertIncentive);								
	$insertId = mysqli_insert_id($connNew); 
	
		
		
		
	$sqlNotify = "INSERT INTO ".TBL_INCENTIVE_DETAILS." SET
					`id_incentive`='".$insertId."',
					`remarks`='".$remarks_inc."',
					`id_user`='".$_SESSION['userId']."',
					`id_forward_for_approval`='".$id_forward_for_approval."',
					`dated` ='".date('Y-m-d')."',
					";

		
		$sqlNotify .="
					`date_created`='".date('Y-m-d H:i:s')."',
					`created_by`='".$_SESSION['userId']."',
					`modified_by`='".$_SESSION['userId']."' ";
					
	mysqli_query($connNew,$sqlNotify);
	$insertIdDetails = mysqli_insert_id($connNew); 
	
	
	$insertCalendar = "INSERT INTO `".TBL_DAILY_CALENDER."` SET 
			`enquiry_details`='1',
			`type`='7',
			`id_shop` = '".addslashes($_SESSION['shop'])."',
			`id_user`='".addslashes($_SESSION['userId'])."',
			`doc_id` ='".$insertIdDetails."',
			`assign_user_id` = '".$id_forward_for_approval."',
			`dated`='".date('Y-m-d')."',
			`visit_id` ='".addslashes($insertId)."',
			`status` = '1'";

			mysqli_query($connNew,$insertCalendar);		
	}
	
	if($query_type=='edit'){
		
		$insertIncentive = "UPDATE `".TBL_INCENTIVE."` SET  
				 					 		
											
						
						`revenue`='".$revenue_inc."',
						`guest_name`='".$guest_name_inc."',
						`checkin`='".date('Y-m-d',strtotime($checkin_inc))."',
						`checkout`='".date('Y-m-d',strtotime($checkout_inc))."',
						`no_room`='".$no_room_inc."',
						`no_pax`='".$no_pax_inc."',
						`banquet_revenue_amount`='".$banquet_revenue_amount_inc."',		
						`room_rate` = '".$room_rate_inc."',	
						`follow_up_close_summary`='".$remarks_inc."',
						
						`modified_by`='".$_SESSION['userId']."',						
						
						`date_modified`='".date('Y-m-d H:i:s')."'
						
						 WHERE   id_enquiry='".$enquiry_id."'";
						
			  
					
	mysqli_query($connNew,$insertIncentive);								
	$insertId = mysqli_insert_id($connNew); 
		
		}
	
	}				
?>