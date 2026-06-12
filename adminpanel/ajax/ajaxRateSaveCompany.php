<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['name']!=''){


if($_REQUEST['EditCompanyID']!=''){
$addSql = "   	UPDATE `".TBL_COMPANY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_default_group` = '".addslashes($_POST['id_default_group'])."',
							`id_lang` = '1',
							`id_rate_level` = '".addslashes(implode(',',$_POST['id_rate_level']))."',
							`name` = '".addslashes(trim($_POST['name']))."',
							`email` = '".addslashes($_POST['email'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',
							`id_country` = '".addslashes($_POST['id_country'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`id_state` = '".addslashes($_POST['id_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',							
							`fax` = '".addslashes($_POST['fax'])."',
							`area` = '".addslashes($_POST['area'])."',
							`company_credibility` = '".addslashes($_POST['company_credibility'])."',
							`deals_in` = '".addslashes($_POST['deals_in'])."',
							`details` = '".addslashes($_POST['details'])."',	
							`credit_form` = '".trim($_POST['credithidden'])."',						
							`booking` = '".addslashes($_POST['booking'])."'";
			$addSql .= "	,`last_modified` = '".currenDateTime()."'
							
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id_company` = '".addslashes($_REQUEST['EditCompanyID'])."'";
							
		executeSql($addSql);				
		$lastInsertId=	addslashes($_REQUEST['EditCompanyID']);			
	}else{

			$addSql = "   	INSERT INTO `".TBL_COMPANY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_default_group` = '".addslashes($_POST['id_default_group'])."',
							`id_lang` = '1',
							`id_rate_level` = '".addslashes(implode(',',$_POST['id_rate_level']))."',
							`name` = '".addslashes(trim($_POST['name']))."',
							`email` = '".addslashes($_POST['email'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',
							`id_country` = '".addslashes($_POST['id_country'])."',
							`id_state` = '".addslashes($_POST['id_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',							
							`fax` = '".addslashes($_POST['fax'])."',
							`area` = '".addslashes($_POST['area'])."',
							`company_credibility` = '".addslashes($_POST['company_credibility'])."',
							`deals_in` = '".addslashes($_POST['deals_in'])."',
							`details` = '".addslashes($_POST['details'])."',
							`credit_form` = '".trim($_POST['credithidden'])."',
							`created_by` = '".$_SESSION['userId']."',
							`booking` = '".addslashes($_POST['booking'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
	
		executeSql($addSql);
		$lastInsertId= $db->insert_id(); 
		}


		?>
		
		 <select class="form-control select2" name="id_company" id="id_company" data-parsley-errors-container="#guestError" >
                      <option value="">Select Company1</option>
                     <?php 	$resCat = selectSql(TBL_COMPANY,"where status=1  AND id_company='".$lastInsertId."'  and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');       
                                if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
													
														
														if($lastInsertId == $resultCat->id_company){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$companyDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">Name : '.ucfirst($resultCat->name).' | City : '.$resultCat->city.'</option>';
													}
												  }
												  echo $companyDropDown;
                                 ?>
                                </select> 
		 <div class="input-group-addon companyby_open"> <i class="fa fa-plus"></i> </div>
		
	<?php 
				
}
?>