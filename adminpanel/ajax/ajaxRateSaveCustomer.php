<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['first_name']!='' && $_POST['last_name'] !=''){


if($_REQUEST['EditCustomerID']!=''){
	
	$addSql = "	UPDATE `".TBL_CUSTOMER."` SET 
						`id_default_group` = '1',
						`id_shop` = '".$_SESSION['shop']."',
						`id_company` = '".addslashes($_POST['id_company'])."',
						`title` = '".addslashes($_POST['Nametitle'])."',
						`first_name` = '".addslashes($_POST['first_name'])."',
						`last_name` = '".addslashes($_POST['last_name'])."',
						`email` = '".addslashes($_POST['email'])."',
						`mobile` = '".addslashes($_POST['mobile'])."',
						`designation` = '".trim(addslashes($_POST['designation']))."',
						`dateofanniversaryMonth` = '".$_POST['dateofanniversaryMonth']."',
						`dateofanniversaryday` = '".$_POST['dateofanniversaryday']."',
						`dateofBirthday` = '". $_POST['dateofBirthday']."',
						`dateofBirthMonth` = '". $_POST['dateofBirthMonth']."',
						`type` = '2'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_customer` = '".addslashes($_REQUEST['EditCustomerID'])."'";
		executeSql($addSql);				
		$lastInsertId=	addslashes($_REQUEST['EditCustomerID']);			
	}else{
$addSql = "   	INSERT INTO `".TBL_CUSTOMER."` SET
				`id_default_group` = '1',
				`id_shop` = '".$_SESSION['shop']."',
				`id_company` = '".addslashes($_REQUEST['id_company'])."',
				`title` = '".addslashes($_POST['Nametitle'])."',
				`first_name` = '".addslashes($_POST['first_name'])."',
				`last_name` = '".addslashes($_POST['last_name'])."',
				`email` = '".addslashes($_POST['email'])."',
				`mobile` = '".addslashes($_POST['mobile'])."',
				`designation` = '".trim(addslashes($_POST['designation']))."',
				`dateofanniversaryMonth` = '".$_POST['dateofanniversaryMonth']."',
				`dateofanniversaryday` = '".$_POST['dateofanniversaryday']."',
				`dateofBirthday` = '". $_POST['dateofBirthday']."',
				`dateofBirthMonth` = '". $_POST['dateofBirthMonth']."',
				`type` = '2'";
$addSql .= "	,`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`last_modified_by` = '".$_SESSION['userId']."'
				,`status` = '1'";
	
		executeSql($addSql);
		$lastInsertId= $db->insert_id(); 
		}
		?>
		
		 <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#guestError" >
                      <option value="">Select Guest</option>
                      <?php 
									$resCat = selectSql(TBL_CUSTOMER,"where status='1' and id_company='".addslashes($_POST['id_company'])."' and type='2' ",' ORDER BY `first_name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
													$monthNum  = $resultCat->dateofBirthMonth;
 $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));	
														$resultCat->dob;
														
														if($lastInsertId == $resultCat->id_customer){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->title).''.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
                    </select>
		 <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>
		
	<?php 
				
}
?>