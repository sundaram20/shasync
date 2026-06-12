<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['select']==1){
$id_user_created 	 = $_REQUEST['id_user_created'];
$id_hotel_created	= $_REQUEST['id_hotel_created'];


			$sqlLevel = "SELECT id FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."' AND FIND_IN_SET(".$id_hotel_created.",hotel_access)";
			$resRet = mysqli_query($connNew,$sqlLevel);

			$arrRet = array();

			while($rowRet=mysqli_fetch_object($resRet)){
				array_push($arrRet, $rowRet->id);
			}

			$hotelUser = "AND  FIND_IN_SET(id, '".implode(',',$arrRet)."')";
			
?>
<div class="form-group">
                <label for="pkg_extra_price" style="float:left;">Hotel User</label>
                <?php   
               if($_SESSION['unit_user']=='2'){
                     $salesHead=array();
                     $teamSql = "SELECT id_user_level_1 FROM ".TBL_TEAM." WHERE id_shop='".$_SESSION['shop']."'  ";
                     $teamRes= mysqli_query($connNew,$teamSql);

                     while($rowTeam=mysqli_fetch_object($teamRes)){
                       array_push($salesHead,$rowTeam->id_user_level_1);
                     }
                  }

              //hotel_access

                 $HotelUserList.= '<select class="form-control select2" data-parsley-required name="id_forward_for_approval" id="id_forward_for_approval">
					<option value="">Select Assign UserName</option>';

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."'  AND  FIND_IN_SET(id, '".implode(',',$arrRet)."')",' ORDER BY `name`');

											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){


$id_designation = selectColumn(TBL_USERS,'designation','WHERE id="'.$resultUserLevel->id.'" ');

	$Designation =selectColumn(TBL_DESIGNATION_MASTER,'name','WHERE id="'.$id_designation.'" ');



													if($_SESSION['userId'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}
														
		$HotelUserList .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).' - '.$Designation.'-'.userTeamName($resultUserLevel->ids_team).'</option>';
													

												}

											  }

											 	echo $HotelUserList .= '</select>';

                                              

                                              	

												 

												 ?>
              </div>
<?php } ?>
<?php if($_REQUEST['select']==2){?>
<?php
$id_user_created 	 = $_REQUEST['id_user_created'];
$id_hotel_created	= $_REQUEST['id_hotel_created'];
$id_enquiry= addslashes(encryptor('decrypt',$_REQUEST['id_enquiry']));

			$sqlLevel = "SELECT * FROM ".TBL_INCENTIVE." WHERE id_shop='".$_SESSION['shop']."' AND id_enquiry='".$id_enquiry."'";
			$resRet = mysqli_query($connNew,$sqlLevel);

			

			$rowRet=mysqli_fetch_object($resRet);
				 if($rowRet->id_user==$rowRet->id_currently_with){
					 
					  $User = "AND  id='".$rowRet->id_forward_for_approval."'";
					  $label='Forward for Approval';
					 }else{
						 
						 $User = "AND  id='".$rowRet->id_user."'";
						  $label='Action By';
						  }
			

			//$hotelUser = "AND  FIND_IN_SET(id, '".implode(',',$arrRet)."')";
			
?>
<div class="form-group">
                <label for="pkg_extra_price" style="float:left;"><?php echo  $label;?></label>
                <?php   
              

                 $HotelUserList.= '<select class="form-control select2" data-parsley-required name="id_forward_for_approval" id="id_forward_for_approval">
					';

				  $resUserLevel = selectSql(TBL_USERS," WHERE `status` = '1' AND  `id_shop` = '".addslashes($_SESSION['shop'])."'   $User ",' ORDER BY `name`');

											  if($db->num_rows2($resUserLevel)){

											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){

													if($_SESSION['userId'] == $resultUserLevel->id){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}
														
													$HotelUserList .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'-'.userTeamName($resultUserLevel->ids_team).'</option>';
													

												}

											  }

											 	echo $HotelUserList .= '</select>';

                                              

                                              	

												 

												 ?>
              </div>
<?php } ?>