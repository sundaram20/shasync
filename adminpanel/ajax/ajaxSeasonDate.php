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




$resContact_1 = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id where   `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  and (`".TBL_RATE."`.company_id='".$row->id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($StartDate))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($EndDate))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($StartDate))."' and '".date('Y-m-d',strtotime($EndDate))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($StartDate))."' and '".date('Y-m-d',strtotime($EndDate))."'))" );

if(num_rows($resContact_1) > 0){	
	$contact  =	'<select class="form-control select2" name="rate_level_id" id="rate_level_id" data-parsley-errors-container="#rate_level_idError" >
					<option value="">Select Rate Master</option>
					  <option value="0">Not Applicable</option>';
		while($rowContact_1 = $db->fetch_object2($resContact_1)){	
			if($contactId==$rowContact_1->id){
				$selected = 'selected="selected"';
			}else {
				$selected = '';
			}
			$contact .= '<option value="'.$rowContact_1->id.'" '.$selected.'>'.ucfirst($rowContact_1->rate_name).' | '.ucfirst($rowContact_1->level_name).' | '.ucfirst($rowContact_1->market_name).'</option>';
		}				 
		$contact .=	'</select>';
	}else{
	$contact .= '<option value="">No Rate Master</option>';
	}

echo '|||'.$contact;







?>