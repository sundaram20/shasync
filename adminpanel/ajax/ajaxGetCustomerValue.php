<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

									$resCat = selectSql(TBL_CUSTOMER,"where status='1' and id_customer='".addslashes($_REQUEST['id_contacts'])."' and type='2' ",' ORDER BY `first_name`');
												  if(num_rows($resCat)){
													$resultCat = $db->fetch_object2($resCat);
													//print_r($resultCat);	
													
													 $k .=	$resultCat->title;
													$k .= "####".$resultCat->first_name;
													$k .= "####".$resultCat->last_name;
													$k .= "####".$resultCat->email;
													$k .= "####".$resultCat->mobile;
													$k .= "####".$resultCat->designation;
													$k .= "####".$resultCat->dateofBirthMonth;
													$k .= "####".$resultCat->dateofBirthday;
													$k .= "####".$resultCat->dateofanniversaryMonth;
													$k .= "####".$resultCat->dateofanniversaryday;
													$k .= "####";	
													echo $k;/*
													$monthNum  = $resultCat->dateofBirthMonth;
 $monthName = date('F', mktime(0, 0, 0, $monthNum, 10));	
														$resultCat->dob;
														
														if($lastInsertId == $resultCat->id_customer){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->title).''.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}*/
												  }
												  
									
									 ?>
                
		
	<?php 
?>