<?php include_once("../../config/auto_loader.php");
/////////////////////////////////////////////////////////////////////////////////////////////////////

 $categoryDropDown='<option value="">Select Hotel</option>';
 $resCat = selectSql(TBL_HOTELS," where status='1' AND id_shop='".addslashes($_SESSION['shop'])."'".$_SESSION['HotelPerHotel']." ",' ORDER BY `name`');
											  if(num_rows($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($row->id_hotel == $resultCat->id){
													   $selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</option>';
												}
											  }
											  
					echo $categoryDropDown;
					die;						  
											  
$_SESSION['editCart']['reservation_date'] = $_REQUEST['reservation_date'];
$reservation_date = explode(' to ',$_REQUEST['reservation_date']);
$checkin_date = $reservation_date[0];
$checkout_date = $reservation_date[1];

$id_company = $_REQUEST['id_company'];
$CompanyNames =  selectColumn(TBL_COMPANY,'name','WHERE id_company="'.$id_company.'" ');
$GroupCompanyList='';
$GroupCompanyList=array();

$resGetAllCompany = executeSql("SELECT `".TBL_COMPANY."`.* from `".TBL_COMPANY."` 	
	where    `".TBL_COMPANY."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_COMPANY."`.status='1'  AND `".TBL_COMPANY."`.name='".$CompanyNames."' group by `".TBL_COMPANY."`.id_company" );						
						if($db->num_rows2($resGetAllCompany)){
						 
							while($resultAllCompanyList = $db->fetch_object2($resGetAllCompany)){
							$GroupCompanyList[]	=	$resultAllCompanyList->id_company;
							
							}
						}
						 $GroupCompanyList=implode(',',$GroupCompanyList);
								

 $rateType = $_REQUEST['rateType'];
//if($rateType == 'Corporate'){
$resCat = executeSql("SELECT `".TBL_RATE."`.* from `".TBL_RATE."` 
	
	where    `".TBL_RATE."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE."`.status='1'  AND `".TBL_RATE."`.company_id='".$id_company."' AND (( `".TBL_RATE."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."')) " );
	$RateIDS='';
	$RateIDS	=array();
						if($db->num_rows2($resCat)){
								  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
									while($resultCat = $db->fetch_object2($resCat)){
									$CorperatRateIDS[]=$resultCat->id;
									}
										}
	
	
	$Corperatrate_ids	=	implode(',',$CorperatRateIDS);
	
$resHotel = executeSql("SELECT `".TBL_RATE_DETAILS."`.* from `".TBL_RATE_DETAILS."` 
	
	where    `".TBL_RATE_DETAILS."`.rate_id IN (".$Corperatrate_ids.")  group by `".TBL_RATE_DETAILS."`.hotel_id" );
	
	  $planData .= '<option '.$selected.' value="">Select Hotel Name</option>';
	 
	  
							  if($db->num_rows2($resHotel)){
								  $CorpetateHotelIds='';
								  $CorpetateHotelIds=array();
								  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
									while($resultCat2 = $db->fetch_object2($resHotel)){
										
									$HOtelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$resultCat2->hotel_id."'");
									$HOtelName_id	=	selectColumn(TBL_HOTELS,'id'," WHERE `id` = '".$resultCat2->hotel_id."'");
									$HotelName_city	=	selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$resultCat2->hotel_id."'");
									$CorpetateHotelIds[]	=	$resultCat2->hotel_id;
									
			$planData .= '<option  value="'.$HOtelName_id.'####Corporate'.'">'.ucfirst($HOtelName).' -'.ucfirst($HotelName_city).'-'.$HOtelName_id.'</option>';
								}
							  }else{
							   //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
							  
							  }




//echo $planData;
//}if($rateType == 'Unit'){
	print_r($CorpetateHotelIds);
$resCat = executeSql("SELECT `".TBL_RATE_UNIT."`.* from `".TBL_RATE_UNIT."` 
	
	where    `".TBL_RATE_UNIT."`.id_shop='".addslashes($_SESSION['shop'])."'  AND `".TBL_RATE_UNIT."`.status='1'  AND `".TBL_RATE_UNIT."`.company_id='".$id_company."' AND (( `".TBL_RATE_UNIT."`.start_date <=  '".date('Y-m-d',strtotime($checkin_date))."' and  `".TBL_RATE_UNIT."`.end_date >= '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE_UNIT."`.start_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."') OR (  `".TBL_RATE_UNIT."`.end_date between '".date('Y-m-d',strtotime($checkin_date))."' and '".date('Y-m-d',strtotime($checkout_date))."')) " );
	$RateIDS='';
	$RateIDS	=array();
						if($db->num_rows2($resCat)){
								  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
									while($resultCat = $db->fetch_object2($resCat)){
									$RateIDS[]=$resultCat->id;
									}
										}
	


$rateids	=	implode(',',$RateIDS);
	
$resHotel = executeSql("SELECT `".TBL_RATE_DETAILS_UNIT."`.* from `".TBL_RATE_DETAILS_UNIT."` 
	
	where    `".TBL_RATE_DETAILS_UNIT."`.rate_id IN (".$rateids.")  group by `".TBL_RATE_DETAILS_UNIT."`.hotel_id" );
	
	  
	 
	  
			  if($db->num_rows2($resHotel)){
				  //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
					while($resultCat2 = $db->fetch_object2($resHotel)){
						$HOtelName		=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$resultCat2->hotel_id."'");
					$HOtelName_id		=	selectColumn(TBL_HOTELS,'id'," WHERE `id` = '".$resultCat2->hotel_id."'");					
					$HotelName_city		=	selectColumn(TBL_HOTELS,'city'," WHERE `id` = '".$resultCat2->hotel_id."'");
					
$planData .= '<option  value="'.$HOtelName_id.'####Unit'.'">'.ucfirst($HOtelName).' -'.ucfirst($HotelName_city).'-'.$HOtelName_id.'</option>';
				}
			  }else{
			   //$planData .= '<option '.$selected.' value="0">ADHOC</option>';
			  
			  }




echo $planData;
//}

?>