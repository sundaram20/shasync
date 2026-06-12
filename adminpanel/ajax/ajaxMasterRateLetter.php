<?php include_once("../../config/auto_loader.php");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$season = $_REQUEST['seasonId'];
//////////////////////////////getting and showing season date//////////////////////////////////////////////////////

if($season != ''){
$resSeason = executeSql("SELECT * from `".TBL_RATE_SEASON."` where status='1' and id='".addslashes($season)."' limit 0,1");
$rowSeason = $db->fetch_object2($resSeason);
echo date('d-m-Y',strtotime($rowSeason->start_date)).','.date('d-m-Y',strtotime($rowSeason->end_date));
$StartDate	=	 date('d-m-Y',strtotime($rowSeason->start_date));
$EndDate	=	date('d-m-Y',strtotime($rowSeason->end_date));

}else {
echo date("d-m-Y").','.date('d-m-Y',strtotime("+1 days"));
$StartDate	=	 date("d-m-Y");
$EndDate	=	date('d-m-Y',strtotime("+1 days"));
}

	
	$contact  =	'<select class="form-control" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="updateLevelAndMarket(); rateLetterMasterFunction();" <?php echo $disabled ?>>
                    <option value="">Select Rate Level</option>';
					
	$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_LEVEL."`.id as rate_level_id ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id where    `".TBL_RATE."`.status='1' AND `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.seasonId='".addslashes($season)."'  and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' ) ");
	if($db->num_rows2($resCat)){
	   $i=1;
	   while($resultCat = $db->fetch_object2($resCat)){
			
			if($i==1){
				if($row->rate_category_id==0){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$contact .= '<option '.$selected.' value="0">Not Applicable</option>';
			}
														
			if($resultCat->id == $row->rate_category_id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$Season_Name	=	selectColumn(TBL_RATE_SEASON,'name'," WHERE `id` = '".addslashes($resultCat->seasonId)."'");
			$contact .= '<option '.$selected.' value="'.$resultCat->id.'|'.$resultCat->rate_level_id.'">'.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).' | '.ucfirst($Season_Name).'</option>';
			$i++;
		}
	}else{
		$contact  =	'<select class="form-control" name="rate_level_id" id="rate_level_id" data-parsley-required data-parsley-errors-container="#rate_level_idError" onchange="updateLevelAndMarket(); rateLetterMasterFunction();" <?php echo $disabled ?>>
                    <option value="">Select Rate Level</option>
                    <option value="0">Not Applicable</option>';
	}

	

echo $contact;







?>