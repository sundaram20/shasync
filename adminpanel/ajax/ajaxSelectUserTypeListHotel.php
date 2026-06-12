<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$returnArr=array();
$retData='';
$hotelId	=	$_REQUEST['value'];
$checkUserType = selectColumn(TBL_USERS,'user_type','WHERE id="'.$hotelId.'" ');

$returnArr['user_type']=$checkUserType;
 
//WHERE id IN (".$_SESSION['hotel_access'].")
$checkUserHotelAccess = selectColumn(TBL_USERS,'hotel_access','WHERE id="'.$hotelId.'" '); 
				
if($checkUserType ==2){
	$hotSql = "SELECT id,CONCAT(name,', ',city) AS name FROM ".TBL_HOTELS." WHERE id IN (".$checkUserHotelAccess.")";
                $hotRes = mysqli_query($connNew,$hotSql);
	
		$retData ='<div class="col-md-4">
              <label>Select Hotel</label>

              <select onChange="budgetAchievedMasterFunction();" name="id_hotel" id="id_hotel" class="select2 form-control" data-parsley-required>
                <option value="">Select Hotel</option>';
                
                while($hotRow = mysqli_fetch_object($hotRes)){

                  if(encryptor('decrypt',$_REQUEST['value'])==$hotRow->id){
                    $selected='selected="selected"';
                  }
                  else{
                    $selected="";
                  }

                  $retData .= "<option ".$selected." value='".$hotRow->id."'>".$hotRow->name."</option>";
                }
               
              $retData.='</select>
            </div>';
			$returnArr['data']=$retData;
             }
echo json_encode($returnArr);			 