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


//$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id where     FIND_IN_SET(`".TBL_RATE."`.rate_level_id,'".$rate_level_assgin."')  and (`".TBL_RATE."`.company_id='".$id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))" );
	
	$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id  LEFT JOIN `fs_rate_details` ON `fs_rate`.id=`fs_rate_details`.rate_id
	
	where    `fs_rate_details`.hotel_id='".addslashes($_REQUEST['hotel_id'])."' AND  `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1' and (`".TBL_RATE."`.company_id='".$id_company."' && `".TBL_RATE."`.company_id!='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))  group by `".TBL_RATE."`.rate_name" );
	
	/*$resCat = executeSql("SELECT `".TBL_RATE."`.*, `".TBL_RATE_LEVEL."`.name as level_name ,`".TBL_RATE_MARKET."`.name as market_name from `".TBL_RATE."` LEFT JOIN `".TBL_RATE_LEVEL."` ON `".TBL_RATE."`.rate_level_id=`".TBL_RATE_LEVEL."`.id   LEFT JOIN `".TBL_RATE_MARKET."` ON `".TBL_RATE."`.market=`".TBL_RATE_MARKET."`.id  
	
	where      `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1' and (`".TBL_RATE."`.company_id='".$id_company."' || `".TBL_RATE."`.company_id='0' ) and (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."'))  " );*/
	  $planData .= '<option '.$selected.' value="">Select Rate Latter</option>';
							  if($db->num_rows2($resCat)){
								  $planData .= '<option '.$selected.' value="0">ADHOC</option>';
									while($resultCat = $db->fetch_object2($resCat)){
									if($rate_id == $resultCat->id){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									$planData .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->rate_name).' | '.ucfirst($resultCat->level_name).' | '.ucfirst($resultCat->market_name).'</option>';
								}
							  }else{
							   $planData .= '<option '.$selected.' value="0">ADHOC</option>';
							  
							  }
							 echo $planData;
											




?>