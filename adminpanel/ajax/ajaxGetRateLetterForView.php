<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];

$id_company = $_REQUEST['id_company'];
$hotel_id = $_REQUEST['hotel_id'];
$rate_id = 	$_SESSION['editCart']['rate_id'];	
 
$rate_level_assgin = selectColumn(TBL_COMPANY,'id_rate_level'," WHERE `id_company` = '".addslashes($id_company)."'");

$CompanyNames =  selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$id_company.'" ');
$companySql="SELECT `".TBL_COMPANY."`.* from `".TBL_COMPANY."` 	
	where    `".TBL_COMPANY."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_COMPANY."`.status='1'  AND UPPER(REPLACE(`".TBL_COMPANY."`.name,'&AMP;','&'))='".strtoupper(str_replace('&AMP;','&',$CompanyNames))."' ";
	

$resGetAllCompany = executeSql($companySql);

						if($db->num_rows2($resGetAllCompany)){
						 
							while($resultAllCompanyList = $db->fetch_object2($resGetAllCompany)){
							$GroupCompanyList[]	=	$resultAllCompanyList->id_company;
							
							}
						}
						 $GroupCompanyList=implode(',',$GroupCompanyList);


$rateType = $_REQUEST['rateType'];
//if($rateType == 'Corporate'){
	 $sqlRso="SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id  LEFT JOIN `fs_rate_details` ON `fs_rate`.id=`fs_rate_details`.rate_id
	
	where    `fs_rate_details`.hotel_id='".addslashes($_REQUEST['hotel_id'])."' AND  `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1' and (`".TBL_RATE."`.company_id IN (".$GroupCompanyList.") && `".TBL_RATE."`.company_id!='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))  group by `".TBL_RATE."`.rate_name";
	
	$resCat = executeSql($sqlRso);
	
	
	  $planData .= '<option '.$selected.' value="">Select Rate Latters</option>';
	 
	  $planData .= '<option  value="">-----Corporate rate letters-----</option>';
							  if($db->num_rows2($resCat)){
								  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
									while($resultCat = $db->fetch_object2($resCat)){
									if($rate_id == $resultCat->id){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									$planData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).'</option>';
								}
							  }else{
							   //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
							  
							  }
//}if($rateType == 'Unit'){
////////////////// UNIT RATE START ////////////////
$resCatUnit = executeSql("SELECT `".TBL_RATE_UNIT."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE_UNIT."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE_UNIT."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE_UNIT."`.market=`".TBL_RATE_MARKET."`.id  LEFT JOIN `fs_rate_details_unit` ON `fs_rate_unit`.id=`fs_rate_details_unit`.rate_id
	
	where    `fs_rate_details_unit`.hotel_id='".addslashes($_REQUEST['hotel_id'])."' AND  `".TBL_RATE_UNIT."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE_UNIT."`.status='1' and (`".TBL_RATE_UNIT."`.company_id IN (".$GroupCompanyList.") && `".TBL_RATE_UNIT."`.company_id!='0' ) and (( `".TBL_RATE_UNIT."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE_UNIT."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE_UNIT."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE_UNIT."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))  group by `".TBL_RATE_UNIT."`.rate_name" );	
	$planData .= '<option  value="">--------Unit rate letters--------</option>';						  
	  if($db->num_rows2($resCatUnit)){
		  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
			while($resultCatUnit = $db->fetch_object2($resCatUnit)){
			if($rate_id == $resultCatUnit->id){
				$selected = 'selected="selected"';
			}else{
				$selected = '';
			}
			$planData .= '<option '.$selected.' value="'.$resultCatUnit->id.' ">'.ucfirst($resultCatUnit->rate_name).' | '.ucfirst($resultCatUnit->level_name).' | '.ucfirst($resultCatUnit->market_name).'</option>';
		}
	  }else{
	   //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
	  
	  }
//}

echo $planData;



?>