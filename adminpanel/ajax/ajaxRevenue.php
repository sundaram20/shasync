<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$result=array();
 $resultClose = selectSql(TBL_CLOSING_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and id='".$_REQUEST['id']."' ",' ORDER BY name ');
			  
											  $resultData = $db->fetch_object2($resultClose);
											  $Name	=	ucfirst($resultData->name);
											  if($Name=='Confirmed' || $Name=='Booked Online'){
												  
												  //$te="Toggle('displayincentive')";
			if($Name=='Confirmed'){		
			
		$id_hotel_md=	selectColumn(TBL_INCENTIVE_PARTICIPATE_HOTEL, 'id' , "WHERE hotel_id = '".$_REQUEST['id_hotel_md']."' and status=1");							  
		if($id_hotel_md>0){		
		$te="displayincentiveaddeditform('displayincentive')";
				
				$result['dvalue']='<div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> 
				<input type="checkbox" name="ClaimIncentive" id="ClaimIncentive" onclick="'.$te.'" data-parsley-required/> &nbsp; Sales Lead Award 
				</div>';  
				$result['dvalueRevenue']= '';
		}else{
	 $result['dvalue']= '';
	 $result['dvalueRevenue']= '<div class="form-group">
                <input  type="text" id="revenue" name="revenue" oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");" class="form-control" placeholder="Revenue"  automcomplete="off" data-parsley-required>
                </div>';

                	// $result['dvalue2']= '';
	 $result['dvalueCommission']= '<div class="form-group">
                <input  type="text" id="commission" name="commission" oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");" class="form-control" placeholder="Commission"  automcomplete="off" data-parsley-required>
                </div>';
	 }
 }else{
	 $result['dvalue']= '';
	 $result['dvalueRevenue']= '<div class="form-group">
                <input  type="text" id="revenue" name="revenue" oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");" class="form-control" placeholder="Revenue"  automcomplete="off" data-parsley-required>
                </div>';
	 $result['dvalueCommission']= '<div class="form-group">
	<input  type="text" id="commission" name="commission" oninput="this.value = this.value.replace(/[^0-9.]/g, "").replace(/(\..*)\./g, "$1");" class="form-control" placeholder="Commission"  automcomplete="off" data-parsley-required>
	</div>';
	 }
 		
 
	}else{
		$result['id']=1;
	
	
	
	}
	echo json_encode($result);
	
/* $resultClose = selectSql(TBL_CLOSING_MASTER,"where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and id='".$_REQUEST['id']."' ",' ORDER BY name ');
				  
											  $resultData = $db->fetch_object2($resultClose);
											  $Name	=	ucfirst($resultData->name);
											  if($Name=='Confirmed'){
												 ?>
          
 
  		<div class="form-group"> 
            
            <input type="text"   name="revenue" id="revenue" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" placeholder="Revenue"  automcomplete="off" data-parsley-required>
            
            </div>
                                                 <?php
												  }else{
?>
            
<?php
													  }
											  */
?>
