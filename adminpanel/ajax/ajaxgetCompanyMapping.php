<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$shopId=$_REQUEST['shopId'];
$company_id = $_REQUEST['company_id'];
?> 
 <?php $categoryDropDown = '<select class="form-control select2" name="company_id">
											    <option value="">Select Company</option>';
											  $resCat = selectSql(TBL_COMPANY," where status='1' and id_shop='".addslashes($shopId)."' ",' ORDER BY `id_company`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['company_id'] == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else if($row->company_id == $resultCat->id_company){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_company.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>