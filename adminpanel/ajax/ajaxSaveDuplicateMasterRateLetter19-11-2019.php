<?php include_once("../../config/auto_loader.php");

//echo "<pre>";print_r($_REQUEST);echo "</pre>";
//die;
$start_date	=	$_REQUEST['start_date'];
$end_date	=	$_REQUEST['end_date'];

$DuphotelId = explode(",", $_REQUEST['DuphotelId']);


$DuplicateID	=	explode('|',$_REQUEST['rate_level_id']);

$dupId	=	$_REQUEST['dupId'];

$rate_category_id	=	$DuplicateID['0'];

$RateSQL = executeSql("SELECT * FROM `".TBL_RATE."` where id='".addslashes($dupId)."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'");

$RateRecordRow = $db->fetch_object2($RateSQL);

	  	if($_POST['rate_level_id']!='' && $_POST['market']!='' && $_POST['seasonId']!='' && $start_date !='' && $end_date!=''){
		
		
		 	 checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'add');

			 $start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");				
			 $end_date		=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");					
		
	
	
				$checkExisting = executeSql("SELECT * FROM `".TBL_RATE."` where  rate_level_id='".addslashes($_POST['rate_level_id'])."' and seasonId='".addslashes($_POST['seasonId'])."' and market='".addslashes($_POST['market'])."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
				
				
				
			if(num_rows($checkExisting)>0){
				
			$err++;
			echo '<p class="help-block">Rate Letter Master has been already added for this Rate Category,Market And Season Data.</p>'; 	
				
			}else{
			
			//ALL HOTEL INSERT
				
//echo "ALL HOTEL INSERT";
				
		
		
			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				
			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			//$newId 		= sprintf("%'03d", ($lastRecordRow->maxId));
			//echo $rateName 	= 'WH'.$newId;
			$mystring 	   = $lastRecordRow->maxId;			
			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			
			$newId 		   = sprintf("%'03d", ($newId+1));
			$rateName 	   = $GetShopShortCode.$newId;
			
$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");
			$resultCat 	= $db->fetch_object2($resCat);
			
		//die;
				$ratePointDetail = json_encode($resultCat);
						 $addRate = "    INSERT INTO `".TBL_RATE."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',
										`id_shop_group` = '1',
										`rate_name` = '".addslashes($rateName)."',
										`sub_code` = '0',
										`company_id` = '".addslashes($resultCat->company_id)."',
										`id_contacts` = '".addslashes($resultCat->id_contacts)."',
										`rate_level_id` = '".addslashes($_POST['rate_level_id'])."',
										`rate_category_id` = '".addslashes($rate_category_id)."',
										`seasonId` = '".addslashes($_POST['seasonId'])."',							
										`remarks` = '".addslashes($_POST['remarks'])."',
										`market` = '".addslashes($_POST['market'])."',
										`generalterms` = '".addslashes($resultCat->generalterms)."',	
										`additional_points` = '".addslashes($resultCat->additional_points)."',	
										`rate_points` = '".addslashes($resultCat->rate_points)."',							
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						$addRate .= "	,`date_created` = '".currenDateTime()."'
										,`created_by` = '".$_SESSION['userId']."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";					
			executeSql($addRate);
			$rate_id= mysql_insert_id();					
		
		
		
		
			executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			
			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			
			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SELECT * FROM temp_rate_assign");
			$rateAssignId = $db->insert_id();	
			
			
			executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			
			
			$resCat = executeSql("SELECT * from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".$rate_id."'");
			
			
			while($resultCat = $db->fetch_object2($resCat)){
				
				
						executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,rate_assign_id='".$resultCat->id."' where rate_assign_id=(select id from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes($dupId)."' and hotel_id='".$resultCat->hotel_id."')");
					
					
					}
			
			
			
			
			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");
			
			executeSql("DROP TEMPORARY TABLE temp_rate_detail");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign");
			
			//echo '<p class="help-block">All Hotel Rate details has been updated sucessfully.</p>';
			echo '<p class="help-block">All Hotel Rate details has been updated sucessfully</p><script>window.setTimeout(function() {window.location.href = "manageRateMaster.php";}, 2000);</script>';
			//header("location:manageRateLetters.php");
			
			
			
	
			
		}
		
	}else{
		
		$err++;
			echo '<p class="help-block">Unable to Process.Please select required Fields.</p>'; 	
				
		}
?>
