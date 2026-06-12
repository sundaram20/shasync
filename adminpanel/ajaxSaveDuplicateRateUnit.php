<?php include_once("../../config/auto_loader.php");

/*echo "<pre>";print_r($_REQUEST);echo "</pre>";
die;*/

if(empty($_POST['seasonId']) && $_POST['seasonId']==""){
	echo '<font style="color:red;font-weight:normal;" ><br>Please select Season.</font>';
	exit;
}
if(empty($_POST['start_date'])){
	echo '<font style="color:red;font-weight:normal;" ><br>Please enter start date.</font>';
	exit;
}
if(empty($_POST['end_date'])){
	echo '<font style="color:red;font-weight:normal;" ><br>Please enter end date.</font>';
	exit;
}
if(empty($_POST['rate_level_id'])){
	echo '<font style="color:red;font-weight:normal;" ><br>Please select Rate Letter Master.</font>';
	exit;
}
if(empty($_POST['new_rate_level_id'])){
	echo '<font style="color:red;font-weight:normal;" ><br>Please select rate level.</font>';
	exit;
}

if($_POST['HotelDuplicateInsert']==0 || empty($_POST['DuphotelId'])){
	echo '<font style="color:red;font-weight:normal;" ><br>Error : Please Select Hotel.</font>';
	exit;
}		
			



$id_state	=	selectColumn(TBL_COMPANY,'id_state'," WHERE `id_company` = '".addslashes($_POST['company_id'])."'");
$start_date	=	$_REQUEST['start_date'];
$end_date	=	$_REQUEST['end_date'];

$DuphotelId = explode(",", $_REQUEST['DuphotelId']);

$DuplicateID =	explode('|',$_REQUEST['rate_level_id']);

$dupId	=	$DuplicateID['0'];

$rate_category_id	=	$DuplicateID['0'];

$RateSQL = executeSql("SELECT * FROM `".TBL_RATE."` where id='".addslashes($dupId)."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'");

$RateRecordRow = $db->fetch_object2($RateSQL);

$addSql  = "  INSERT INTO `".TBL_CUSTOMER."` SET
				`id_default_group` = '1',
				`id_company` = '".addslashes($_POST['company_id'])."',
				`first_name` = '".addslashes($_POST['first_name'])."',
				`last_name` = '".addslashes($_POST['last_name'])."',
				`email` = '".addslashes($_POST['email'])."',
				`mobile` = '".addslashes($_POST['mobile'])."',
				`type` = '2'";
$addSql .= "	,`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`last_modified_by` = '".$_SESSION['userId']."'
				,`status` = '1'";	  
		
	

$start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");				
$end_date		=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");					
	 	
$companyName=selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$_POST['company_id'].'" ');

	 	
$chkSql = "SELECT * FROM `".TBL_RATE."` LEFT JOIN `".TBL_COMPANY."` ON `".TBL_RATE."`.company_id=`".TBL_COMPANY."`.id_company where  rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and seasonId='".addslashes($_POST['seasonId'])."' and market='".addslashes($_POST['market'])."' AND `".TBL_RATE."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.`status` = '1' AND `".TBL_COMPANY."`.name='".$companyName."' ";
	 	
$chkSqlUnit = "SELECT DISTINCT a.id,a.rate_name FROM `".TBL_RATE_UNIT."` as a join `".TBL_RATE_DETAILS_UNIT."` as b LEFT JOIN 
			".TBL_COMPANY." c ON a.company_id=c.id_company  where UPPER(c.name)='".strtoupper($companyName)."' and a.rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and a.seasonId='".addslashes($_POST['seasonId'])."' and a.market='".addslashes($_POST['market'])."' AND a.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND a.id=b.rate_id AND b.hotel_id IN ('".$_SESSION['hotel_access']."') ";	 	
	 	



