<?php include_once("../../config/auto_loader.php");

/////////////////////////////////////////////////////////////////////////////////////////////////////

$reservation_date = explode(' to ',$_REQUEST['reservation_date']);

$checkin_date = date('Y-m-d',strtotime($reservation_date[0]));

$checkout_date = $reservation_date[1];

$id_company = $_REQUEST['id_company'];

$id_hotel = $_REQUEST['hotel_id'];



$rate_id = 	$_REQUEST['rate_id'];	

							





 $sqlGuestDetail = executeSQl("SELECT * FROM `".TBL_ORDERS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and  `id_company` = '".addslashes($id_company)."' AND `id_hotel` = '".addslashes($id_hotel)."' and `checkin` = '".addslashes($checkin_date)."' "); 

//		 $rowGuestDetail = $db->fetch_object2($sqlGuestDetail);

			 if(mysqli_num_rows($sqlGuestDetail)>0){

				 

				 $resSql = executeSql("SELECT * from `".TBL_COMPANY."` where status='1' and id_company='".addslashes($id_company)."' ");

$rowresult = $db->fetch_object2($resSql);



			$company_credibility	=	$rowresult->company_credibility;

			

			

			if($company_credibility==1){

			

			$Credit	=	"Credit Allowed";

			

			}if($company_credibility==2){

			

			$Credit	=	"Credit Not Allowed";

			

			}

			 echo "Please Recheck Your Booking for Duplicate".'&&&&'.$Credit;

			 

			 }else{

				 

				 $resSql = executeSql("SELECT * from `".TBL_COMPANY."` where status='1' and id_company='".addslashes($id_company)."' ");

$rowresult = $db->fetch_object2($resSql);



			$company_credibility	=	$rowresult->company_credibility;

			

			

			if($company_credibility==1){

			

			$Credit	=	"Credit Allowed &nbsp; Limit: ".$rowresult->credit_limit." Lakhs";

			

			}if($company_credibility==2){

			

			$Credit	=	"Credit Not Allowed";

			

			}


			if($rowresult->credit_form != ''){
				$link = "ajax/ajaxCreditFormDownload.php?fileName=".$rowresult->credit_form;
				$Credit.='&nbsp; <a style="color:#333333;" href="'.$link.'"><i  class="fa fa-cloud-download fa-1x" value="" ></i></a> ';
			}	
			
			

			
			

			

			

				 				  echo " ".' &&&&'.$Credit;

				 

				 }

			  

			 

			 

			 



?>