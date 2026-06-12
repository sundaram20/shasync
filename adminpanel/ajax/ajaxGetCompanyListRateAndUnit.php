<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];

$id_company = $_REQUEST['id_company'];
$hotel_id = $_REQUEST['hotel_id'];
$rate_id = 	$_SESSION['editCart']['rate_id'];	
 
//$rate_level_assgin = selectColumn(TBL_COMPANY,'id_rate_level'," WHERE `id_company` = '".addslashes($id_company)."'");

$resCat = executeSql("SELECT `".TBL_RATE."`.* from `".TBL_RATE."` 
	
	where    `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1'  AND `".TBL_RATE."`.company_id!='0' AND (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))  group by `".TBL_RATE."`.company_id" );
	
	
	
	
	
$planData .= '<option '.$selected.' value="">Select Company Name</option>';

$planData .= '<option  value="">-----By Corporate -----</option>';
					  if($db->num_rows2($resCat)){
						 
							while($resultCat = $db->fetch_object2($resCat)){
								$RateLetterCompany	= selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$resultCat->company_id.'" ');
			$RateLetterCompany_city	= selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$resultCat->company_id.'" ');
			
			
		
			
							if($rate_id == $resultCat->id){
								$selected = 'selected="selected"';
							}else{
								$selected = '';
							}
							$planData .= '<option '.$selected.' value="'.$resultCat->company_id.'####Corporate'.'">'.ucfirst($RateLetterCompany).'</option>';
						}//'-Corporate'.'-'.$resultCat->company_id.
					  }else{
					   //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
					  
					  }
					

////////////////// UNIT RATE START ////////////////
$resCatUnit = executeSql("SELECT `".TBL_RATE_UNIT."`.* from `".TBL_RATE_UNIT."` 
	
	where    `".TBL_RATE_UNIT."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE_UNIT."`.status='1' AND `".TBL_RATE_UNIT."`.company_id!='0' AND (( `".TBL_RATE_UNIT."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE_UNIT."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE_UNIT."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE_UNIT."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))  group by `".TBL_RATE_UNIT."`.company_id" );	
	$planData .= '<option  value="">--------By Unit --------</option>';						  
	  if($db->num_rows2($resCatUnit)){
		 
			while($resultCatUnit = $db->fetch_object2($resCatUnit)){
				
				$RateLetterUnitCompany	= selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$resultCatUnit->company_id.'" ');
				$RateLetterUnitCompanyCity	= selectColumn(TBL_COMPANY,'city','WHERE id_company="'.$resultCatUnit->company_id.'" ');
			if($rate_id == $resultCatUnit->id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$planData .= '<option  '.$selected.' value="'.$resultCatUnit->company_id.'####Unit'.'"> '.ucfirst($RateLetterUnitCompany).'</option>';
		}//'-Unit'.'-'.$resultCatUnit->company_id.
	  }else{
	   //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
	  
	  }


echo $planData;



?>