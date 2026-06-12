<?php include_once("../../config/auto_loader.php");

////////////////////////////////////////////////////////////////////////////////////////

$OrderUniqueID	=$_REQUEST['OrderUniqueID'];



$Evoucher 			= $_REQUEST['Evoucher'];

$EvoucherPassCode 	= $_REQUEST['EvoucherPassCode'];

$EvoucherValue 		= $_REQUEST['EvoucherValue'];







if($EvoucherPassCode!=''){

	

//$sql 			= "SELECT * FROM `fs_promo_code_details` WHERE `promo_code_status` = 3 AND `promo_code` = '".$EvoucherPassCode."' AND CURDATE() between date_valid_from and date_valid_to";

$sql = "SELECT * FROM `fs_promo_code_details` where `promo_code` = '".$EvoucherPassCode."' ";

$res 			= mysqli_query($connNew,$sql);

$NumberOfRows	= mysqli_num_rows($res);

$getRecord		= mysqli_fetch_array($res);





			if($NumberOfRows >0 && $Evoucher == 'apply' && $EvoucherPassCode!=''){

				

			 $_SESSION['editCart'][$OrderUniqueID]['EvoucherPassCode']	=$_REQUEST['EvoucherPassCode'];

			 $_SESSION['editCart'][$OrderUniqueID]['EvoucherValue']		=$getRecord['vaoucher_value'];

			

			

			$Message	=	'<input type="text" class="form-control" id="EvoucherValue" name="EvoucherValue" value="'.$getRecord['vaoucher_value'].'" autocomplete="off" disabled>';

				

				

			}		

			

			else{

				

				$Message ='2';

				

			}

}else{

	

	$Message ='1';

	

	}





echo $Message;

?>