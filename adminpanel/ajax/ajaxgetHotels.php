<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$shopId=$_REQUEST['shopId'];
$hotel_access = $_REQUEST['hotel_access'];
$userId = $_REQUEST['userId'];
?> 
 <select class="form-control select2" name="hotel_access[]" multiple="multiple" id="hotel_access">
<?php 
					$sqlUserHotel = selectSql(TBL_HOTELS,' where status="1" and id_shop="'.addslashes($shopId).'"','');
					$iCounter = 0;
					while($resUserHotel = $db->fetch_object2($sqlUserHotel)){
						$chkSql = "SELECT * FROM `".TBL_USERS."` WHERE FIND_IN_SET('".$resUserHotel->id."',hotel_access) and id='".addslashes($userId)."' ";
						if($db->num_rows2(executeSql($chkSql)) > 0){
							$selected = 'selected="selected"';
						}else if($_POST[$selected]){
						$selected = 'selected="selected"';
						}													
						else{
							$selected = '';
						}
						echo '<option '.$selected.' value="'.$resUserHotel->id.'">'.$resUserHotel->name.','.$resUserHotel->city.'</option>';
						
						$iCounter++;
					}
					?>
	</select>