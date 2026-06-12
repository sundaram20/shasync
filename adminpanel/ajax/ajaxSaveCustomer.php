<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['first_name']!='' && $_POST['last_name'] !=''){

$addSql = "   	INSERT INTO `".TBL_CUSTOMER."` SET
				`id_default_group` = '1',
				`title` = '".addslashes($_POST['title'])."',
				`id_company` = '".addslashes($_POST['id_company'])."',
				`first_name` = '".addslashes($_POST['first_name'])."',
				`last_name` = '".addslashes($_POST['last_name'])."',
				`email` = '".addslashes($_POST['email'])."',
				`mobile` = '".addslashes($_POST['mobile'])."',
				`type` = '2'";
$addSql .= "	,`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`last_modified_by` = '".$_SESSION['userId']."'
				,`status` = '1'";
		
	if(executeSql($addSql)){ 
		$lastInsertId= $db->insert_id(); ?>
		
		 <select class="form-control select2" name="id_contacts" id="id_contacts" data-parsley-errors-container="#guestError" >
                      <option value="">Select Guest</option>
                      <?php 
									$resCat = selectSql(TBL_CUSTOMER,"where status='1' and type='2' and  id_customer='".$lastInsertId."'",'');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($lastInsertId == $resultCat->id_customer){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->title).' '.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
                    </select>
		 <div class="input-group-addon bookedby_open"> <i class="fa fa-plus"></i> </div>
		
	<?php }
				
}
?>