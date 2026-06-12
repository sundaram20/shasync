<?php include_once("../config/fron_autoload.php"); 

?>

<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>FS ORDER</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

//print_r($_REQUEST);

if($_REQUEST['submit']	==	'Upload csv'){
print_r($_REQUEST);
print_r($_FILES['fileToUpload']['name']);

//echo $target_dir = $_SERVER['DOCUMENT_ROOT']."/crs/import/";

	$target_dir = "/home/admingcs/public_html/crs/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
		
//$csv_file = "fernhillsP01-04-2018.csv";

$csv_file = $_FILES['fileToUpload']['name'];

				$fieldseparator = ",";
				$lineseparator = "\n";
				
				
				$file = fopen($csv_file, "r");
//$sql_data = "SELECT * FROM prod_list_1 ";

$count = 1;                                         // add this line

				if(!file_exists($csv_file)) {
					echo "File not found. Make sure you specified the correct path.\n";
					exit;
				}
				
				$file = fopen($csv_file,"r");
				
				if(!$file) {
					echo "Error opening data file.\n";
					exit;
				}
				
				$size = filesize($csv_file);
				
				if(!$size) {
					echo "File is empty.\n";
					exit;
				}
$CountInc=1;
		while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
{
    //echo "<pre>";print_r($emapData);
    //exit();
    $count++;                                      // add this line

    if($count>1){   
	
	
	 $hotelname=$emapData[0]; $bookingnumber=$emapData[1]; $bookingdate=$emapData[2];  $checkin=$emapData[3];  $checkout=$emapData[4];  $agentname=$emapData[5];  $gustname=$emapData[6];  $countryname=$emapData[7];  $paymentmode=$emapData[8];  $planning=$emapData[9];  $status=$emapData[10];  $specialins=$emapData[11];  $single=$emapData[12]; $double=$emapData[13]; $triple=$emapData[14]; $FGCP=$emapData[15]; $ARRIVING_FROM=$emapData[16]; $DEPARTING_TO=$emapData[17];  $TRAVEL_MODE=$emapData[18];  $RATE_TYPE=$emapData[19]; $USER_Name=$emapData[20]; $NEXT_ID=$emapData[21]; $PICKYN=$emapData[22];  $BOOKING_THROUGH=$emapData[23];  $STATUS_FLAG=$emapData[24];  $no_of_days=$emapData[25];  $DEAR1=$emapData[26]; $BS=$emapData[28];
 $RoomDetails=$emapData[29]; $BOOKER_EMAIL_ID=$emapData[30];$BOOKER_CONTACT_NUMBER=$emapData[31];
    $payment_status=$emapData[34];
   $payment_date=$emapData[35];$pickup_details=$emapData[36];$pickup=$emapData[37];$amount_received=$emapData[38];$payment_reference=$emapData[39];
   
   $operator_name=$emapData[40];
   $series_name	=	$emapData[41];
 
    $id_shop=$emapData[32];
$hotelname_get =$emapData[0];
$hotelname_get2 = explode(",",$hotelname_get);
$hotelname = $hotelname_get2[0];

$hotel_code_get = $emapData[1];
$hotel_code_get2 = explode("/",$hotel_code_get);
$hotel_code = $hotel_code_get2[0];

$bookingnumber = $emapData[1];


$Other_reference	=$bookingnumber;
 
$exp	=explode('/',$checkin);
 $Day	=	$exp[0];
 $Month	=	$exp[1];
 $year	=	$exp[2];
	
/*echo $today = date('d/m/Y h:i A');
echo '<br />';*/
 $Newcheckin  = ($year.'-'.$Month.'-'.$Day);	 
 
 
 
$exp_checkout	=explode('/',$checkout);
 $Day_checkout	=	$exp_checkout[0];
 $Month_checkout	=	$exp_checkout[1];
 $year_checkout	=	$exp_checkout[2];
	
/*echo $today = date('d/m/Y h:i A');
echo '<br />';*/
 $Newcheckout = ($year_checkout.'-'.$Month_checkout.'-'.$Day_checkout);	  

			
$double_aud	=($double*2);	
		 	
$triple_adu =($triple*3);
	
$total_adu	= $double_aud+$triple_adu+$single;

	
 	 $query1 = "SELECT * FROM fs_hotels WHERE name ='".$hotelname."' and id_shop='".$id_shop."'" ;
	
	/*$result1 = mysql_query($query1,$link) or die('Errant query:  '.$query1);
	$query1count = mysql_num_rows($result1);
	
*/
	$result1 = executeSql($query1,$link);
	$query1count = mysql_num_rows($result1);
		
	
	$query1data = mysql_fetch_array($result1);

			//$id_shop = trim(stripslashes($query1data['id_shop']));
			  $id_shop_group =1; 
			  $hotel_code = trim(stripslashes($query1data['hotel_code']));
			  $id_hotels=trim(stripslashes($query1data['id']));
		
		
		$id_lang=1;
		$id_hotel = $HotelReservations_ID;
		//$id_rate = 0;
		//$id_company = 20;
		//$id_company_person = 29;//SElf
		
		$id_cart = 0;
		$id_currency = 1;
		
		
		if($ResStatus=="Commit") { 
		$ResStatusNew = "Confirmed";
		}else if($ResStatus=="Cancel") {
		$ResStatusNew = "Cancelled";	
		}else {
		$ResStatusNew = "Waitlisted";
		}
		
		$query2 = "SELECT * FROM fs_htl_booking_status WHERE name='".$status."'";
		/*$result2 = mysql_query($query2,$link) or die('Errant query:  '.$query2);
		$query2count = mysql_num_rows($result2);
		*/
	
	
		
		$result2 = executeSql($query2,$link);
	    $query2count = mysql_num_rows($result2);
		
	
		if($query2count>0) {
	$query2data = mysql_fetch_array($result2);
			$booking_status = trim(stripslashes($query2data['id']));
		
		}else{
		$booking_status="0";	
		}
 
	
	
		$tarrif_price = 0;
		$food_price = 0;
		$extra_price = 0;
		$subtotal = 0;
		$total_tax = 0;
		$total_price = 0;
		$amount_received = 0;
		
		//$subtotal = $Total_AmountBeforeTax;
		//$total_tax = $Total_TotalBookingAmount - $Total_AmountBeforeTax;
		//$total_price = $Total_TotalBookingAmount;
		//$amount_received = $Total_TotalBookingAmount;
		$balance = 0;
		
		
		$total_products =0; 
		
		
				if($RoomType_AgeQualifyingCode=="10"){
		$total_adults =	$RoomType_AgeQualifyingCount;
		}else {
		$total_adults = 0;	
		}
		
		
		if($RoomType_AgeQualifyingCode=="8"){
		$total_child =	$RoomType_AgeQualifyingCount;
		}else {
		$total_child = 0;	
		}
		
		
		
		$conversion_rate = "1.00";
		$discount_type = 0;
		$discount_var = 0;
		$total_discounts = 0;
		
		$invoice_number = 0;
		
		
$exp_bookingdate	=explode('/',$bookingdate);
$Day_bookingdate	=	$exp_bookingdate[0];
$Month_bookingdate	=	$exp_bookingdate[1];
$year_bookingdate	=	$exp_bookingdate[2];
$invoice_date = ($year_bookingdate.'-'.$Month_bookingdate.'-'.$Day_bookingdate);	 
 
 $payment_date;
$exp_payment_date	=explode('/',$payment_date);
$Day_payment_date	=	$exp_payment_date[0];
$Month_payment_date	=	$exp_payment_date[1];
$year_payment_date	=	$exp_payment_date[2];	

$payment_date = ($year_payment_date.'-'.$Month_payment_date.'-'.$Day_payment_date);
		
		
		$checkin = $Newcheckin;
		$checkout = $Newcheckout;
		
		$now = $checkin; // or your date as well
		$your_date = strtotime($checkout);
		$datediff = $now - $your_date;
		
		//$no_of_days =  round($datediff / (60 * 60 * 24));
		
		$valid = 1;
		$segment_id = 1; 
		
		
	/**********************OPERATOR NAME SATART**********************************************************************/	
if($operator_name !=''){
		$CheckOperaterID = "SELECT * FROM `".TBL_OPERATOR_MASTER."` WHERE `name` = '".addslashes($operator_name)."' and id_shop='".$id_shop."'" ;
		$OperatorResult = executeSql($CheckOperaterID,$link);
		$OperatorQueryCount = mysql_num_rows($OperatorResult);
		$opequery4data = mysql_fetch_array($OperatorResult);
if($OperatorQueryCount != '0'){
		   
	 $operator_id=$opequery4data['id'];
		   
}else{

		   $OperatorInsert = "   	INSERT INTO `".TBL_OPERATOR_MASTER."` SET 
							`name` = '".addslashes($operator_name)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1'";
			 $OperatorInsert .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			executeSql($OperatorInsert);
					$operator_id = $db->insert_id();
				
				
	}
}else{
	
	$operator_id	=0;
	}


if($series_name!=''){
 $CheckSeriesID = "SELECT * FROM `".TBL_SERIES_MASTER."` WHERE `name` = '".addslashes($series_name)."' and id_shop='".$id_shop."'" ;
		$SeriesResult = executeSql($CheckSeriesID,$link);
		 $SeriesQueryCount = mysql_num_rows($SeriesResult);
		 $Seriesquery4data = mysql_fetch_array($SeriesResult);
if($SeriesQueryCount != 0){
	
	 $series_id	=$Seriesquery4data['id'];
	
	}else{
		  
		 
		  
		   $seriesSql = "   	INSERT INTO `".TBL_SERIES_MASTER."` SET 
							`name` = '".addslashes($series_name)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1'";
			 $seriesSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			executeSql($seriesSql);
				$series_id = $db->insert_id();
}
}else{
	
	$series_id	=	0;
	}
/****************************OPERATOR NAME ENDDDDDDDDDDDDDDDDDD**************************************************************/

		
		
		
		$type = "N";
		$reminder = 0;
		
		$date_created = date("Y-m-d H:i:s");
		$last_modified = date("Y-m-d H:i:s");
		$last_modified_by = 9;
		
		//get last id of orders table//
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
 $query11 = "SELECT area,id_company FROM fs_company WHERE name ='".$agentname."' and id_shop='".$id_shop."'" ;


$result11 = executeSql($query11,$link);
$query11count = mysql_num_rows($result11);		

$query11data = mysql_fetch_array($result11);
$id_area= trim(stripslashes($query11data['area']));
$id_company= trim(stripslashes($query11data['id_company']));
if($id_company==''){


		 $addNewCompanyName= "  INSERT INTO fs_company SET 
													`name` = '".addslashes($agentname)."',
													`id_shop_group` = '1',					
													`id_shop` ='".$id_shop."',
													`booking` = '1'
													,`status` = '1'
													,`company_credibility` = '2'
													,`details` = 'imported'
													,`id_lang` = '1'";
								executeSql($addNewCompanyName);
								$id_company = $db->insert_id();
}
	
	
	 $query12 = "SELECT  fs_areas_assign.user_id FROM  fs_areas_assign WHERE id ='".$id_area."' and id_shop='".$id_shop."'" ;	
	
	$result12 	= executeSql($query12,$link);
	$query12count 	= mysql_num_rows($result12);	
	
	$query12data 	= mysql_fetch_array($result12);
	$id_executive	= trim(stripslashes($query12data['user_id']));
			
			
			
			
	
	
	    $query14	=	"SELECT id FROM  fs_rate WHERE (`company_id` = '".$id_company."' || company_id=0) and ((start_date <=  '".$Newcheckin."' and end_date >= '".$Newcheckin."') OR ( start_date between '".$Newcheckin."' and '".$Newcheckin."') OR ( end_date between '".$Newcheckin."' and '".$Newcheckin."')) and id_shop='".$id_shop."'";
	
	$result14 = executeSql($query14,$link);
	$query14count = mysql_num_rows($result14);	
	
	$query14data = mysql_fetch_array($result14);
	 $id_rate	= $query14data['id'];		
			
			
	
	$query7 		= mysql_query("Insert into fs_customer set first_name = '".$gustname."',type='1' ,id_shop='".$id_shop."',status='1'");
	$id_customer 		= mysql_insert_id();
	
	
	
	$query8 		= mysql_query("Insert into fs_customer set first_name = '".$DEAR1."', mobile = '".$BOOKER_CONTACT_NUMBER."', email = '".$BOOKER_EMAIL_ID."', id_company = '".$id_company."',type='2' ,id_shop='".$id_shop."',status='1'");
	$id_company_person 		= mysql_insert_id();	
	
		
	$query15 		= "SELECT * FROM fs_rate_assign_details WHERE hotel_id ='".$id_hotels."' and rate_id='".$id_rate."' " ;	
	$result15 		= executeSql($query15,$link);

	
	 $query15data 			= mysql_fetch_array($result15);
	 $fs_rate_assign_id		= trim(stripslashes($query15data['id']));
			
		
		
		
		$query4 = "SELECT * FROM fs_orders where id_hotel ='".$id_hotels."' order by reference desc ";
		
		
		$result4 = executeSql($query4,$link);
		$query4count = mysql_num_rows($result4);
		
		
		if($query4count>0) {
		$query4data = mysql_fetch_array($result4);
			 $fs_orders_id = explode('/',$query4data['reference']);
			 $fs_orders_id	=	$fs_orders_id[1];
			$fs_orders_idnew = $fs_orders_id+1;
		
		}else{
		$fs_orders_idnew = 1;	
		}
		
		$invID = str_pad($fs_orders_idnew, 3, '0', STR_PAD_LEFT);
		  $reference = $hotel_code."/".$invID;
		$code = 0;
		
	
		/*if($reference!='' && $code!='' && $id_shop_group!='' && $id_shop!='' && $id_lang!='' && $id_hotels!='' && $id_rate!='' && $id_customer!='' && $id_company!='' && $id_company_person!='' && $id_cart!='' && $id_currency!='' && $payment_status!='' && $booking_status!='' && $conversion_rate!='' && $tarrif_price!='' && $food_price!='' && $extra_price!='' && $discount_type!='' && $discount_var!='' && $total_discounts!='' && $subtotal!='' && $total_tax!='' && $total_price!='' && $balance!='' && $total_products!='' && $total_adu!='' && $total_child!='' && $invoice_number!='' && $invoice_date!='' && $payment_date!='' && $Newcheckin!='' && $Newcheckout!='' && $no_of_days!='' && $valid!='' && $segment_id!='' && $series_id!='' && $operator_id!='' && $type!='' && $reminder!='' && $date_created!='' && $last_modified!='' && $last_modified_by!='' && $RATE_TYPE!='' && $id_executive!='' && $id_area!='' && $Other_reference!='' && $paymentmode!='' && $specialins!='' && $pickup_details!='' && $pickup!='' && $amount_received!='' && $payment_reference){*/
		
		//if($reference=='$reference' && $code=='$Other_reference'){
			
					 $Insert_into_Order_Table = "Insert into fs_orders(reference,code,id_shop_group,id_shop,id_lang,id_hotel,id_rate,id_customer,id_company,id_company_person,id_cart,id_currency,payment_status,booking_status,conversion_rate,tarrif_price,food_price,extra_price,discount_type,discount_var,total_discounts,subtotal,total_tax,total_price,balance,total_products,total_adults,total_child,invoice_number,invoice_date,payment_date,checkin,checkout,no_of_days,valid,segment_id,series_id,operator_id,type,reminder,date_created,last_modified,last_modified_by,rate_text,id_executive,id_area,other_reference,payment_remarks,requests,pickup_details,pickup,amount_received,payment_reference)Values('$reference','$code','$id_shop_group','$id_shop','$id_lang','$id_hotels','$id_rate','$id_customer','$id_company','$id_company_person','$id_cart','$id_currency','$payment_status','$booking_status','$conversion_rate','$tarrif_price','$food_price','$extra_price','$discount_type','$discount_var','$total_discounts','$subtotal','$total_tax','$total_price','$balance','$total_products','$total_adu','$total_child','$invoice_number','$invoice_date','$payment_date','$Newcheckin','$Newcheckout','$no_of_days','$valid','$segment_id','$series_id','$operator_id','$type','$reminder','$date_created','$last_modified','$last_modified_by','$RATE_TYPE','$id_executive','$id_area','$Other_reference','$paymentmode','$specialins','$pickup_details','$pickup','$amount_received','$payment_reference')";
					
				
		//$Insert_into_Order_Table_Run = mysql_query($Insert_into_Order_Table,$link) or die('Errant query:  '.$Insert_into_Order_Table);
		
		$Insert_into_Order_Table_Run = executeSql($Insert_into_Order_Table);
		$in_last_id = mysql_insert_id();
		
		echo  "<br> $count Iserted ID= ".$in_last_id."Other_reference=>".$Other_reference." Rate id=>".$id_rate."Company Id==>".$id_company."id_customer==>".$id_customer.'payment_date'.$payment_date;
		
		//}else{
			
			//echo  "<br><div style='background-color: #4f81bc;color:#fff;'>$count The record already exists= ".$in_last_id."Other_reference=>".$Other_reference." Rate id=>".$id_rate."Company Id==>".$id_company."id_customer==>".$id_customer.'payment_date'.$payment_date."</div>";
			
			
		//}
		
		
		//Customer ID id_rate,id_customer,id_company,
		
		
		
		$room_id=$RoomType_RoomTypeCode;

		$rate_plan_id=0;
		
		$dated = $HotelReservations_CreateDateTime;
		$type=0;
		$room_quantity = $RoomType_NumberOfUnits;
		$adults = $total_adults;
		$child = $total_child;
		
		$unique_code = "CODE";
		
		
		/*$Insert_into_Order_Details = "Insert into fs_order_detail(id_order,id_shop,hotel_id,room_id,rate_id,rate_plan_id,rate_assign_id,dated,type,room_quantity,adults,child,tarrif_price,food_price,extra_price,total_price,original_product_price,unique_code)Values('$in_last_id','$id_shop','$id_hotels','$room_id','$id_rate','$rate_plan_id','$fs_rate_assign_id','$dated','$type','$room_quantity','$adults','$child','$tarrif_price','$food_price','$extra_price','$total_price','$Base_AmountBeforeTax','$unique_code')";
		//$Insert_into_Order_Details_Run = mysql_query($Insert_into_Order_Details,$link) or die('Errant query:  '.$Insert_into_Order_Details);
		$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);*/
		
    }                                             
}
echo "Sucessful";

}





?>