$checkExisting = executeSql($chkSql);
$rateNoUnit = mysqli_query($connNew,$chkSqlUnit);
$rateNameUnit = '';
$chkFlag = 0;
while($rowUnit = mysqli_fetch_object($rateNoUnit)){
	$rateNameUnit.=$rowUnit->rate_name.'<br>';
}
/*<button class='btn btn-primary' onclick='SaveDuplicateRate();'>Countinue</button>*/			
$rateNo = mysqli_fetch_object(mysqli_query($connNew,$chkSql));
if(num_rows($checkExisting)>0 && mysqli_num_rows($rateNoUnit )==0){	
$warning= '<p>!! Duplicate Found !!<br>
'.($rateNo->rate_name!=""?"-----Rate Letter By Corporate -----<br>".$rateNo->rate_name."<br>&nbsp; <button class='btn btn-danger' onclick='location.replace(\"manageRateLettersUnit.php\");'>Cancel</button>":"").'
	</p>';	
	if($_REQUEST['chkFlag']!="0"){
		$chkFlag = 2;
		//$warning='';
	}
	else{
		echo $warning;
		exit;
	}	
}
elseif(mysqli_num_rows($rateNoUnit )> 0){
	$warning= '<p>!! Duplicate Found !!<br>
	'.($rateNo->rate_name!=""?"-----Rate Letter By Corporate -----<br>".$rateNo->rate_name:"").($rateNameUnit!=""?"<br>
	-----Rate Letter By Unit -------<br>".$rateNameUnit."<br><button class='btn btn-primary' onclick='location.replace(\"manageRateLettersUnit.php\")'>Ok, Redirect me to rate letters lists</button>":"").'

		</p>';	
	$chkFlag = 2;
	
}


