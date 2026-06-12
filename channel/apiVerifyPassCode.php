<?php include_once("../config/fron_autoload.php"); 
/////////////////////////////////////////////////////////////

   
$postData = file_get_contents('php://input');

//$postData = file_get_contents('testbooking.xml');

$xmlarray= json_decode($postData);



foreach($xmlarray  as $key=>$value){
 $filter_id[$key] = $value;
}


 $sql 			= "SELECT * FROM `fs_promo_code_details` WHERE  `promo_code` LIKE '%".addslashes(encryptor(decrypt,$filter_id['promocode']))."%' AND `pass_code` LIKE '%".$filter_id['verifyevoucher']."%'  AND CURDATE() between date_valid_from and date_valid_to";
$res 			= mysql_query($sql);
$NumberOfRows	= mysql_num_rows($res);
$getRecord	= mysql_fetch_array($res);

if($NumberOfRows >0 && $getRecord['promo_code_status']!= 3){

	
	$issueSql= "UPDATE `fs_promo_code_details` SET `issue_date` ='".date('Y-m-d')."',`promo_code_status`='3' WHERE `promo_code` LIKE '%".addslashes(encryptor(decrypt,$filter_id['promocode']))."%' AND `pass_code` LIKE '%".$filter_id['verifyevoucher']."%'  ";

	$issueRes =	executeSql($issueSql);

	$title = base64_encode($getRecord['emp_title']);
	$name = base64_encode($getRecord['employee_name']);
	$serial = base64_encode($getRecord['serial_no']);
	$promocode = base64_encode($getRecord['promo_code']);
	$date_from = base64_encode($getRecord['date_valid_from']);
	$date_to = base64_encode($getRecord['date_valid_to']);
	$value = base64_encode($getRecord['food_value']);

	
	$Message = "https://crs.roomstatushub.com/adminpanel/pdf-template/evoucher.php?employee=".$name."&serial_no=".$serial."&promocode=".$promocode."&date_from=".$date_from."&date_to=".$date_to."&title=".$title."&value=".$value." ";
	
	
	//$Message = "valid";
}
elseif ($getRecord['promo_code_status'] == 3) {
	$Message = false;
}
else{
	$Message = true;
}
	
	
	
		echo $Message;



					

//}

?>