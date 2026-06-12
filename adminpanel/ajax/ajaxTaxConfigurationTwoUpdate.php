<?php  include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//echo "<pre>";print_r($_REQUEST);echo "</pre>";


$Array_count_roomeid=	sizeof($_REQUEST['room_id']);

	
	
	$RoomID	=	$_REQUEST['room_id'];
		
		
		
			
			foreach($RoomID as $data =>$value){		
	
	checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'add');
			
		
			
			
			
	$SqlRoomType2	=	executeSql("SELECT * from `".TBL_TAX_CONFIGURATION_TWO."`  where `id_shop` = '".addslashes($_SESSION['shop'])."' and `id_hotel` = '".addslashes($_REQUEST['hotelId'])."' and  `room_id` = '".addslashes($_REQUEST['room_id'][$data])."' and  `seasonId` = '".addslashes($_REQUEST['seasonId'])."'   ");

	if(num_rows($SqlRoomType2)>0){
		
		
			$start_date		=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
			$end_date		=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
						
						//$addRate = "    INSERT INTO `".TBL_TAX_CONFIGURATION_TWO."` SET 
					
						$editRate = " UPDATE `".TBL_TAX_CONFIGURATION_TWO."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',																													
										`id_hotel` = '".addslashes($_REQUEST['hotelId'])."',
										`room_id` = '".$_REQUEST['room_id'][$data]."',
										`seasonId` = '".$_REQUEST['seasonId']."',							
										`tax_room` = '".addslashes($_POST['tax_room'][$data])."',																											
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						 $editRate .= "  ,`status` = '1'
										WHERE `id` = '".$_REQUEST['id'][$data]."'";						
							executeSql($editRate);
										
		
		
	}else{

			
				
				
				$start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
				$end_date	=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".$_REQUEST['seasonId']."'");
				
						$addRate = "    INSERT INTO `".TBL_TAX_CONFIGURATION_TWO."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',																													
										`id_hotel` = '".addslashes($_REQUEST['hotelId'])."',
										`room_id` = '".$_REQUEST['room_id'][$data]."',
										`seasonId` = '".$_REQUEST['seasonId']."',							
										`tax_room` = '".addslashes($_REQUEST['tax_room'][$data])."',																											
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						$addRate .= "  ,`status` = '1'";						
							executeSql($addRate);
								$rate_id= $db->insert_id();								
								
						
					
				
									
							
							/*echo '<p class="help-block">New Rate details has been added sucessfully.</p><script>window.setTimeout(function() { window.location.href = "editTaxConfigurationTwo.php?hotelId='.encryptor('encrypt',($_POST['hotelId'])).'&id='.encryptor('encrypt',($rate_id)).'&action=edit&page=1";}, 2000); </script>';
							unset($_POST);*/
			
	}
		
			
			
				
			
			}
		
		echo '<p class="help-block">New Tax details has been added sucessfully.</p><script>window.setTimeout(function() { window.location.href = "editTaxConfigurationTwo.php?hotelId='.encryptor('encrypt',($_POST['hotelId'])).'&id='.encryptor('encrypt',($rate_id)).'&action=edit&page=1";}, 2000); </script>';
							unset($_POST);
		
	


?>