<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$rateId = $_REQUEST['rateId'];
//////////////////////////////getting and showing rate inclusion//////////////////////////////////////////////////////
$resRatePlan = executeSql("SELECT inclusion,tax_percent from `".TBL_RATE_PLAN."`  where status='1' and id='".addslashes($rateId)."' limit 0,1");
$rowRatePlan = $db->fetch_object2($resRatePlan);
$inclusion = explode(',',$rowRatePlan->inclusion);

if($rateId !=''){
foreach($inclusion as $value){	
$availableData .= '<div class="form-group col-sm-2"> 
					   <label for="inclusion">'.selectColumn(TBL_RATE_INCLUSION,'name'," WHERE `id` = '".$value."'").' </label>
					   		<input type="hidden" name="inclusionId[]" value="'.$value.'" />							
						  	<input type="text" class="form-control input-sm inclusion'.selectColumn(TBL_RATE_INCLUSION,'type'," WHERE `id` = '".$value."'").'" placeholder="Enter price" id="inclusion" name="inclusion[]" value="0" data-parsley-required data-parsley-type="digits" onkeyup="rateCalAll(this.id,this.value);">
					  </div>';
}
$availableData .= '|||';
}else {
$availableData .= '|||';
}
$availableData .= $rowRatePlan->tax_percent;
echo $availableData;

?>