<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$shopId=$_REQUEST['shopId'];
$rate_id = $_REQUEST['rate_id'];
?> 
 <?php $categoryDropDown = '<select class="form-control select2" name="rate_id">
											    <option value="">Select Rate</option>';
											  $resCat = selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($shopId)."' ",' ORDER BY `id`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['rate_id'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else if($row->rate_id == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>