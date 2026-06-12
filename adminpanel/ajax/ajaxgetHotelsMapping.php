<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$shopId=$_REQUEST['shopId'];
$hotel_id = $_REQUEST['hotel_id'];
?> 
 <?php $categoryDropDown = '<select class="form-control select2" name="hotel_id">
											    <option value="">Select Hotel</option>';
											  $resCat = selectSql(TBL_HOTELS," where status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' and id_shop='".addslashes($shopId)."' ",' ORDER BY `id`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['hotel_id'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else if($row->hotel_id == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>