if($chkFlag==2){
	echo $warning;
		$warning='';
		exit;
				
}else{
	
	if($_REQUEST['HotelDuplicateInsert']==1){  //ALL HOTEL INSERT
				
//echo "ALL HOTEL INSERT";
				
	$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				
			
	$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate_unit` WHERE id=(SELECT MAX(id) FROM ".TBL_RATE_UNIT." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ) AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			$mystring 	   = $lastRecordRow->maxId;			
			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			
			$newId 		   = sprintf("%'03d", ($newId+1));
			$rateName 	   = $GetShopShortCode."U".$newId;
			
		$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");
			$resultCat 	= $db->fetch_object2($resCat);
		
				$ratePointDetail = json_encode($resultCat);
						$addRate = "    INSERT INTO `".TBL_RATE_UNIT."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',
										`id_shop_group` = '1',
										`rate_name` = '".addslashes($rateName)."',
										`id_state` = '".addslashes($id_state)."',
										`sub_code` = '0',
										`company_id` = '".addslashes($_POST['company_id'])."',
										`id_contacts` = '".addslashes($_POST['id_contacts'])."',
										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',
										`rate_category_id` = '".addslashes($rate_category_id)."',
										`seasonId` = '".addslashes($_POST['seasonId'])."',
										`generalterms` 	= '".addslashes($_POST['generalterms'])."',		
										`rate_points` 	= '".addslashes($_POST['rate_points'])."',				
										`remarks` = '".addslashes($_POST['remarks'])."',
										`market` = '".addslashes($_POST['market'])."',
										
										`rate_points` = '".addslashes($resultCat->rate_points)."',							
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						$addRate .= "	,`date_created` = '".currenDateTime()."'
										,`created_by` = '".$_SESSION['userId']."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";					
			executeSql($addRate);
			$rate_id= mysqli_insert_id();					
		
		
		
		
			executeSql("CREATE TEMPORARY TABLE temp_rate_assign_unit AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			executeSql("UPDATE temp_rate_assign_unit SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS_UNIT."` SELECT * FROM temp_rate_assign_unit");
			$rateAssignId = $db->insert_id();	
			
			
			executeSql("CREATE TEMPORARY TABLE temp_rate_detail_unit SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			
			
			$resCat = executeSql("SELECT * from `".TBL_RATE_ASSIGN_DETAILS_UNIT."` where rate_id='".$rate_id."'");
			
			
			while($resultCat = $db->fetch_object2($resCat)){
				executeSql("UPDATE temp_rate_detail_unit SET id=NULL , rate_id='".$rate_id."' ,rate_assign_id='".$resultCat->id."' where rate_assign_id=(select id from `".TBL_RATE_ASSIGN_DETAILS_UNIT."` where rate_id='".addslashes($dupId)."' and hotel_id='".$resultCat->hotel_id."')");
			}
				
			
			executeSql("UPDATE temp_rate_detail_unit SET id=NULL , rate_id='".$rate_id."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS_UNIT."` SELECT * FROM temp_rate_detail_unit");
			
			executeSql("DROP TEMPORARY TABLE temp_rate_detail_unit");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign_unit");
			
			//echo '<p class="help-block">All Hotel Rate details has been updated sucessfully.</p>';
			echo '<p class="help-block">All Hotel Rate details has been updated sucessfully<br>Redirecting Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageRateLettersUnit.php";}, 1);</script>';
			//header("location:manageRateLetters.php");
	}
			
			
	if($_REQUEST['HotelDuplicateInsert']==2){ //SELECTED HOTEL INSERT
				
			//echo "SELECTED HOTEL INSERT";		
			//die;	
			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				
			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate_unit` WHERE id=(SELECT MAX(id) FROM ".TBL_RATE_UNIT." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ) AND  `id_shop` = '".addslashes($_SESSION['shop'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			//$newId 		= sprintf("%'03d", ($lastRecordRow->maxId));
			//echo $rateName 	= 'WH'.$newId;
			$mystring 	   = $lastRecordRow->maxId;			
			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			
			$newId 		   = sprintf("%'03d", ($newId+1));
			$rateName 	   = $GetShopShortCode."U".$newId;
			
			$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");
			$resultCat 	= $db->fetch_object2($resCat);
		
				$ratePointDetail = json_encode($resultCat);
						 $addRate = "    INSERT INTO `".TBL_RATE_UNIT."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',
										`id_shop_group` = '1',
										`rate_name` = '".addslashes($rateName)."',
										`sub_code` = '0',
										`company_id` = '".addslashes($_POST['company_id'])."',
										`id_state` = '".addslashes($id_state)."',
										`id_contacts` = '".addslashes($_POST['id_contacts'])."',
										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',
										`generalterms` 	= '".addslashes($_POST['generalterms'])."',		
										`rate_points` 	= '".addslashes($_POST['rate_points'])."',
										`rate_category_id` = '".addslashes($rate_category_id)."',
										`seasonId` = '".addslashes($_POST['seasonId'])."',							
										`remarks` = '".addslashes($_POST['remarks'])."',
										`market` = '".addslashes($_POST['market'])."',
										
																	
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						 $addRate .= "	,`date_created` = '".currenDateTime()."'
						 				,`created_by` = '".$_SESSION['userId']."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";					
			executeSql($addRate);
			$rate_id= mysqli_insert_id();

			foreach($DuphotelId as $value){
					
						
			$value;
				
			executeSql("CREATE TEMPORARY TABLE temp_rate_assign_unit AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");
			executeSql("UPDATE temp_rate_assign_unit SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS_UNIT."` SELECT * FROM temp_rate_assign_unit");
			$rateAssignId = $db->insert_id();	
				
					
					
					
			executeSql("CREATE TEMPORARY TABLE temp_rate_detail_unit SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");
			
			executeSql("UPDATE temp_rate_detail_unit SET id=NULL , rate_id='".$rate_id."' ,  rate_assign_id='".$rateAssignId."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS_UNIT."` SELECT * FROM temp_rate_detail_unit");
		
			executeSql("DROP TEMPORARY TABLE temp_rate_detail_unit");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign_unit");
				
				
				
				}	
			echo '<p class="help-block">Selected Hotel Rate details has been updated sucessfully.<br>Redirecting Please Wait...</p><script>window.setTimeout(function() {window.location.href = "manageRateLettersUnit.php";}, 1);</script>'; 
	}
}
?>
