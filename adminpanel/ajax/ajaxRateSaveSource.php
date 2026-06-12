<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['name']!=''){


if($_REQUEST['EditSourceID']!=''){
$addSql = "   	UPDATE `".TBL_LEAD_SOURCE_MASTER."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							
							`name` = '".addslashes(trim($_POST['name']))."',
							
							`description` = '".addslashes($_POST['description'])."' ";
						     
			$addSql .= "	,`last_modified` = '".currenDateTime()."'
							
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = 1
							WHERE `id` = '".addslashes($_REQUEST['EditSourceID'])."'";
							
		executeSql($addSql);				
		$lastInsertId=	addslashes($_REQUEST['EditSourceID']);			
	}else{

			$addSql = "   	INSERT INTO `".TBL_LEAD_SOURCE_MASTER."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							
							`name` = '".addslashes(trim($_POST['name']))."',
							
							`description` = '".addslashes($_POST['description'])."' ";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
	
		executeSql($addSql);
		$lastInsertId= $db->insert_id(); 
		}


		?>
		
		 <select class="form-control select2" name="id_mst_lead_source" id="id_mst_lead_source" data-parsley-errors-container="#guestError" >
                      <option value="">Select Source</option>
                     <?php 	$resCat = selectSql(TBL_LEAD_SOURCE_MASTER,"where status=1  AND id='".$lastInsertId."'  and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');       
                                if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
													
														
														if($lastInsertId == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$sourceDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">Name : '.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $sourceDropDown;
                                 ?>
                                </select> 
		 <div class="input-group-addon sourceby_open"> <i class="fa fa-plus"></i> </div>
		
	<?php 
				
}
?>